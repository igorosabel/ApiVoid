<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\Routes;

use Osumi\OsumiFramework\Routing\ORoute;
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
use Osumi\OsumiFramework\App\Filter\LoginFilter;

ORoute::group('/api', 'json', function() {
  ORoute::post('/buy',             BuyAction::class,           [LoginFilter::class]);
  ORoute::post('/current-system',  CurrentSystemAction::class, [LoginFilter::class]);
  ORoute::post('/edit-name',       EditNameAction::class,      [LoginFilter::class]);
  ORoute::post('/explore',         ExploreAction::class,       [LoginFilter::class]);
  ORoute::post('/get-sell-items',  GetSellItemsAction::class,  [LoginFilter::class]);
  ORoute::post('/get-system-info', GetSystemInfoAction::class, [LoginFilter::class]);
  ORoute::post('login',            CopyLevelAction::class);
  ORoute::post('/npc-shop',        NPCShopAction::class,       [LoginFilter::class]);
  ORoute::post('/register',        RegisterAction::class,      [LoginFilter::class]);
  ORoute::post('/sell',            SellAction::class,          [LoginFilter::class]);
});
