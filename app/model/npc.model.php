<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\App\Model\NPCResource;

class NPC extends OModel {
	function __construct() {
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único de cada NPC'
			],
			'name' => [
				'type'    => OModel::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 50,
				'comment' => 'Nombre del NPC'
			],
			'id_race' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Id de la raza del NPC'
			],
			'id_system' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Id del sistema en el que está el NPC'
			],
			'margin' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => 0,
				'comment' => 'Margen del NPC respecto a precios originales (-20/20 %)'
			],
			'found' => [
				'type'    => OModel::BOOL,
				'nullable' => false,
				'default' => false,
				'comment' => 'Indica si el NPC ya ha sido encontrado 1 o no 0'
			],
			'last_reset' => [
				'type'    => OModel::DATE,
				'nullable' => true,
				'default' => null,
				'comment' => 'Fecha del último reseteo del NPC'
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

	private ?array $ships = null;

	/**
	 * Obtiene la lista de naves del NPC
	 *
	 * @return array Lista de naves del NPC
	 */
	public function getShips(): array {
		if (is_null($this->ships)) {
			$this->loadShips();
		}
		return $this->ships;
	}

	/**
	 * Guarda la lista de naves del NPC
	 *
	 * @param array $ships Lista de naves del NPC
	 *
	 * @return void
	 */
	public function setShips(array $ships): void {
		$this->ships = $ships;
	}

	/**
	 * Carga la lista de naves del NPC
	 *
	 * @return void
	 */
	public function loadShips(): void {
		$sql = "SELECT * FROM `npc_ship` WHERE `id_npc` = ?";
		$this->db->query($sql, [$this->get('id')]);
		$ships = [];
		while ($res = $this->db->next()){
			$npc_ship = new NPCShip();
			$npc_ship->update($res);
			$npc_ship->loadShip($this->get('margin'));

			array_push($ships, $npc_ship);
		}
		$this->setShips($ships);
	}

	private ?array $modules = null;

	/**
	 * Obtiene la lista de módulos del NPC
	 *
	 * @return array Lista de módulos del NPC
	 */
	public function getModules(): array {
		if (is_null($this->modules)) {
		  $this->loadModules();
		}
		return $this->modules;
	}

	/**
	 * Guarda la lista de módulos del NPC
	 *
	 * @param array $modules Lista de módulos del NPC
	 *
	 * @return void
	 */
	public function setModules(array $modules): void {
		$this->modules = $modules;
	}

	/**
	 * Carga la lista de módulos del NPC
	 *
	 * @return void
	 */
	public function loadModules(): void {
		$sql = "SELECT * FROM `npc_module` WHERE `id_npc` = ?";
		$this->db->query($sql, [$this->get('id')]);
		$modules = [];
		while ($res = $this->db->next()) {
			$npc_module = new NPCModule();
			$npc_module->update($res);
			$npc_module->loadModule($this->get('margin'));

			array_push($modules, $npc_module);
		}
		$this->setModules($modules);
	}

	private ?array $resources = null;

	/**
	 * Obtiene la lista de recursos del NPC
	 *
	 * @return array Lista de recursos del NPC
	 */
	public function getResources(): array {
		if (is_null($this->resources)) {
		  $this->loadResources();
		}
		return $this->resources;
	}

	/**
	 * Guarda la lista de recursos del NPC
	 *
	 * @param array $resources Lista de recursos del NPC
	 *
	 * @return void
	 */
	public function setResources(array $resources): void {
		$this->resources = $resources;
	}

	/**
	 * Carga la lista de recursos del NPC
	 *
	 * @return void
	 */
	public function loadResources(): void {
		$sql = "SELECT * FROM `npc_resource` WHERE `id_npc` = ?";
		$this->db->query($sql, [$this->get('id')]);
		$resources = [];
		while ($res = $this->db->next()) {
			$npc_resource = new NPCResource();
			$npc_resource->update($res);
			$npc_resource->loadResource($this->get('margin'));

			array_push($resources, $npc_resource);
		}
		$this->setResources($resources);
	}
}
