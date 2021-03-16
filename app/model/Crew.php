<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class Crew extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name  = 'crew';
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único de cada tripulante'
			],
			'id_player' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'ref' => 'player.id',
				'comment' => 'Id del jugador que contrata al tripulante'
			],
			'name' => [
				'type'    => OModel::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 50,
				'comment' => 'Nombre del tripulante'
			],
			'id_race' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Id de la raza del tripulante'
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