<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\Routes;

use Osumi\OsumiFramework\Routing\ORoute;
use Osumi\OsumiFramework\App\Module\Auth\CheckEmailAvailable\CheckEmailAvailableComponent;
use Osumi\OsumiFramework\App\Module\Auth\Login\LoginComponent;
use Osumi\OsumiFramework\App\Module\Auth\Logout\LogoutComponent;
use Osumi\OsumiFramework\App\Module\Auth\Refresh\RefreshComponent;
use Osumi\OsumiFramework\App\Module\Auth\Register\RegisterComponent;

ORoute::prefix('/auth', function() {
  ORoute::post('/check-email-available', CheckEmailAvailableComponent::class);
  ORoute::post('/login',                 LoginComponent::class);
  ORoute::post('/logout',                LogoutComponent::class);
  ORoute::post('/refresh',               RefreshComponent::class);
  ORoute::post('/register',              RegisterComponent::class);
});
