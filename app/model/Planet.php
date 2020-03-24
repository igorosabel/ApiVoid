<?php
class Planet extends OModel{
	function __construct(){
		$table_name  = 'planet';
		$model = [
			'id' => [
				'type'    => OCore::PK,
				'comment' => 'Id único del planeta'
			],
			'id_system' => [
				'type'    => OCore::NUM,
				'nullable' => false,
				'default' => null,
				'ref' => 'system.id',
				'comment' => 'Id del sistema al que pertenece el planeta'
			],
			'original_name' => [
				'type'    => OCore::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 50,
				'comment' => 'Nombre original del planeta'
			],
			'name' => [
				'type'    => OCore::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 50,
				'comment' => 'Nombre actual del planeta'
			],
			'type' => [
				'type'    => OCore::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Tipo de planeta'
			],
			'radius' => [
				'type'    => OCore::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Radio del planeta'
			],
			'rotation' => [
				'type'    => OCore::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Velocidad de rotación del planeta alrededor del sol'
			],
			'distance' => [
				'type'    => OCore::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Distancia del planeta a su sol'
			],
			'num_moons' => [
				'type'    => OCore::NUM,
				'nullable' => false,
				'default' => '0',
				'comment' => 'Número de lunas que tiene el planeta'
			],
			'explored' => [
				'type'    => OCore::BOOL,
				'default' => false,
				'comment' => 'Indica si el planeta ha sido explorado 1 o no 0'
			],
			'explore_time' => [
				'type'    => OCore::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Tiempo necesario para explorar el planeta'
			],
			'id_npc' => [
				'type'    => OCore::NUM,
				'nullable' => true,
				'default' => null,
				'ref' => 'npc.id',
				'comment' => 'Id del NPC que habita el planeta o null si no tiene'
			],
			'id_construction' => [
				'type'    => OCore::NUM,
				'nullable' => true,
				'default' => null,
				'ref' => 'construction.id',
				'comment' => 'Id de la construcción que hay en el planeta o null si no tiene'
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

	private $moons = null;

	public function getMoons(){
		if (is_null($this->moons)){
			$this->loadMoons();
		}
		return $this->moons;
	}

	public function setMoons($moons){
		$this->moons = $moons;
	}

	public function loadMoons(){
		$sql = "SELECT * FROM `moon` WHERE `id_planet` = ?";
		$this->db->query($sql, [$this->get('id')]);
		$moons = [];
		while($res = $this->db->next()){
			$moon = new Moon();
			$moon->update($res);
			array_push($moons, $moon);
		}
		$this->setMoons($moons);
	}

	private $resources = null;

	public function getResources(){
		if (is_null($this->resources)){
			$this->loadResources();
		}
		return $this->resources;
	}

	public function setResources($resources){
		$this->resources = $resources;
	}

	public function loadResources(){
		$sql = "SELECT * FROM `resource` WHERE `id_planet` = ?";
		$this->db->query($sql, [$this->get('id')]);
		$resources = [];
		while($res = $this->db->next()){
			$resource = new Resource();
			$resource->update($res);
			array_push($resources, $resource);
		}
		$this->setResources($resources);
	}

	private $planet_type_name = null;
	private $planet_type_url  = null;

	public function getTypeName(){
		if (is_null($this->planet_type_name)){
			$this->loadPlanetType();
		}
		return $this->planet_type_name;
	}

	public function setTypeName($planet_type_name){
		$this->planet_type_name = $planet_type_name;
	}

	public function getTypeURL(){
		if (is_null($this->planet_type_url)){
			$this->loadPlanetType();
		}
		return $this->planet_type_url;
	}

	public function setTypeURL($planet_type_url){
		$this->planet_type_url = $planet_type_url;
	}

	public function loadPlanetType(){
		$planet_types = OTools::getCache('planet');
		$this->setTypeName($planet_types['planet_types']['type_'.$this->get('type')]['type']);
		$this->setTypeURL($planet_types['planet_types']['type_'.$this->get('type')]['url']);
	}

	private $npc = null;

	public function getNPC(){
		if (is_null($this->npc)){
			$this->loadNPC();
		}
		return $this->npc;
	}
	
	public function setNPC($npc){
		$this->npc = $npc;
	}
	
	public function loadNPC(){
		
	}
}