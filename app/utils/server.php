<?php declare(strict_types=1);

namespace OsumiFramework\App\Utils;

use OsumiFramework\OFW\Plugins\OWebSocket;

class Server extends OWebSocket {
  function __construct() {
    parent::__construct();
  }

  protected function process ($user, $message) {
    echo "USER: \n";
    var_dump($user);
    echo "\nMESSAGE: \n";
    var_dump($message);
  }

  protected function connected ($user) {
    echo "USER CONNECTED: \n";
    var_dump($user);
  }

  protected function closed ($user) {
    echo "USER DISCONNECTED: \n";
    var_dump($user);
  }
}
