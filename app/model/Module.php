<?php declare(strict_types=1);
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
				'type'    => OCore::PK,
				'comment' => 'Id único de cada módulo'
			],
			'id_player' => [
				'type'    => OCore::NUM,
				'nullable' => true,
				'default' => null,
				'ref' => 'player.id',
				'comment' => 'Id del jugador dueño del módulo o null si lo vende un NPC'
			],
			'id_npc' => [
				'type'    => OCore::NUM,
				'nullable' => true,
				'default' => null,
				'ref' => 'npc.id',
				'comment' => 'Id del NPC que vende el módulo o null si es de un jugador'
			],
			'id_ship' => [
				'type'    => OCore::NUM,
				'nullable' => true,
				'default' => null,
				'ref' => 'ship.id',
				'comment' => 'Id de la nave en la que está equipado o null si no está equipado'
			],
			'name' => [
				'type'    => OCore::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 100,
				'comment' => 'Nombre descriptivo del módulo'
			],
			'id_type' => [
				'type'    => OCore::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Tipo de módulo'
			],
			'engine' => [
				'type'    => OCore::FLOAT,
				'nullable' => true,
				'default' => null,
				'comment' => 'Multiplicador de velocidad en caso de ser un módulo de motor'
			],
			'shield' => [
				'type'    => OCore::NUM,
				'nullable' => true,
				'default' => null,
				'comment' => 'Puntos de escudo que aumenta el módulo en caso de ser un módulo de escudo'
			],
			'cargo' => [
				'type'    => OCore::NUM,
				'nullable' => true,
				'default' => null,
				'comment' => 'Capacidad de carga en caso de ser un módulo de carga'
			],
			'damage' => [
				'type'    => OCore::NUM,
				'nullable' => true,
				'default' => null,
				'comment' => 'Puntos de daño en caso de ser un módulo de arma'
			],
			'crew' => [
				'type'    => OCore::NUM,
				'nullable' => true,
				'default' => null,
				'comment' => 'Cantidad de tripulantes en caso de ser un módulo de cabinas'
			],
			'energy' => [
				'type'    => OCore::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Puntos de energía que consume el módulo o produce en caso de ser un módulo de energía'
			],
			'slots' => [
				'type'    => OCore::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Número de huecos que ocupa el módulo en la nave'
			],
			'credits' => [
				'type'    => OCore::NUM,
				'nullable' => true,
				'default' => null,
				'comment' => 'Cantidad de créditos que cuesta el módulo'
			],
			'created_at' => [
				'type'    => OCore::CREATED,
				'comment' => 'Fecha de creación del registro'
			],
			'updated_at' => [
				'type'    => OCore::UPDATED,
				'nullable' => true,
				'default' => null,
				'comment' => 'Fecha de última modificación del registro'
			]
		];

		parent::load($table_name, $model);
	}
}