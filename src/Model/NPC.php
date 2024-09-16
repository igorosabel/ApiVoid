<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\DB\OModel;
use Osumi\OsumiFramework\DB\OModelGroup;
use Osumi\OsumiFramework\DB\OModelField;
use Osumi\OsumiFramework\App\Model\NPCShip;
use Osumi\OsumiFramework\App\Model\NPCModule;
use Osumi\OsumiFramework\App\Model\NPCResource;

class NPC extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id único de cada NPC'
			),
			new OModelField(
				name: 'name',
				type: OMODEL_TEXT,
				nullable: false,
				default: 'null',
				size: 50,
				comment: 'Nombre del NPC'
			),
			new OModelField(
				name: 'id_race',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Id de la raza del NPC'
			),
			new OModelField(
				name: 'id_system',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Id del sistema en el que está el NPC'
			),
			new OModelField(
				name: 'margin',
				type: OMODEL_NUM,
				nullable: false,
				default: 0,
				comment: 'Margen del NPC respecto a precios originales (-20/20 %)'
			),
			new OModelField(
				name: 'found',
				type: OMODEL_BOOL,
				nullable: false,
				default: false,
				comment: 'Indica si el NPC ya ha sido encontrado 1 o no 0'
			),
			new OModelField(
				name: 'last_reset',
				type: OMODEL_DATE,
				nullable: true,
				default: 'null',
				comment: 'Fecha del último reseteo del NPC'
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
