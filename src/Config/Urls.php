<?php declare(strict_types=1);

use Osumi\OsumiFramework\Routing\OUrl;
use Osumi\OsumiFramework\App\Module\Api\Buy\BuyAction;
use Osumi\OsumiFramework\App\Module\Api\CurrentSystem\CurrentSystemAction;
use Osumi\OsumiFramework\App\Module\Api\EditName\EditNameAction;
use Osumi\OsumiFramework\App\Module\Api\Explore\ExploreAction;
use Osumi\OsumiFramework\App\Module\Api\GetSellItems\GetSellItemsAction;
use Osumi\OsumiFramework\App\Module\Api\GetSystemInfo\GetSystemInfoAction;
use Osumi\OsumiFramework\App\Module\Api\Login\LoginAction;
use Osumi\OsumiFramework\App\Module\Api\NPCShop\NPCShopAction;
use Osumi\OsumiFramework\App\Module\Api\Register\RegisterAction;
use Osumi\OsumiFramework\App\Module\Api\Sell\SellAction;
use Osumi\OsumiFramework\App\Module\Home\Closed\ClosedAction;
use Osumi\OsumiFramework\App\Module\Home\Index\IndexAction;
use Osumi\OsumiFramework\App\Module\Home\NotFound\NotFoundAction;

use Osumi\OsumiFramework\App\Filter\LoginFilter;
use Osumi\OsumiFramework\App\Service\JobService;
use Osumi\OsumiFramework\App\Service\MessageService;
use Osumi\OsumiFramework\App\Service\ModuleService;
use Osumi\OsumiFramework\App\Service\NpcService;
use Osumi\OsumiFramework\App\Service\ResourceService;
use Osumi\OsumiFramework\App\Service\ShipService;
use Osumi\OsumiFramework\App\Service\SystemService;

$api_urls = [
  [
    'url' => '/buy',
    'action' => BuyAction::class,
    'filters' => [LoginFilter::class],
    'type' => 'json'
  ],
  [
    'url' => '/current-system',
    'action' => CurrentSystemAction::class,
    'filters' => [LoginFilter::class],
  	'services' => [
      MessageService::class,
      SystemService::class
    ],
    'type' => 'json'
  ],
  [
    'url' => '/edit-name',
    'action' => EditNameAction::class,
  	'filters' => [LoginFilter::class],
    'type' => 'json'
  ],
  [
    'url' => '/explore',
    'action' => ExploreAction::class,
    'filters' => [LoginFilter::class],
    'type' => 'json'
  ],
  [
    'url' => '/get-sell-items',
    'action' => GetUserAction::class,
    'filters' => [LoginFilter::class],
  	'services' => [
      ShipService::class,
      ModuleService::class,
      ResourceService::class
    ],
    'type' => 'json'
  ],
  [
    'url' => '/get-system-info',
    'action' => GetSystemInfoAction::class,
    'filters' => [LoginFilter::class],
    'type' => 'json'
  ],
  [
    'url' => '/login',
    'action' => LoginAction::class,
    'type' => 'json'
  ],
  [
    'url' => '/npc-shop',
    'action' => NPCShopAction::class,
    'filters' => [LoginFilter::class],
    'type' => 'json'
  ],
  [
    'url' => '/register',
    'action' => RegisterAction::class,
  	'services' => [
      ShipService::class,
      SystemService::class
    ],
    'type' => 'json'
  ],
  [
    'url' => '/sell',
    'action' => SellAction::class,
    'filters' => [LoginFilter::class],
    'type' => 'json'
  ],
];

$home_urls = [
  [
    'url' => '/closed',
    'action' => ClosedAction::class
  ],
  [
    'url' => '/',
    'action' => IndexAction::class
  ],
  [
    'url' => '/not-found',
    'action' => NotFoundAction::class
  ]
];

$urls = [];
OUrl::addUrls($urls, $api_urls, '/api');
OUrl::addUrls($urls, $home_urls);

return $urls;
