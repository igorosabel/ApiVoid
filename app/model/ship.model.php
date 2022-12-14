<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;

class Ship extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id único para cada nave'
			),
			new OModelField(
				name: 'id_player',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				ref: 'player.id',
				comment: 'Id del jugador dueño de la nave o null si la vende un NPC'
			),
			new OModelField(
				name: 'id_npc',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				ref: 'npc.id',
				comment: 'Id del NPC que vende la nave o null si es de un jugador'
			),
			new OModelField(
				name: 'original_name',
				type: OMODEL_TEXT,
				nullable: false,
				default: 'null',
				size: 50,
				comment: 'Nombre original de la nave'
			),
			new OModelField(
				name: 'name',
				type: OMODEL_TEXT,
				nullable: false,
				default: 'null',
				size: 50,
				comment: 'Nombre actual de la nave'
			),
			new OModelField(
				name: 'id_type',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Tipo de nave'
			),
			new OModelField(
				name: 'max_strength',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Puntos máximos de integridad de la nave'
			),
			new OModelField(
				name: 'strength',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Puntos de integridad actuales de la nave'
			),
			new OModelField(
				name: 'endurance',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Puntos de resistencia'
			),
			new OModelField(
				name: 'shield',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				comment: 'Puntos de escudo'
			),
			new OModelField(
				name: 'id_engine',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Tipo de motor que lleva la nave'
			),
			new OModelField(
				name: 'speed',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Velocidad base de la nave'
			),
			new OModelField(
				name: 'max_cargo',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Capacidad total de carga de la nave'
			),
			new OModelField(
				name: 'cargo',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Capacidad inicial de carga de la nave'
			),
			new OModelField(
				name: 'damage',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				comment: 'Daño total que puede hacer la nave'
			),
			new OModelField(
				name: 'id_generator',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Tipo de generador de energía'
			),
			new OModelField(
				name: 'max_energy',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Puntos de energía totales de la nave'
			),
			new OModelField(
				name: 'energy',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Puntos de energía iniciales de la nave'
			),
			new OModelField(
				name: 'slots',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Número de huecos disponibles para módulos'
			),
			new OModelField(
				name: 'crew',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				comment: 'Número de tripulantes que se pueden llevar en la nave'
			),
			new OModelField(
				name: 'credits',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				comment: 'Cantidad de créditos que cuesta la nave'
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
