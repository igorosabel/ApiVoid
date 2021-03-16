<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class Moon extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name  = 'moon';
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único de cada luna'
			],
			'id_planet' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'ref' => 'planet.id',
				'comment' => 'Id del planeta al que pertenece la luna'
			],
			'original_name' => [
				'type'    => OModel::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 50,
				'comment' => 'Nombre original de la luna'
			],
			'name' => [
				'type'    => OModel::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 50,
				'comment' => 'Nombre actual de la luna'
			],
			'type' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Tipo de luna'
			],
			'radius' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Radio de la luna'
			],
			'rotation' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Velocidad de rotación de la luna alrededor del planeta'
			],
			'distance' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Distancia de la luna a su planeta'
			],
			'explored' => [
				'type'    => OModel::BOOL,
				'comment' => 'Indica si la luna ha sido explorada 1 o no 0'
			],
			'explore_time' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Tiempo necesario para explorar la luna'
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

		parent::load($table_name, $model);
	}

	private ?array $resources = null;

	/**
	 * Obtiene la lista de recursos de la luna
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
	 * Guarda la lista de recursos de la luna
	 *
	 * @param array $resources Lista de recursos
	 *
	 * @return void
	 */
	public function setResources(array $resources): void {
		$this->resources = $resources;
	}

	/**
	 * Carga la lista de recursos de la luna
	 *
	 * @return void
	 */
	public function loadResources(): void {
		$sql = "SELECT * FROM `resource` WHERE `id_moon` = ?";
		$this->db->query($sql, [$this->get('id')]);
		$resources = [];
		while($res = $this->db->next()) {
			$resource = new Resource();
			$resource->update($res);
			array_push($resources, $resource);
		}
		$this->setResources($resources);
	}
}