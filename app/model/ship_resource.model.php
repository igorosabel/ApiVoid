<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;

class ShipResource extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id_ship',
				type: OMODEL_PK,
				incr: false,
				ref: 'ship.id',
				comment: 'Id de la nave que almacena los recursos'
			),
			new OModelField(
				name: 'type',
				type: OMODEL_PK,
				incr: false,
				comment: 'Tipo de recurso'
			),
			new OModelField(
				name: 'value',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Cantidad del recurso almacenado'
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
}
