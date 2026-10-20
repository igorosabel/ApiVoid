<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\Routes;

use Osumi\OsumiFramework\Routing\ORoute;
use Osumi\OsumiFramework\App\Module\Game\Home\HomeComponent;
use Osumi\OsumiFramework\App\Filter\LoginFilter;

ORoute::prefix('/game', function() {
  ORoute::get('/home', HomeComponent::class, [LoginFilter::class]);
});
