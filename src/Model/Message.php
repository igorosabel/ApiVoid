<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\DB\OModel;
use Osumi\OsumiFramework\DB\OModelGroup;
use Osumi\OsumiFramework\DB\OModelField;
use Osumi\OsumiFramework\App\Model\Player;

class Message extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id único de cada mensaje'
			),
			new OModelField(
				name: 'id_player_from',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				ref: 'player.id',
				comment: 'Id del jugador que envía un mensaje'
			),
			new OModelField(
				name: 'id_player_to',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				ref: 'player.id',
				comment: 'Id del jugador al que se le envía un mensaje'
			),
			new OModelField(
				name: 'message',
				type: OMODEL_TEXT,
				nullable: false,
				default: 'null',
				size: 250,
				comment: 'Contenido del mensaje'
			),
			new OModelField(
				name: 'type',
				type: OMODEL_NUM,
				nullable: false,
				default: 0,
				comment: 'Tipo de mensaje: normal 0 solicitud 1'
			),
			new OModelField(
				name: 'req_id_resource',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				comment: 'Tipo de recurso que se solicita'
			),
			new OModelField(
				name: 'req_value',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				comment: 'Cantidad del recurso que se solicita'
			),
			new OModelField(
				name: 'req_credits',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				comment: 'Cantidad de créditos que se solicitan'
			),
			new OModelField(
				name: 'offer_id_resource',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				comment: 'Tipo de recurso que se ofrece'
			),
			new OModelField(
				name: 'offer_value',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				comment: 'Cantidad del recurso que se ofrece'
			),
			new OModelField(
				name: 'offer_credits',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				comment: 'Cantidad de créditos que se ofrecen'
			),
			new OModelField(
				name: 'req_status',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				comment: 'Estado de la solicitud 0 sin aceptar, 1 aceptada, null no hay solicitud'
			),
			new OModelField(
				name: 'is_read',
				type: OMODEL_BOOL,
				nullable: false,
				default: false,
				comment: 'Mensaje no leído 0 o leído 1'
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

	private ?Player $to = null;

	/**
	 * Obtiene el destinatario del mensaje
	 *
	 * @return Player Destinatario del mensaje
	 */
	public function getTo(): Player {
		if (is_null($this->to)) {
			$this->loadTo();
		}
		return $this->to;
	}

	/**
	 * Guarda el destinatario del mensaje
	 *
	 * @param Player $to Destinatario del mensaje
	 *
	 * @return void
	 */
	public function setTo(Player $to): void {
		$this->to = $to;
	}

	/**
	 * Carga el destinatario del mensaje
	 *
	 * @return void
	 */
	public function loadTo(): void {
		$to = new Player();
		$to->find(['id'=>$this->get('id_player_to')]);
		$this->setTo($to);
	}

	private ?Player $from = null;

	/**
	 * Obtiene el remitente del mensaje
	 *
	 * @return Player Remitente del mensaje
	 */
	public function getFrom(): Player {
		if (is_null($this->from)) {
			$this->loadFrom();
		}
		return $this->from;
	}

	/**
	 * Guarda el remitente del mensaje
	 *
	 * @param Player $from Remitente del mensaje
	 *
	 * @return void
	 */
	public function setFrom(Player $from): void {
		$this->from = $from;
	}

	/**
	 * Carga el remitente del mensaje
	 *
	 * @return void
	 */
	public function loadFrom(): void {
		$from = new Player();
		$from->find(['id'=>$this->get('id_player_from')]);
		$this->setFrom($from);
	}
}
