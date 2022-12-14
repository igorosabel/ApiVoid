<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;

class Construction extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id único para cada construcción'
			),
			new OModelField(
				name: 'id_player',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				ref: 'player.id',
				comment: 'Id del jugador que hace la construcción'
			),
			new OModelField(
				name: 'commerce',
				type: OMODEL_BOOL,
				comment: 'Indica si tiene puesto de comercio 1 o no 0'
			),
			new OModelField(
				name: 'repair',
				type: OMODEL_BOOL,
				comment: 'Indica si tiene taller de reparaciones 1 o no 0'
			),
			new OModelField(
				name: 'workshop',
				type: OMODEL_BOOL,
				comment: 'Indica si tiene taller de construcciones 1 o no 0'
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
