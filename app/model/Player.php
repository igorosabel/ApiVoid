<?php declare(strict_types=1);
class Player extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name  = 'player';
		$model = [
			'id' => [
				'type'    => OCore::PK,
				'comment' => 'Id único del jugador'
			],
			'name' => [
				'type'    => OCore::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 50,
				'comment' => 'Nombre de usuario del jugador'
			],
			'email' => [
				'type'    => OCore::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 100,
				'comment' => 'Email del jugador'
			],
			'pass' => [
				'type'    => OCore::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 120,
				'comment' => 'Contraseña cifrada del jugador'
			],
			'credits' => [
				'type'    => OCore::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Cantidad de créditos que posee el jugador'
			],
			'id_ship' => [
				'type'    => OCore::NUM,
				'nullable' => false,
				'default' => null,
				'ref' => 'ship.id',
				'comment' => 'Id de la nave que actualmente pilota el jugador'
			],
			'id_system' => [
				'type'    => OCore::NUM,
				'nullable' => false,
				'default' => null,
				'ref' => 'system.id',
				'comment' => 'Id del sistema en el que se encuentra el jugador'
			],
			'id_job' => [
				'type'    => OCore::NUM,
				'nullable' => true,
				'default' => null,
				'ref' => 'job.id',
				'comment' => 'Id de la tarea que está desempeñando el jugador'
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