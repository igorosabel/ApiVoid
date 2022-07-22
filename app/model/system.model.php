<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\App\Model\Player;
use OsumiFramework\App\Model\Planet;

class System extends OModel {
	function __construct() {
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único de cada sistema'
			],
			'id_player' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'ref' => 'player.id',
				'comment' => 'Id del jugador que descubre el sistema'
			],
			'original_name' => [
				'type'    => OModel::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 50,
				'comment' => 'Nombre original del sistema'
			],
			'name' => [
				'type'    => OModel::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 50,
				'comment' => 'Nombre actual del sistema'
			],
			'num_planets' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => '0',
				'comment' => 'Número de planetas que tiene el sistema'
			],
			'fully_explored' => [
				'type'    => OModel::BOOL,
				'nullable' => false,
				'default' => false,
				'comment' => 'Indica si el sistema ha sido completamente explorado 1 o no 0'
			],
			'num_npc' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => '0',
				'comment' => 'Número de NPCs que hay en el sistema'
			],
			'type' => [
				'type'    => OModel::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 5,
				'comment' => 'Tipo de sol'
			],
			'radius' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Radio del sol'
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

	private ?array $planets = null;

	/**
	 * Obtiene la lista de planetas del sistema
	 *
	 * @return array Lista de planetas
	 */
	public function getPlanets(): array {
		if (is_null($this->planets)) {
			$this->loadPlanets();
		}
		return $this->planets;
	}

	/**
	 * Guarda la lista de planetas del sistema
	 *
	 * @param array $planets Lista de planetas
	 *
	 * @return void
	 */
	public function setPlanets(array $planets): void {
		$this->planets = $planets;
	}

	/**
	 * Carga la lista de planetas del sistema
	 *
	 * @return void
	 */
	public function loadPlanets(): void {
		$sql = "SELECT * FROM `planet` WHERE `id_system` = ?";
		$this->db->query($sql, [$this->get('id')]);
		$planets = [];
		while ($res = $this->db->next()) {
			$planet = new Planet();
			$planet->update($res);
			array_push($planets, $planet);
		}
		$this->setPlanets($planets);
	}

	private ?array $connections = null;

	/**
	 * Obtiene la lista de conexiones del sistema
	 *
	 * @return array Lista de conexiones
	 */
	public function getConnections(): array {
		if (is_null($this->connections)) {
			$this->loadConnections();
		}
		return $this->connections;
	}

	/**
	 * Guarda la lista de conexiones del sistema
	 *
	 * @param array $connections Lista de conexiones
	 *
	 * @return void
	 */
	public function setConnections(array $connections): void {
		$this->connections = $connections;
	}

	/**
	 * Carga la lista de conexiones del sistema
	 *
	 * @return void
	 */
	public function loadConnections(): void {
		$sql = "SELECT * FROM `connection` WHERE `id_system_start` = ? ORDER BY `order`";
		$this->db->query($sql, [$this->get('id')]);
		$connections = [];
		while ($res = $this->db->next()) {
			$connection = new Connection();
			$connection->update($res);
			array_push($connections, $connection);
		}
		$this->setConnections($connections);
	}

	private ?Player $discoverer = null;

	/**
	 * Obtiene el jugador descubridor del sistema
	 *
	 * @return Player Jugador descubridor del sistema
	 */
	public function getDiscoverer(): Player {
		if (is_null($this->discoverer)) {
			$this->loadDiscoverer();
		}
		return $this->discoverer;
	}

	/**
	 * Guarda el jugador descubridor del sistema
	 *
	 * @param Player Jugador descubridor del sistema
	 *
	 * @return void
	 */
	public function setDiscoverer(Player $discoverer): void {
		$this->discoverer = $discoverer;
	}

	/**
	 * Carga el jugador descubridor del sistema
	 *
	 * @return void
	 */
	public function loadDiscoverer(): void {
		$sql = "SELECT * FROM `player` WHERE `id` = ?";
		$this->db->query($sql, [$this->get('id_player')]);
		$res = $this->db->next();

		$discoverer = new Player();
		$discoverer->update($res);

		$this->setDiscoverer($discoverer);
	}

	private ?string $system_type_name = null;
	private ?string $system_type_color = null;

	/**
	 * Obtiene el nombre del tipo de sistema
	 *
	 * @return string Nombre del tipo de sistema
	 */
	public function getTypeName(): string {
		if (is_null($this->system_type_name)) {
			$this->loadSystemType();
		}
		return $this->system_type_name;
	}

	/**
	 * Guarda el nombre del tipo de sistema
	 *
	 * @param string $system_type_name Nombre del tipo de sistema
	 *
	 * @return void
	 */
	public function setTypeName(string $system_type_name): void {
		$this->system_type_name = $system_type_name;
	}

	/**
	 * Obtiene el color del tipo de sistema
	 *
	 * @return string Color del tipo de sistema
	 */
	public function getTypeColor(): string {
		if (is_null($this->system_type_color)) {
			$this->loadSystemType();
		}
		return $this->system_type_color;
	}

	/**
	 * Guarda el color del tipo de sistema
	 *
	 * @param string $system_type_color Color del tipo de sistema
	 *
	 * @return void
	 */
	public function setTypeColor(string $system_type_color): void {
		$this->system_type_color = $system_type_color;
	}

	/**
	 * Carga el nombre y color del tipo de sistema
	 *
	 * @return void
	 */
	public function loadSystemType(): void {
		global $core;
		$type_data = explode('-', $this->get('type'));
		$system = json_decode(file_get_contents($core->config->getDir('ofw_cache').'system.json'), true);
		$mkk_types = $system['mkk_types'];
		$spectral_types = $system['spectral_types'];

		$key = array_search($type_data[0], array_column($mkk_types, 'type'));
		$stype = $mkk_types[$key];
		$this->setTypeName($stype['name']);

		foreach ($spectral_types as $stype) {
			if ($stype['type']==$type_data[1]){
				$this->setTypeColor($stype['color']);
				break;
			}
		}
	}

	/**
	 * Obtiene la URL de información del tipo de sistema
	 *
	 * @return string URL de información
	 */
	public function getSystemInfoLink(): string {
		global $core;
		$system = json_decode(file_get_contents($core->config->getDir('ofw_cache').'system.json'), true);
		return $system['info'];
	}
}
