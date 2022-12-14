<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;
use OsumiFramework\OFW\Tools\OTools;

class NPCResource extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id_npc',
				type: OMODEL_PK,
				incr: false,
				ref: 'npc.id',
				comment: 'Id del NPC que tiene un recurso a la venta'
			),
			new OModelField(
				name: 'type',
				type: OMODEL_PK,
				incr: false,
				comment: 'Tipo de recurso'
			),
			new OModelField(
				name: 'start_value',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Cantidad inicial del recurso que vende'
			),
			new OModelField(
				name: 'value',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Cantidad del recurso que le queda disponible'
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

	private ?array $resource = null;

	/**
	 * Obtiene el recurso del NPC
	 *
	 * @return array Recurso del NPC
	 */
	public function getResource(): array {
		if (is_null($this->resource)) {
			$this->loadResource();
		}
		return $this->resource;
	}

	/**
	 * Guarda el recurso del NPC
	 *
	 * @param array $resource Recurso del NPC
	 *
	 * @return void
	 */
	public function setResource(array $resource): void {
		$this->resource = $resource;
	}

	/**
	 * Carga el recurso del NPC
	 *
	 * @param int $margin Margen de precio del NPC al vender el módulo
	 *
	 * @return void
	 */
	public function loadResource(int $margin = 0): void {
		$resources = OTools::getCache('resource');
		$key = array_search($this->get('type'), array_column($resources['resources'], 'id'));
		$resource = $resources['resources'][$key];
		$resource['credits'] = $resource['price'] * (1 + ($margin/100));

		$this->setResource($resource);
	}
}
