<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\Routes;

use Osumi\OsumiFramework\Routing\ORoute;
use Osumi\OsumiFramework\App\Middleware\LoginMiddleware;
use Osumi\OsumiFramework\App\Module\Game\Home\HomeComponent;

ORoute::prefix('/game', function() {
  ORoute::get('/home', HomeComponent::class, ['before' => [LoginMiddleware::class]]);
});
