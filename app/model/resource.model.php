<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class Resource extends OModel {
	function __construct() {
		$model = [
			'id_planet' => [
				'type'    => OModel::PK,
				'incr' => false,
				'ref' => 'planet.id',
				'comment' => 'Id del planeta que contiene el recurso'
			],
			'id_moon' => [
				'type'    => OModel::PK,
				'incr' => false,
				'ref' => 'moon.id',
				'comment' => 'Id de la luna que contiene el recurso'
			],
			'type' => [
				'type'    => OModel::PK,
				'incr' => false,
				'comment' => 'Tipo de recurso'
			],
			'value' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Cantidad del recurso'
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

	private ?array $resource = null;

	/**
	 * Obtiene los datos del recurso
	 *
	 * @return array Datos del recurso
	 */
	public function getResource(): array {
		if (is_null($this->resource)) {
		  $this->loadResource();
		}
		return $this->resource;
	}

	/**
	 * Guarda los datos del recurso
	 *
	 * @param array $resource Datos del recurso
	 *
	 * @return void
	 */
	public function setResource(array $resource): void {
		$this->resource = $resource;
	}

	/**
	 * Carga los datos del recurso
	 *
	 * @return void
	 */
	public function loadResource(): void {
		global $core;
		$resources = json_decode(file_get_contents($core->config->getDir('ofw_cache').'resource.json'), true);
		$list = $resources['resources'];
		$key = array_search($this->get('type'), array_column($list, 'id'));
		$resource = $list[$key];
		$resource['value'] = $this->get('value');

		$this->setResource($resource);
	}
}
