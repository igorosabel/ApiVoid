<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class PlayerShip extends OModel {
	#[OPK(
		comment: 'PK'
	)]
	public ?int $id;

	#[OField(
		comment: 'FK player',
		nullable: false,
		default: 0,
		ref: 'player.id'
	)]
	public ?int $id_player;

	#[OField(
		comment: 'FK ship_type',
		nullable: false,
		default: 0,
		ref: 'ship_type.id'
	)]
	public ?int $id_ship_type;

	#[OField(
		comment: 'Nombre personalizado',
		nullable: true,
		max: 100,
		default: null
	)]
	public ?string $name;

	#[OField(
		comment: 'HP actual',
		nullable: false,
		default: 0
	)]
	public ?int $hp_current;

	#[OField(
		comment: 'Resistencia actual',
		nullable: false,
		default: 0
	)]
	public ?int $resistance_current;

	#[OField(
		comment: 'Carga usada (kg)',
		nullable: false,
		default: 0
	)]
	public ?int $cargo_used_kg;

	#[OCreatedAt(
		comment: 'Creación'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
		comment: 'Última actualización'
	)]
	public ?string $updated_at;

	private ?array $player_ship_modules = null;

	/**
	 * Save PlayerShipModule list
	 *
	 * @param array $player_ship_modules PlayerShipModule list
	 *
	 * @return void
	 */
	public function setPlayerShipModules(array $player_ship_modules): void {
		$this->player_ship_modules = $player_ship_modules;
	}

	/**
	 * Get PlayerShipModule list
	 *
	 * @return array player_ship_module list
	 */
	public function getPlayerShipModules(): array {
		if (is_null($this->player_ship_modules)) {
			$this->loadPlayerShipModules();
		}
		return $this->player_ship_modules;
	}

	/**
	 * Load PlayerShipModule list
	 *
	 * @return void
	 */
	private function loadPlayerShipModules(): void {
		$this->player_ship_modules = PlayerShipModule::where(['id_player_ship' => $this->id]);
	}

}
