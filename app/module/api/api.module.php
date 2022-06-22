<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Routing\OModule;

#[OModule(
	actions: ['register', 'login', 'currentSystem', 'NPCShop', 'buy', 'getSellItems', 'sell', 'getSystemInfo', 'editName', 'explore'],
	type: 'json',
	prefix: '/api'
)]
class apiModule {}
