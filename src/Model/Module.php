<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\DB\OModel;
use Osumi\OsumiFramework\DB\OModelGroup;
use Osumi\OsumiFramework\DB\OModelField;

class Module extends OModel {
	const ENGINE = 1;
	const SHIELD = 2;
	const CARGO = 3;
	const GUN = 4;
	const CABIN = 5;
	const ENERGY = 6;

	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id único de cada módulo'
			),
			new OModelField(
				name: 'id_player',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				ref: 'player.id',
				comment: 'Id del jugador dueño del módulo o null si lo vende un NPC'
			),
			new OModelField(
				name: 'id_npc',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				ref: 'npc.id',
				comment: 'Id del NPC que vende el módulo o null si es de un jugador'
			),
			new OModelField(
				name: 'id_ship',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				ref: 'ship.id',
				comment: 'Id de la nave en la que está equipado o null si no está equipado'
			),
			new OModelField(
				name: 'name',
				type: OMODEL_TEXT,
				nullable: false,
				default: 'null',
				size: 100,
				comment: 'Nombre descriptivo del módulo'
			),
			new OModelField(
				name: 'id_type',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Tipo de módulo'
			),
			new OModelField(
				name: 'engine',
				type: OMODEL_FLOAT,
				nullable: true,
				default: null,
				comment: 'Multiplicador de velocidad en caso de ser un módulo de motor'
			),
			new OModelField(
				name: 'shield',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				comment: 'Puntos de escudo que aumenta el módulo en caso de ser un módulo de escudo'
			),
			new OModelField(
				name: 'cargo',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				comment: 'Capacidad de carga en caso de ser un módulo de carga'
			),
			new OModelField(
				name: 'damage',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				comment: 'Puntos de daño en caso de ser un módulo de arma'
			),
			new OModelField(
				name: 'crew',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				comment: 'Cantidad de tripulantes en caso de ser un módulo de cabinas'
			),
			new OModelField(
				name: 'energy',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Puntos de energía que consume el módulo o produce en caso de ser un módulo de energía'
			),
			new OModelField(
				name: 'slots',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Número de huecos que ocupa el módulo en la nave'
			),
			new OModelField(
				name: 'credits',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				comment: 'Cantidad de créditos que cuesta el módulo'
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
