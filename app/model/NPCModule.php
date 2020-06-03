<?php declare(strict_types=1);
class NPCModule extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name  = 'npc_module';
		$model = [
			'id_npc' => [
				'type'    => OCore::PK,
				'incr' => false,
				'ref' => 'npc.id',
				'comment' => 'Id del NPC que hace la venta'
			],
			'id_module' => [
				'type'    => OCore::PK,
				'incr' => false,
				'ref' => 'module.id',
				'comment' => 'Id del módulo que vende'
			],
			'start_value' => [
				'type'    => OCore::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Cantidad inicial de módulos que vende'
			],
			'value' => [
				'type'    => OCore::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Cantidad de módulos que le quedan disponibles'
			],
			'created_at' => [
				'type'    => OCore::CREATED,
				'comment' => 'Fecha de creación del registro'
			],
			'updated_at' => [
				'type'    => OCore::UPDATED,
				'nullable' => true,
				'default' => null,
				'comment' => 'Fecha de última modificación del registro'
			]
		];

		parent::load($table_name, $model);
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