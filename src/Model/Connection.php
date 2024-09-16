<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\DB\OModel;
use Osumi\OsumiFramework\DB\OModelGroup;
use Osumi\OsumiFramework\DB\OModelField;
use Osumi\OsumiFramework\App\Model\System;

class Connection extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id única de cada conexión'
			),
			new OModelField(
				name: 'id_system_start',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				ref: 'system.id',
				comment: 'Id del sistema del que se parte'
			),
			new OModelField(
				name: 'id_system_end',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				ref: 'system.id',
				comment: 'Id del sistema destino o null si todavía no se ha investigado'
			),
			new OModelField(
				name: 'order',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Orden de la conexión entre las que tiene un sistema'
			),
			new OModelField(
				name: 'navigate_time',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Tiempo que se tarda en navegar al sistema destino'
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

	private ?System $system_start = null;

	/**
	 * Obtiene el sistema de inicio de la conexión
	 *
	 * @return System Sistema de inicio de la conexión
	 */
	public function getSystemStart(): System {
		if (is_null($this->system_start)) {
			$this->loadSystemStart();
		}
		return $this->system_start;
	}

	/**
	 * Guarda el sistema de inicio de la conexión
	 *
	 * @param System Sistema de inicio de la conexión
	 *
	 * @return void
	 */
	public function setSystemStart(System $system_start): void {
		$this->system_start = $system_start;
	}

	/**
	 * Carga el sistema de inicio de la conexión
	 *
	 * @return void
	 */
	public function loadSystemStart(): void {
		$sql = "SELECT * FROM `system` WHERE `id` = ?";
		$this->db->query($sql, [$this->get('id_system_start')]);
		$res = $this->db->next();

		$system_start = new System();
		$system_start->update($res);

		$this->setSystemStart($system_start);
	}

	private ?System $system_end = null;

	/**
	 * Obtiene el sistema de fin de la conexión
	 *
	 * @return System Sistema de fin de la conexión
	 */
	public function getSystemEnd(): System {
		if (is_null($this->system_end)) {
			$this->loadSystemEnd();
		}
		return $this->system_end;
	}

	/**
	 * Guarda el sistema de fin de la conexión
	 *
	 * @param System Sistema de fin de la conexión
	 *
	 * @return void
	 */
	public function setSystemEnd(System $system_end): void {
		$this->system_end = $system_end;
	}

	/**
	 * Carga el sistema de fin de la conexión
	 *
	 * @return void
	 */
	public function loadSystemEnd(): void {
		$sql = "SELECT * FROM `system` WHERE `id` = ?";
		$this->db->query($sql, [$this->get('id_system_end')]);
		$res = $this->db->next();

		$system_end = new System();
		$system_end->update($res);

		$this->setSystemEnd($system_end);
	}
}
