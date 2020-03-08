<?php
class System extends OBase{
	function __construct(){
		$table_name  = 'system';
		$model = [
			'id' => [
				'type'    => Base::PK,
				'comment' => 'Id único de cada sistema'
			],
			'id_player' => [
				'type'    => Base::NUM,
				'nullable' => false,
				'default' => null,
				'ref' => 'player.id',
				'comment' => 'Id del jugador que descubre el sistema'
			],
			'original_name' => [
				'type'    => Base::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 50,
				'comment' => 'Nombre original del sistema'
			],
			'name' => [
				'type'    => Base::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 50,
				'comment' => 'Nombre actual del sistema'
			],
			'num_planets' => [
				'type'    => Base::NUM,
				'nullable' => false,
				'default' => '0',
				'comment' => 'Número de planetas que tiene el sistema'
			],
			'fully_explored' => [
				'type'    => Base::BOOL,
				'nullable' => false,
				'default' => false,
				'comment' => 'Indica si el sistema ha sido completamente explorado 1 o no 0'
			],
			'num_npc' => [
				'type'    => Base::NUM,
				'nullable' => false,
				'default' => '0',
				'comment' => 'Número de NPCs que hay en el sistema'
			],
			'type' => [
				'type'    => Base::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 5,
				'comment' => 'Tipo de sol'
			],
			'radius' => [
				'type'    => Base::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Radio del sol'
			],
			'created_at' => [
				'type'    => Base::CREATED,
				'comment' => 'Fecha de creación del registro'
			],
			'updated_at' => [
				'type'    => Base::UPDATED,
				'nullable' => true,
				'default' => null,
				'comment' => 'Fecha de última modificación del registro'
			]
		];

		parent::load($table_name, $model);
	}

	private $planets = null;

	public function getPlanets(){
		if (is_null($this->planets)){
			$this->loadPlanets();
		}
		return $this->planets;
	}

	public function setPlanets($planets){
		$this->planets = $planets;
	}

	public function loadPlanets(){
		$sql = "SELECT * FROM `planet` WHERE `id_system` = ?";
		$this->db->query($sql, [$this->get('id')]);
		$planets = [];
		while($res = $this->db->next()){
			$planet = new Planet();
			$planet->update($res);
			array_push($planets, $planet);
		}
		$this->setPlanets($planets);
	}

	private $connections = null;

	public function getConnections(){
		if (is_null($this->connections)){
			$this->loadConnections();
		}
		return $this->connections;
	}

	public function setConnections($connections){
		$this->connections = $connections;
	}

	public function loadConnections(){
		$sql = "SELECT * FROM `connection` WHERE `id_system_start` = ? ORDER BY `order`";
		$this->db->query($sql, [$this->get('id')]);
		$connections = [];
		while($res = $this->db->next()){
			$connection = new Connection();
			$connection->update($res);
			array_push($connections, $connection);
		}
		$this->setConnections($connections);
	}

	private $discoverer = null;

	public function getDiscoverer(){
		if (is_null($this->discoverer)){
			$this->loadDiscoverer();
		}
		return $this->discoverer;
	}

	public function setDiscoverer($discoverer){
		$this->discoverer = $discoverer;
	}

	public function loadDiscoverer(){
		$sql = "SELECT * FROM `player` WHERE `id` = ?";
		$this->db->query($sql, [$this->get('id_player')]);
		$res = $this->db->next();

		$discoverer = new Player();
		$discoverer->update($res);

		$this->setDiscoverer($discoverer);
	}

	private $system_type_name = null;
	private $system_type_color = null;

	public function getTypeName(){
		if (is_null($this->system_type_name)){
			$this->loadSystemType();
		}
		return $this->system_type_name;
	}

	public function setTypeName($system_type_name){
		$this->system_type_name = $system_type_name;
	}

	public function getTypeColor(){
		if (is_null($this->system_type_color)){
			$this->loadSystemType();
		}
		return $this->system_type_color;
	}

	public function setTypeColor($system_type_color){
		$this->system_type_color = $system_type_color;
	}

	public function loadSystemType(){
		$type_data = explode('-', $this->get('type'));
		$system = Base::getCache('system');

		$key = array_search($type_data[0], array_column($system['mkk_types'], 'type'));
		$stype = $system['mkk_types'][$key];
		$this->setTypeName($stype['name']);

		foreach ($system['spectral_types'] as $stype){
			if ($stype['type']==$type_data[1]){
				$this->setTypeColor($stype['color']);
				break;
			}
		}
	}

	public function getSystemInfoLink(){
		$system = Base::getCache('system');
		return $system['info'];
	}
}