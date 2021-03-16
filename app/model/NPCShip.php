<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class NPCShip extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name  = 'npc_ship';
		$model = [
			'id_npc' => [
				'type'    => OModel::PK,
				'incr' => false,
				'ref' => 'npc.id',
				'comment' => 'Id del NPC que hace la venta'
			],
			'id_ship' => [
				'type'    => OModel::PK,
				'incr' => false,
				'ref' => 'ship.id',
				'comment' => 'Id de la nave que vende'
			],
			'start_value' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Cantidad inicial de naves que vende'
			],
			'value' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Cantidad de naves que le quedan disponibles'
			],
			'created_at' => [
				'type'    => OModel::CREATED,
				'comment' => 'Fecha de creación del registro'
			],
			'updated_at' => [
				'type'    => OModel::UPDATED,
				'nullable' => true,
				'default' => null,
				'comment' => 'Fecha de última modificación del registro'
			]
		];

		parent::load($table_name, $model);
	}

	private ?Ship $ship = null;

	/**
	 * Obtiene la nave del NPC
	 *
	 * @return Ship Nave del NPC
	 */
	public function getShip(): Ship {
		if (is_null($this->ship)) {
			$this->loadShip();
		}
		return $this->ship;
	}

	/**
	 * Guarda la nave del NPC
	 *
	 * @param Ship $ship Nave del NPC
	 *
	 * @return void
	 */
	public function setShip(Ship $ship): void {
		$this->ship = $ship;
	}

	/**
	 * Carga la nave del NPC
	 *
	 * @return void
	 */
	public function loadShip(): void {
		$sql = "SELECT * FROM `ship` WHERE `id` = ?";
		$this->db->query($sql, [$this->get('id_ship')]);
		$res = $this->db->next();

		$ship = new Ship();
		$ship->update($res);
		$credits = $ship->get('credits') * (1 + ($margin/100));
		$ship->set('credits', $credits);

		$this->setShip($ship);
	}
}