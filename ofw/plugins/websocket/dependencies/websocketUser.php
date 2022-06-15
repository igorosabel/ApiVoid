<?php
namespace OsumiFramework\OFW\Plugins;

class WebSocketUser {
  public $socket;
  public string $id;
  public array $headers = [];
  public bool $handshake = false;

  public bool $handlingPartialPacket = false;
  public string $partialBuffer = "";

  public bool $sendingContinuous = false;
  public string $partialMessage = "";

  public bool $hasSentClose = false;

  function __construct(string $id, $socket) {
    $this->id = $id;
    $this->socket = $socket;
  }
}
