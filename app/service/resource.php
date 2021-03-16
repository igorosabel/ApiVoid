<?php declare(strict_types=1);

namespace OsumiFramework\App\Service;

use OsumiFramework\OFW\Core\OService;
use OsumiFramework\OFW\DB\ODB;
use OsumiFramework\OFW\Tools\OTools;
use OsumiFramework\App\Model\ShipResource;
use OsumiFramework\App\Model\Ship;

class resourceService extends OService {
	/**
	 * Load service tools
	 */
	function __construct() {
		$this->loadService();
	}

	/**
	 * Obtiene la lista de recursos que se pueden vender de la bodega de una nave
	 *
	 * @param Ship $ship Nave de la que obtener los recursos
	 *
	 * @param NPC $npc NPC al que se quiere vender los recursos
	 *
	 * @return array Lista de recursos
	 */
	public function getSellResources(Ship $ship, NPC $npc): array {
		$db = new ODB();
		$resources = OTools::getCache('resource');
		$sql = "SELECT * FROM `ship_resource` WHERE `id_ship` = ?";
		$db->query($sql, [$ship->get('id')]);
		$ret = [];

		while ($res = $db->next()) {
			$ship_resource = new ShipResource();
			$ship_resource->update($res);

			$key = array_search($ship_resource->get('type'), array_column($resources['resources'], 'id'));
			$resource = $resources['resources'][$key];
			$resource['credits'] = $resource['price'] * (1 + ($npc->get('margin')/100));
			$resource['value'] = $ship_resource->get('value');

			array_push($ret, $resource);
		}

		return $ret;
	}
}