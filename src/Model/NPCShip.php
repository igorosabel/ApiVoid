<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\DB\OModel;
use Osumi\OsumiFramework\DB\OModelGroup;
use Osumi\OsumiFramework\DB\OModelField;
use Osumi\OsumiFramework\App\Model\Ship;

class NPCShip extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id_npc',
				type: OMODEL_PK,
				incr: false,
				ref: 'npc.id',
				comment: 'Id del NPC que hace la venta'
			),
			new OModelField(
				name: 'id_ship',
				type: OMODEL_PK,
				incr: false,
				ref: 'ship.id',
				comment: 'Id de la nave que vende'
			),
			new OModelField(
				name: 'start_value',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Cantidad inicial de naves que vende'
			),
			new OModelField(
				name: 'value',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Cantidad de naves que le quedan disponibles'
			),
			new OModelField(
				name: 'created_at',
				type: OMODEL_CREATED,
				comment: 'Fecha de creación del registro'
			),
			new OModelField(
				name: 'updated_at',
				type: OMODEL_UPDATED,
				nullable: true,
				default: null,
				comment: 'Fecha de última modificación del registro'
			)
		);

		parent::load($model);
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
