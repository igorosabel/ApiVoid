<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\App\Model\Moon;

class Planet extends OModel {
	function __construct() {
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único del planeta'
			],
			'id_system' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'ref' => 'system.id',
				'comment' => 'Id del sistema al que pertenece el planeta'
			],
			'original_name' => [
				'type'    => OModel::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 50,
				'comment' => 'Nombre original del planeta'
			],
			'name' => [
				'type'    => OModel::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 50,
				'comment' => 'Nombre actual del planeta'
			],
			'type' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Tipo de planeta'
			],
			'radius' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Radio del planeta'
			],
			'rotation' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Velocidad de rotación del planeta alrededor del sol'
			],
			'distance' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Distancia del planeta a su sol'
			],
			'num_moons' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => '0',
				'comment' => 'Número de lunas que tiene el planeta'
			],
			'explored' => [
				'type'    => OModel::BOOL,
				'default' => false,
				'comment' => 'Indica si el planeta ha sido explorado 1 o no 0'
			],
			'explore_time' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Tiempo necesario para explorar el planeta'
			],
			'id_npc' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => null,
				'ref' => 'npc.id',
				'comment' => 'Id del NPC que habita el planeta o null si no tiene'
			],
			'id_construction' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => null,
				'ref' => 'construction.id',
				'comment' => 'Id de la construcción que hay en el planeta o null si no tiene'
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

	private ?array $moons = null;

	/**
	 * Obtiene la lista de lunas del planeta
	 *
	 * @return array Lista de lunas
	 */
	public function getMoons(): array {
		if (is_null($this->moons)) {
			$this->loadMoons();
		}
		return $this->moons;
	}

	/**
	 * Guarda la lista de lunas del planeta
	 *
	 * @param array $moons Lista de lunas
	 *
	 * @return void
	 */
	public function setMoons(array $moons): void {
		$this->moons = $moons;
	}

	/**
	 * Carga la lista de lunas del planeta
	 *
	 * @return void
	 */
	public function loadMoons(): void {
		$sql = "SELECT * FROM `moon` WHERE `id_planet` = ?";
		$this->db->query($sql, [$this->get('id')]);
		$moons = [];
		while($res = $this->db->next()) {
			$moon = new Moon();
			$moon->update($res);
			array_push($moons, $moon);
		}
		$this->setMoons($moons);
	}

	private ?array $resources = null;

	/**
	 * Obtiene la lista de recursos del planeta
	 *
	 * @return array Lista de recursos
	 */
	public function getResources(): array {
		if (is_null($this->resources)) {
			$this->loadResources();
		}
		return $this->resources;
	}

	/**
	 * Guarda la lista de recursos del planeta
	 *
	 * @param array $resources Lista de recursos
	 *
	 * @return void
	 */
	public function setResources(array $resources): void {
		$this->resources = $resources;
	}

	/**
	 * Carga la lista de recursos del planeta
	 *
	 * @return void
	 */
	public function loadResources(): void {
		$sql = "SELECT * FROM `resource` WHERE `id_planet` = ?";
		$this->db->query($sql, [$this->get('id')]);
		$resources = [];
		while ($res = $this->db->next()) {
			$resource = new Resource();
			$resource->update($res);
			array_push($resources, $resource);
		}
		$this->setResources($resources);
	}

	private ?string $planet_type_name = null;
	private ?string $planet_type_url  = null;

	/**
	 * Obtiene el nombre del tipo de planeta
	 *
	 * @return string Nombre del tipo de planeta
	 */
	public function getTypeName(): string {
		if (is_null($this->planet_type_name)) {
			$this->loadPlanetType();
		}
		return $this->planet_type_name;
	}

	/**
	 * Guarda el nombre del tipo de planeta
	 *
	 * @param string $planet_type_name Nombre del tipo de planeta
	 *
	 * @return void
	 */
	public function setTypeName(string $planet_type_name): void {
		$this->planet_type_name = $planet_type_name;
	}

	/**
	 * Obtiene la URL del tipo de planeta
	 *
	 * @return string URL del tipo de planeta
	 */
	public function getTypeURL(): string {
		if (is_null($this->planet_type_url)) {
			$this->loadPlanetType();
		}
		return $this->planet_type_url;
	}

	/**
	 * Guarda la URL del tipo de planeta
	 *
	 * @param string $planet_type_url URL del tipo de planeta
	 *
	 * @return void
	 */
	public function setTypeURL(string $planet_type_url): void {
		$this->planet_type_url = $planet_type_url;
	}

	/**
	 * Carga el nombre y la URL del tipo de planeta
	 *
	 * @return void
	 */
	public function loadPlanetType(): void {
		global $core;
		$planet_types = json_decode(file_get_contents($core->config->getDir('ofw_cache').'planet.json'), true);
		$type_list = $planet_types['planet_types'];

		$this->setTypeName($type_list['type_'.$this->get('type')]['type']);
		$this->setTypeURL($type_list['type_'.$this->get('type')]['url']);
	}

	private ?NPC $npc = null;

	/**
	 * Obtiene el NPC del planeta
	 *
	 * @return NPC NPC del planeta
	 */
	public function getNPC(): NPC {
		if (is_null($this->npc)) {
			$this->loadNPC();
		}
		return $this->npc;
	}

	/**
	 * Guarda el NPC del planeta
	 *
	 * @param NPC $npc NPC del planeta
	 *
	 * @return void
	 */
	public function setNPC(NPC $npc): void {
		$this->npc = $npc;
	}

	/**
	 * Carga el NPC del planeta
	 *
	 * @return void
	 */
	public function loadNPC(): void {

	}
}
