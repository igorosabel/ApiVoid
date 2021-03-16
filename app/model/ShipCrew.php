<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class ShipCrew extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name  = 'ship_crew';
		$model = [
			'id_ship' => [
				'type'    => OModel::PK,
				'incr' => false,
				'ref' => 'ship.id',
				'comment' => 'Id de la nave'
			],
			'id_crew' => [
				'type'    => OModel::PK,
				'incr' => false,
				'ref' => 'crew.id',
				'comment' => 'Id del tripulante'
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