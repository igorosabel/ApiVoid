<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\App\Model\Player;

class Message extends OModel {
	function __construct() {
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único de cada mensaje'
			],
			'id_player_from' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'ref' => 'player.id',
				'comment' => 'Id del jugador que envía un mensaje'
			],
			'id_player_to' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'ref' => 'player.id',
				'comment' => 'Id del jugador al que se le envía un mensaje'
			],
			'message' => [
				'type'    => OModel::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 250,
				'comment' => 'Contenido del mensaje'
			],
			'type' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => 0,
				'comment' => 'Tipo de mensaje: normal 0 solicitud 1'
			],
			'req_id_resource' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => null,
				'comment' => 'Tipo de recurso que se solicita'
			],
			'req_value' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => null,
				'comment' => 'Cantidad del recurso que se solicita'
			],
			'req_credits' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => null,
				'comment' => 'Cantidad de créditos que se solicitan'
			],
			'offer_id_resource' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => null,
				'comment' => 'Tipo de recurso que se ofrece'
			],
			'offer_value' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => null,
				'comment' => 'Cantidad del recurso que se ofrece'
			],
			'offer_credits' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => null,
				'comment' => 'Cantidad de créditos que se ofrecen'
			],
			'req_status' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => null,
				'comment' => 'Estado de la solicitud 0 sin aceptar, 1 aceptada, null no hay solicitud'
			],
			'is_read' => [
				'type'    => OModel::BOOL,
				'nullable' => false,
				'default' => false,
				'comment' => 'Mensaje no leído 0 o leído 1'
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
