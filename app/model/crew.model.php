<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;

class Crew extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id único de cada tripulante'
			),
			new OModelField(
				name: 'id_player',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				ref: 'player.id',
				comment: 'Id del jugador que contrata al tripulante'
			),
			new OModelField(
				name: 'name',
				type: OMODEL_TEXT,
				nullable: false,
				default: 'null',
				size: 50,
				comment: 'Nombre del tripulante'
			),
			new OModelField(
				name: 'id_race',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Id de la raza del tripulante'
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
