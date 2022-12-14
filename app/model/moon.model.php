<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;

class Moon extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id único de cada luna'
			),
			new OModelField(
				name: 'id_planet',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				ref: 'planet.id',
				comment: 'Id del planeta al que pertenece la luna'
			),
			new OModelField(
				name: 'original_name',
				type: OMODEL_TEXT,
				nullable: false,
				default: 'null',
				size: 50,
				comment: 'Nombre original de la luna'
			),
			new OModelField(
				name: 'name',
				type: OMODEL_TEXT,
				nullable: false,
				default: 'null',
				size: 50,
				comment: 'Nombre actual de la luna'
			),
			new OModelField(
				name: 'type',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Tipo de luna'
			),
			new OModelField(
				name: 'radius',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Radio de la luna'
			),
			new OModelField(
				name: 'rotation',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Velocidad de rotación de la luna alrededor del planeta'
			),
			new OModelField(
				name: 'distance',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Distancia de la luna a su planeta'
			),
			new OModelField(
				name: 'explored',
				type: OMODEL_BOOL,
				comment: 'Indica si la luna ha sido explorada 1 o no 0'
			),
			new OModelField(
				name: 'explore_time',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Tiempo necesario para explorar la luna'
			),
			new OModelField(
				name: 'id_npc',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				ref: 'npc.id',
				comment: 'Id del NPC que habita el planeta o null si no tiene'
			),
			new OModelField(
				name: 'id_construction',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				ref: 'construction.id',
				comment: 'Id de la construcción que hay en el planeta o null si no tiene'
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
