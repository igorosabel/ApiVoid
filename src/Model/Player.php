<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\DB\OModel;
use Osumi\OsumiFramework\DB\OModelGroup;
use Osumi\OsumiFramework\DB\OModelField;

class Player extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id único del jugador'
			),
			new OModelField(
				name: 'name',
				type: OMODEL_TEXT,
				nullable: false,
				default: 'null',
				size: 50,
				comment: 'Nombre de usuario del jugador'
			),
			new OModelField(
				name: 'email',
				type: OMODEL_TEXT,
				nullable: false,
				default: 'null',
				size: 100,
				comment: 'Email del jugador'
			),
			new OModelField(
				name: 'pass',
				type: OMODEL_TEXT,
				nullable: false,
				default: 'null',
				size: 120,
				comment: 'Contraseña cifrada del jugador'
			),
			new OModelField(
				name: 'credits',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Cantidad de créditos que posee el jugador'
			),
			new OModelField(
				name: 'id_ship',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				ref: 'ship.id',
				comment: 'Id de la nave que actualmente pilota el jugador'
			),
			new OModelField(
				name: 'id_system',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				ref: 'system.id',
				comment: 'Id del sistema en el que se encuentra el jugador'
			),
			new OModelField(
				name: 'id_job',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				ref: 'job.id',
				comment: 'Id de la tarea que está desempeñando el jugador'
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
