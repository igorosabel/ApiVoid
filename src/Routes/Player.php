<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\Routes;

use Osumi\OsumiFramework\Routing\ORoute;
use Osumi\OsumiFramework\App\Module\Player\CheckEmailAvailable\CheckEmailAvailableComponent;
use Osumi\OsumiFramework\App\Module\Player\Register\RegisterComponent;

ORoute::prefix('/player', function() {
  ORoute::post('/check-email-available', CheckEmailAvailableComponent::class);
  ORoute::post('/register', RegisterComponent::class);
});
