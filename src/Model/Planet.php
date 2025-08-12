<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class Planet extends OModel {
	#[OPK(
		comment: 'PK'
	)]
	public ?int $id;

	#[OField(
		comment: 'FK system',
		nullable: false,
		default: 0,
		ref: 'system.id'
	)]
	public ?int $id_system;

	#[OField(
		comment: 'Nombre generado',
		nullable: false,
		max: 50,
		default: null
	)]
	public ?string $original_name;

	#[OField(
		comment: 'Nombre renombrado',
		nullable: true,
		max: 100,
		default: null
	)]
	public ?string $custom_name;

	#[OField(
		comment: 'Jugador que renombró',
		nullable: true,
		default: null,
		ref: 'player.id'
	)]
	public ?int $id_player_renamed_by;

	#[OField(
		comment: 'FK body_type',
		nullable: false,
		default: 0,
		ref: 'body_type.id'
	)]
	public ?int $id_body_type;

	#[OField(
		comment: 'Radio (km)',
		nullable: false,
		default: 0
	)]
	public ?int $radius_km;

	#[OField(
		comment: 'Distancia al sol (UA)',
		nullable: false,
		default: 0
	)]
	public ?float $distance_au;

	#[OField(
		comment: 'Número de lunas',
		nullable: false,
		default: 0
	)]
	public ?int $moons_count;

	#[OField(
		comment: 'Semilla procedural',
		nullable: false,
		default: 0
	)]
	public ?int $seed;

	#[OField(
		comment: 'Descubierto global',
		nullable: false,
		default: false
	)]
	public ?bool $is_discovered;

	#[OField(
		comment: 'Jugador que lo descubrió primero',
		nullable: true,
		default: null,
		ref: 'player.id'
	)]
	public ?int $id_player_first_discoverer;

	#[OCreatedAt(
		comment: 'Creación'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
		comment: 'Última actualización'
	)]
	public ?string $updated_at;

	private ?array $moons = null;

	/**
	 * Save Moon list
	 *
	 * @param array $moons Moon list
	 *
	 * @return void
	 */
	public function setMoons(array $moons): void {
		$this->moons = $moons;
	}

	/**
	 * Get Moon list
	 *
	 * @return array moon list
	 */
	public function getMoons(): array {
		if (is_null($this->moons)) {
			$this->loadMoons();
		}
		return $this->moons;
	}

	/**
	 * Load Moon list
	 *
	 * @return void
	 */
	private function loadMoons(): void {
		$this->moons = Moon::where(['id_planet' => $this->id]);
	}

}
