<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class ShipResource extends OModel {
	function __construct() {
		$model = [
			'id_ship' => [
				'type'    => OModel::PK,
				'incr' => false,
				'ref' => 'ship.id',
				'comment' => 'Id de la nave que almacena los recursos'
			],
			'type' => [
				'type'    => OModel::PK,
				'incr' => false,
				'comment' => 'Tipo de recurso'
			],
			'value' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Cantidad del recurso almacenado'
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

		parent::load($model);
	}
}