<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class Construction extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name  = 'construction';
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único para cada construcción'
			],
			'id_player' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'ref' => 'player.id',
				'comment' => 'Id del jugador que hace la construcción'
			],
			'commerce' => [
				'type'    => OModel::BOOL,
				'comment' => 'Indica si tiene puesto de comercio 1 o no 0'
			],
			'repair' => [
				'type'    => OModel::BOOL,
				'comment' => 'Indica si tiene taller de reparaciones 1 o no 0'
			],
			'workshop' => [
				'type'    => OModel::BOOL,
				'comment' => 'Indica si tiene taller de construcciones 1 o no 0'
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
}