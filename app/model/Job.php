<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class Job extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name  = 'job';
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id única para cada tarea'
			],
			'id_player' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'ref' => 'player.id',
				'comment' => 'Id del jugador que hace la tarea'
			],
			'type' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Tipo de tarea'
			],
			'value' => [
				'type'    => OModel::LONGTEXT,
				'nullable' => true,
				'default' => null,
				'comment' => 'Información extra de la tarea'
			],
			'start' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Timestamp de inicio de la tarea'
			],
			'end' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Timestamp de fin de la tarea'
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