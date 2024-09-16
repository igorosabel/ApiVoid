<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\DB\OModel;
use Osumi\OsumiFramework\DB\OModelGroup;
use Osumi\OsumiFramework\DB\OModelField;
use Osumi\OsumiFramework\App\Model\Module;

class NPCModule extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id_npc',
				type: OMODEL_PK,
				incr: false,
				ref: 'npc.id',
				comment: 'Id del NPC que hace la venta'
			),
			new OModelField(
				name: 'id_module',
				type: OMODEL_PK,
				incr: false,
				ref: 'module.id',
				comment: 'Id del módulo que vende'
			),
			new OModelField(
				name: 'start_value',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Cantidad inicial de módulos que vende'
			),
			new OModelField(
				name: 'value',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Cantidad de módulos que le quedan disponibles'
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

	private ?Module $module = null;

	/**
	 * Obtiene el módulo del NPC
	 *
	 * @return Module Módulo del NPC
	 */
	public function getModule(): Module {
		if (is_null($this->module)) {
			$this->loadModule();
		}
		return $this->module;
	}

	/**
	 * Guarda el módulo del NPC
	 *
	 * @param Module $module Módulo del NPC
	 *
	 * @return void
	 */
	public function setModule(Module $module): void {
		$this->module = $module;
	}

	/**
	 * Carga el módulo del NPC
	 *
	 * @param int $margin Margen de precio del NPC al vender el módulo
	 *
	 * @return void
	 */
	public function loadModule(int $margin = 0): void {
		$sql = "SELECT * FROM `module` WHERE `id` = ?";
		$this->db->query($sql, [$this->get('id_module')]);
		$res = $this->db->next();

		$module = new Module();
		$module->update($res);
		$credits = $module->get('credits') * (1 + ($margin/100));
		$module->set('credits', $credits);

		$this->setModule($module);
	}
}
