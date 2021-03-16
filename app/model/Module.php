<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class Module extends OModel {
	const ENGINE = 1;
	const SHIELD = 2;
	const CARGO = 3;
	const GUN = 4;
	const CABIN = 5;
	const ENERGY = 6;

	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name  = 'module';
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único de cada módulo'
			],
			'id_player' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => null,
				'ref' => 'player.id',
				'comment' => 'Id del jugador dueño del módulo o null si lo vende un NPC'
			],
			'id_npc' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => null,
				'ref' => 'npc.id',
				'comment' => 'Id del NPC que vende el módulo o null si es de un jugador'
			],
			'id_ship' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => null,
				'ref' => 'ship.id',
				'comment' => 'Id de la nave en la que está equipado o null si no está equipado'
			],
			'name' => [
				'type'    => OModel::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 100,
				'comment' => 'Nombre descriptivo del módulo'
			],
			'id_type' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Tipo de módulo'
			],
			'engine' => [
				'type'    => OModel::FLOAT,
				'nullable' => true,
				'default' => null,
				'comment' => 'Multiplicador de velocidad en caso de ser un módulo de motor'
			],
			'shield' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => null,
				'comment' => 'Puntos de escudo que aumenta el módulo en caso de ser un módulo de escudo'
			],
			'cargo' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => null,
				'comment' => 'Capacidad de carga en caso de ser un módulo de carga'
			],
			'damage' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => null,
				'comment' => 'Puntos de daño en caso de ser un módulo de arma'
			],
			'crew' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => null,
				'comment' => 'Cantidad de tripulantes en caso de ser un módulo de cabinas'
			],
			'energy' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Puntos de energía que consume el módulo o produce en caso de ser un módulo de energía'
			],
			'slots' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Número de huecos que ocupa el módulo en la nave'
			],
			'credits' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => null,
				'comment' => 'Cantidad de créditos que cuesta el módulo'
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
}