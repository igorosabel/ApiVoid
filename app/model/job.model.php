<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;

class Job extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id única para cada tarea'
			),
			new OModelField(
				name: 'id_player',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				ref: 'player.id',
				comment: 'Id del jugador que hace la tarea'
			),
			new OModelField(
				name: 'type',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Tipo de tarea'
			),
			new OModelField(
				name: 'value',
				type: OMODEL_LONGTEXT,
				nullable: true,
				default: 'null',
				comment: 'Información extra de la tarea'
			),
			new OModelField(
				name: 'start',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Timestamp de inicio de la tarea'
			),
			new OModelField(
				name: 'end',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Timestamp de fin de la tarea'
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
