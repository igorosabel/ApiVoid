<?php declare(strict_types=1);
class Construction extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name  = 'construction';
		$model = [
			'id' => [
				'type'    => OCore::PK,
				'comment' => 'Id único para cada construcción'
			],
			'id_player' => [
				'type'    => OCore::NUM,
				'nullable' => false,
				'default' => null,
				'ref' => 'player.id',
				'comment' => 'Id del jugador que hace la construcción'
			],
			'commerce' => [
				'type'    => OCore::BOOL,
				'comment' => 'Indica si tiene puesto de comercio 1 o no 0'
			],
			'repair' => [
				'type'    => OCore::BOOL,
				'comment' => 'Indica si tiene taller de reparaciones 1 o no 0'
			],
			'workshop' => [
				'type'    => OCore::BOOL,
				'comment' => 'Indica si tiene taller de construcciones 1 o no 0'
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