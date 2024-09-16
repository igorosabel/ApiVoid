<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\DB\OModel;
use Osumi\OsumiFramework\DB\OModelGroup;
use Osumi\OsumiFramework\DB\OModelField;

class ShipCrew extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id_ship',
				type: OMODEL_PK,
				incr: false,
				ref: 'ship.id',
				comment: 'Id de la nave'
			),
			new OModelField(
				name: 'id_crew',
				type: OMODEL_PK,
				incr: false,
				ref: 'crew.id',
				comment: 'Id del tripulante'
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
