<?php
namespace OsumiFramework\OFW\Plugins;

abstract class WebSocketServer {
  protected int $maxBufferSize;
  protected $master;
  protected array $sockets                             = [];
  protected array $users                               = [];
  protected array $heldMessages                        = [];
  protected bool $interactive                          = true;
  protected bool $headerOriginRequired                 = false;
  protected bool $headerSecWebSocketProtocolRequired   = false;
  protected bool $headerSecWebSocketExtensionsRequired = false;

  function __construct(string $addr, int $port, int $bufferLength = 2048) {
    $this->maxBufferSize = $bufferLength;
    $this->master = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)  or die('Failed: socket_create()');
    socket_set_option($this->master, SOL_SOCKET, SO_REUSEADDR, 1) or die('Failed: socket_option()');
    socket_bind($this->master, $addr, $port)                      or die('Failed: socket_bind()');
    socket_listen($this->master, 20)                              or die('Failed: socket_listen()');
    $this->sockets['m'] = $this->master;
    $this->stdout('Server started'.PHP_EOL.'Listening on: '.$addr.':'.$port.PHP_EOL.'Master socket: ' . strval(spl_object_id($this->master)) );
  }

  /*abstract protected function process(WebSocketUser $user, string $message); // Called immediately when the data is recieved.
  abstract protected function connected(WebSocketUser $user);                // Called after the handshake response is sent to the client.
  abstract protected function closed(WebSocketUser $user);                   // Called after the connection is closed.*/

  // Override to handle a connecting user, after the instance of the User is created, but before
  // the handshake has completed.
  protected function connecting(WebSocketUser $user): void {}

  protected function send(WebSocketUser $user, string $message): void {
    if ($user->handshake) {
      $message = $this->frame($message, $user);
      $result = @socket_write($user->socket, $message, strlen($message));
    }
    else {
      // User has not yet performed their handshake.  Store for sending later.
      $holdingMessage = ['user' => $user, 'message' => $message];
      $this->heldMessages[] = $holdingMessage;
    }
  }

  // Override this for any process that should happen periodically.  Will happen at least once
  // per second, but possibly more often.
  protected function tick(): void {}

  // Core maintenance processes, such as retrying failed messages.
  protected function _tick(): void {
    // Loop message queue
    foreach ($this->heldMessages as $key => $hm) {
      $found = false;
      // Loop users
      foreach ($this->users as $currentUser) {
        // Is the message for a user
        if ($hm['user']->socket == $currentUser->socket) {
          $found = true;
          // If we are in handshake, send the message back (no need to process)
          if ($currentUser->handshake) {
            unset($this->heldMessages[$key]);
            $this->send($currentUser, $hm['message']);
          }
        }
      }
      // If user no longer in the list of connected users, drop the message.
      if (!$found) {
        unset($this->heldMessages[$key]);
      }
    }
  }

  /**
   * Main processing loop
   */
  public function run(): void {
    // Loop forever
    while (true) {
      // Should never happen, if sockets are empty, add the master socket
      if (empty($this->sockets)) {
        $this->sockets['m'] = $this->master;
      }
      // Get all of the sockets
      $read = $this->sockets;
      // Set null values
      $write = $except = null;
      // Maintenance
      $this->_tick();
      $this->tick();
      // Load the sockets
      @socket_select($read, $write, $except, 1);
      foreach ($read as $socket) {
        if ($socket === $this->master) {
          $client = socket_accept($socket);
          if ($client === false) {
            $this->stderr('Failed: socket_accept()');
            continue;
          }
          else {
            $this->connect($client);
            $this->stdout('Client connected. ' . strval(spl_object_id($client)) );
          }
        }
        else {
          $numBytes = @socket_recv($socket, $buffer, $this->maxBufferSize, 0);
          if ($numBytes === false) {
            $sockErrNo = socket_last_error($socket);
            switch ($sockErrNo) {
              case 102: // ENETRESET    -- Network dropped connection because of reset
              case 103: // ECONNABORTED -- Software caused connection abort
              case 104: // ECONNRESET   -- Connection reset by peer
              case 108: // ESHUTDOWN    -- Cannot send after transport endpoint shutdown -- probably more of an error on our part, if we're trying to write after the socket is closed.  Probably not a critical error, though.
              case 110: // ETIMEDOUT    -- Connection timed out
              case 111: // ECONNREFUSED -- Connection refused -- We shouldn't see this one, since we're listening... Still not a critical error.
              case 112: // EHOSTDOWN    -- Host is down -- Again, we shouldn't see this, and again, not critical because it's just one connection and we still want to listen to/for others.
              case 113: // EHOSTUNREACH -- No route to host
              case 121: // EREMOTEIO    -- Rempte I/O error -- Their hard drive just blew up.
              case 125: // ECANCELED    -- Operation canceled
                $this->stderr('Unusual disconnect on socket ' . strval(spl_object_id($socket)) );
                $this->disconnect($socket, true, $sockErrNo); // disconnect before clearing error, in case someone with their own implementation wants to check for error conditions on the socket.
                break;
              default:
                $this->stderr('Socket error: ' . socket_strerror($sockErrNo));
            }
          }
          elseif ($numBytes == 0) {
            $this->disconnect($socket);
            $this->stderr('Client disconnected. TCP connection lost: ' . strval(spl_object_id($socket)) );
          }
          else {
            $user = $this->getUserBySocket($socket);
            if (!$user->handshake) {
              $tmp = str_replace("\r", '', $buffer);
              if (strpos($tmp, "\n\n") === false ) {
                continue; // If the client has not finished sending the header, then wait before sending our upgrade response.
              }
              $this->doHandshake($user, $buffer);
            }
            // Split packet into frame and send it to deframe
            else {
              $this->split_packet($numBytes, $buffer, $user);
            }
          }
        }
      }
    }
  }

  protected function connect($socket): void {
    // If socket exists, setup user
    if (!empty($socket)) {
      $user = new WebSocketUser(uniqid('u'), $socket);
      $this->users[$user->id] = $user;
      $this->sockets[$user->id] = $socket;
      $this->connecting($user);
    }
    else {
      $this->disconnect($socket);
    }
  }

  protected function disconnect($socket, bool $triggerClosed = true, $sockErrNo = null): void {
    $disconnectedUser = $this->getUserBySocket($socket);

    if ($disconnectedUser !== null) {
      unset($this->users[$disconnectedUser->id]);

      if (array_key_exists($disconnectedUser->id, $this->sockets)) {
        unset($this->sockets[$disconnectedUser->id]);
      }

      if (!is_null($sockErrNo)) {
        socket_clear_error($socket);
      }

      if ($triggerClosed) {
        $this->stdout('Client disconnected. '. strval(spl_object_id($disconnectedUser->socket)) );
        $this->closed($disconnectedUser);
        socket_close($disconnectedUser->socket);
      }
      else {
        $message = $this->frame('', $disconnectedUser, 'close');
        @socket_write($disconnectedUser->socket, $message, strlen($message));
      }
    }
  }

  protected function doHandshake(WebSocketUser $user, $buffer): void {
    $magicGUID = '258EAFA5-E914-47DA-95CA-C5AB0DC85B11';
    $headers = [];
    $lines = explode("\n", $buffer);
    foreach ($lines as $line) {
      if (strpos($line,':') !== false) {
        $header = explode(':',$line,2);
        $headers[strtolower(trim($header[0]))] = trim($header[1]);
      }
      elseif (stripos($line,'get ') !== false) {
        preg_match("/GET (.*) HTTP/i", $buffer, $reqResource);
        $headers['get'] = trim($reqResource[1]);
      }
    }
    if (isset($headers['get'])) {
      $user->requestedResource = $headers['get'];
    }
    // todo: fail the connection
    else {
      $handshakeResponse = "HTTP/1.1 405 Method Not Allowed\r\n\r\n";
    }

    if (!isset($headers['host']) || !$this->checkHost($headers['host'])) {
      $handshakeResponse = 'HTTP/1.1 400 Bad Request';
    }

    if (!isset($headers['upgrade']) || strtolower($headers['upgrade']) != 'websocket') {
      $handshakeResponse = 'HTTP/1.1 400 Bad Request';
    }

    if (!isset($headers['connection']) || strpos(strtolower($headers['connection']), 'upgrade') === false) {
      $handshakeResponse = 'HTTP/1.1 400 Bad Request';
    }

    if (!isset($headers['sec-websocket-key'])) {
      $handshakeResponse = 'HTTP/1.1 400 Bad Request';
    }

    if (!isset($headers['sec-websocket-version']) || strtolower($headers['sec-websocket-version']) != 13) {
      $handshakeResponse = "HTTP/1.1 426 Upgrade Required\r\nSec-WebSocketVersion: 13";
    }

    if (($this->headerOriginRequired && !isset($headers['origin']) ) || ($this->headerOriginRequired && !$this->checkOrigin($headers['origin']))) {
      $handshakeResponse = 'HTTP/1.1 403 Forbidden';
    }

    if (($this->headerSecWebSocketProtocolRequired && !isset($headers['sec-websocket-protocol'])) || ($this->headerSecWebSocketProtocolRequired && !$this->checkWebsocProtocol($headers['sec-websocket-protocol']))) {
      $handshakeResponse = 'HTTP/1.1 400 Bad Request';
    }

    if (($this->headerSecWebSocketExtensionsRequired && !isset($headers['sec-websocket-extensions'])) || ($this->headerSecWebSocketExtensionsRequired && !$this->checkWebsocExtensions($headers['sec-websocket-extensions']))) {
      $handshakeResponse = 'HTTP/1.1 400 Bad Request';
    }

    // Done verifying the _required_ headers and optionally required headers.
    if (isset($handshakeResponse)) {
      socket_write($user->socket, $handshakeResponse, strlen($handshakeResponse));
      $this->disconnect($user->socket);
    }
    else {
      $user->headers = $headers;
      $user->handshake = $buffer;

      $webSocketKeyHash = sha1($headers['sec-websocket-key'] . $magicGUID);

      $rawToken = "";
      for ($i = 0; $i < 20; $i++) {
        $rawToken .= chr(hexdec(substr($webSocketKeyHash, $i*2, 2)));
      }
      $handshakeToken = base64_encode($rawToken) . "\r\n";

      $subProtocol = (isset($headers['sec-websocket-protocol'])) ? $this->processProtocol($headers['sec-websocket-protocol']) : "";
      $extensions = (isset($headers['sec-websocket-extensions'])) ? $this->processExtensions($headers['sec-websocket-extensions']) : "";

      $handshakeResponse = "HTTP/1.1 101 Switching Protocols\r\nUpgrade: websocket\r\nConnection: Upgrade\r\nSec-WebSocket-Accept: $handshakeToken$subProtocol$extensions\r\n";
      socket_write($user->socket, $handshakeResponse, strlen($handshakeResponse));
      $this->connected($user);
    }
  }

  // Override and return false if the host is not one that you would expect.
  // Ex: You only want to accept hosts from the my-domain.com domain,
  // but you receive a host from malicious-site.com instead.
  protected function checkHost($hostName): bool {
    return true;
  }

  // Override and return false if the origin is not one that you would expect.
  protected function checkOrigin($origin): bool {
    return true;
  }

  // Override and return false if a protocol is not found that you would expect.
  protected function checkWebsocProtocol($protocol): bool {
    return true;
  }

  // Override and return false if an extension is not found that you would expect.
  protected function checkWebsocExtensions($extensions): bool {
    return true;
  }

  // return either "Sec-WebSocket-Protocol: SelectedProtocolFromClientList\r\n" or return an empty string.
  // The carriage return/newline combo must appear at the end of a non-empty string, and must not
  // appear at the beginning of the string nor in an otherwise empty string, or it will be considered part of
  // the response body, which will trigger an error in the client as it will not be formatted correctly.
  protected function processProtocol($protocol): string {
    return '';
  }

  // return either "Sec-WebSocket-Extensions: SelectedExtensions\r\n" or return an empty string.
  protected function processExtensions($extensions): string {
    return '';
  }

  protected function getUserBySocket($socket): ?WebSocketUser {
    foreach ($this->users as $user) {
      if ($user->socket == $socket) {
        return $user;
      }
    }
    return null;
  }

  public function stdout(string $message): void {
    if ($this->interactive) {
      echo "$message\n";
    }
  }

  public function stderr(string $message): void {
    if ($this->interactive) {
      echo "$message\n";
    }
  }

  protected function frame(string $message, WebSocketUser $user, string $messageType='text', bool $messageContinues=false): string {
    switch ($messageType) {
      case 'continuous':
        $b1 = 0;
        break;
      case 'text':
        $b1 = ($user->sendingContinuous) ? 0 : 1;
        break;
      case 'binary':
        $b1 = ($user->sendingContinuous) ? 0 : 2;
        break;
      case 'close':
        $b1 = 8;
        break;
      case 'ping':
        $b1 = 9;
        break;
      case 'pong':
        $b1 = 10;
        break;
    }
    if ($messageContinues) {
      $user->sendingContinuous = true;
    }
    else {
      $b1 += 128;
      $user->sendingContinuous = false;
    }

    $length = strlen($message);
    $lengthField = '';
    if ($length < 126) {
      $b2 = $length;
    }
    elseif ($length < 65536) {
      $b2 = 126;
      $hexLength = dechex($length);
      //$this->stdout("Hex Length: $hexLength");
      if (strlen($hexLength) % 2 == 1) {
        $hexLength = '0' . $hexLength;
      }

      $n = strlen($hexLength) - 2;

      for ($i = $n; $i >= 0; $i = $i - 2) {
        $lengthField = chr(hexdec(substr($hexLength, $i, 2))) . $lengthField;
      }
      while (strlen($lengthField) < 2) {
        $lengthField = chr(0) . $lengthField;
      }
    }
    else {
      $b2 = 127;
      $hexLength = dechex($length);
      if (strlen($hexLength) % 2 == 1) {
        $hexLength = '0' . $hexLength;
      }

      $n = strlen($hexLength) - 2;

      for ($i = $n; $i >= 0; $i = $i - 2) {
        $lengthField = chr(hexdec(substr($hexLength, $i, 2))) . $lengthField;
      }
      while (strlen($lengthField) < 8) {
        $lengthField = chr(0) . $lengthField;
      }
    }

    return chr($b1) . chr($b2) . $lengthField . $message;
  }

  //check packet if he have more than one frame and process each frame individually
  protected function split_packet(int $length, $packet, WebSocketUser $user): void {
    //add PartialPacket and calculate the new $length
    if ($user->handlingPartialPacket) {
      $packet = $user->partialBuffer . $packet;
      $user->handlingPartialPacket = false;
      $length = strlen($packet);
    }
    $fullpacket = $packet;
    $frame_pos = 0;
    $frame_id = 1;

    while ($frame_pos<$length) {
      $headers = $this->extractHeaders($packet);
      $headers_size = $this->calcoffset($headers);
      $framesize = $headers['length'] + $headers_size;

      //split frame from packet and process it
      $frame = substr($fullpacket, $frame_pos, $framesize);

      if (($message = $this->deframe($frame, $user, $headers)) !== false) {
        if ($user->hasSentClose) {
          $this->disconnect($user->socket);
        }
        else {
          if ((preg_match('//u', $message)) || ($headers['opcode']==2)) {
            //$this->stdout("Text msg encoded UTF-8 or Binary msg\n".$message);
            $this->process($user, $message);
          }
          else {
            $this->stderr("not UTF-8\n");
          }
        }
      }
      //get the new position also modify packet data
      $frame_pos += $framesize;
      $packet = substr($fullpacket, $frame_pos);
      $frame_id++;
    }
  }

  protected function calcoffset(array $headers): int {
    $offset = 2;
    if ($headers['hasmask']) {
      $offset += 4;
    }

    if ($headers['length'] > 65535) {
      $offset += 8;
    }
    elseif ($headers['length'] > 125) {
      $offset += 2;
    }

    return $offset;
  }

  protected function deframe(string $message, WebSocketUser &$user): string|bool {
    //echo $this->strtohex($message);
    $headers = $this->extractHeaders($message);
    $pongReply = false;
    $willClose = false;
    switch($headers['opcode']) {
      case 0:
      case 1:
      case 2:
        break;
      case 8:
        // todo: close the connection
        $user->hasSentClose = true;
        return '';
      case 9:
        $pongReply = true;
      case 10:
        break;
      default:
        //$this->disconnect($user); // todo: fail connection
        $willClose = true;
    }

    /* Deal by split_packet() as now deframe() do only one frame at a time.
    if ($user->handlingPartialPacket) {
      $message = $user->partialBuffer . $message;
      $user->handlingPartialPacket = false;
      return $this->deframe($message, $user);
    }
    */

    if ($this->checkRSVBits($headers, $user)) {
      return false;
    }

    if ($willClose) {
      // todo: fail the connection
      return false;
    }

    $payload = $user->partialMessage . $this->extractPayload($message, $headers);

    if ($pongReply) {
      $reply = $this->frame($payload, $user, 'pong');
      socket_write($user->socket, $reply, strlen($reply));
      return false;
    }
    if ($headers['length'] > strlen($this->applyMask($headers, $payload))) {
      $user->handlingPartialPacket = true;
      $user->partialBuffer = $message;
      return false;
    }

    $payload = $this->applyMask($headers, $payload);

    if ($headers['fin']) {
      $user->partialMessage = '';
      return $payload;
    }
    $user->partialMessage = $payload;
    return false;
  }

  protected function extractHeaders($message): array {
    $header = [
      'fin'     => $message[0] & chr(128),
      'rsv1'    => $message[0] & chr(64),
      'rsv2'    => $message[0] & chr(32),
      'rsv3'    => $message[0] & chr(16),
      'opcode'  => ord($message[0]) & 15,
      'hasmask' => $message[1] & chr(128),
      'length'  => 0,
      'mask'    => ''
    ];
    $header['length'] = (ord($message[1]) >= 128) ? ord($message[1]) - 128 : ord($message[1]);

    if ($header['length'] == 126) {
      if ($header['hasmask']) {
        $header['mask'] = $message[4] . $message[5] . $message[6] . $message[7];
      }

      $header['length'] = ord($message[2]) * 256 + ord($message[3]);
    }
    elseif ($header['length'] == 127) {
      if ($header['hasmask']) {
        $header['mask'] = $message[10] . $message[11] . $message[12] . $message[13];
      }

      $header['length'] = ord($message[2]) * 65536 * 65536 * 65536 * 256
				+ ord($message[3]) * 65536 * 65536 * 65536
				+ ord($message[4]) * 65536 * 65536 * 256
				+ ord($message[5]) * 65536 * 65536
				+ ord($message[6]) * 65536 * 256
				+ ord($message[7]) * 65536
				+ ord($message[8]) * 256
				+ ord($message[9]);
    }
    elseif ($header['hasmask']) {
      $header['mask'] = $message[2] . $message[3] . $message[4] . $message[5];
    }

    //echo $this->strtohex($message);
    //$this->printHeaders($header);
    return $header;
  }

  protected function extractPayload(string $message, array $headers): string {
    $offset = 2;
    if ($headers['hasmask']) {
      $offset += 4;
    }

    if ($headers['length'] > 65535) {
      $offset += 8;
    }
    elseif ($headers['length'] > 125) {
      $offset += 2;
    }

    return substr($message, $offset);
  }

  protected function applyMask(array $headers, $payload) {
    $effectiveMask = '';
    if ($headers['hasmask']) {
      $mask = $headers['mask'];
    }
    else {
      return $payload;
    }

    while (strlen($effectiveMask) < strlen($payload)) {
      $effectiveMask .= $mask;
    }
    while (strlen($effectiveMask) > strlen($payload)) {
      $effectiveMask = substr($effectiveMask,0,-1);
    }
    return $effectiveMask ^ $payload;
  }

  // override this method if you are using an extension where the RSV bits are used.
  protected function checkRSVBits(array $headers, WebSocketUser $user): bool {
    if (ord($headers['rsv1']) + ord($headers['rsv2']) + ord($headers['rsv3']) > 0) {
      //$this->disconnect($user); // todo: fail connection
      return true;
    }
    return false;
  }

  protected function strtohex(string $str): string {
    $strout = '';
    for($i = 0; $i < strlen($str); $i++) {
      $strout .= (ord($str[$i])<16) ? '0' . dechex(ord($str[$i])) : dechex(ord($str[$i]));
      $strout .= " ";
      if ($i % 32 == 7) {
        $strout .= ": ";
      }
      if ($i % 32 == 15) {
        $strout .= ': ';
      }
      if ($i % 32 == 23) {
        $strout .= ': ';
      }
      if ($i % 32 == 31) {
        $strout .= "\n";
      }
    }
    return $strout . "\n";
  }

  protected function printHeaders(array $headers): void {
    echo "Array\n(\n";
    foreach ($headers as $key => $value) {
      if ($key == 'length' || $key == 'opcode') {
        echo "\t[$key] => $value\n\n";
      }
      else {
        echo "\t[$key] => ".$this->strtohex($value)."\n";
      }
    }
    echo ")\n";
  }
}
