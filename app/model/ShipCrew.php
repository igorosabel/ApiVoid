<?php declare(strict_types=1);
class ShipCrew extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name  = 'ship_crew';
		$model = [
			'id_ship' => [
				'type'    => OCore::PK,
				'incr' => false,
				'ref' => 'ship.id',
				'comment' => 'Id de la nave'
			],
			'id_crew' => [
				'type'    => OCore::PK,
				'incr' => false,
				'ref' => 'crew.id',
				'comment' => 'Id del tripulante'
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