<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class System extends OModel {
	#[OPK(
		comment: 'PK'
	)]
	public ?int $id;

	#[OField(
		comment: 'FK system_type',
		nullable: false,
		default: 0,
		ref: 'system_type.id'
	)]
	public ?int $id_system_type;

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
		comment: 'FK star_class',
		nullable: false,
		default: 0,
		ref: 'star_class.id'
	)]
	public ?int $id_star_class;

	#[OField(
		comment: 'Semilla procedural',
		nullable: false,
		default: 0
	)]
	public ?int $seed;

	#[OCreatedAt(
		comment: 'Creación'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
		comment: 'Última actualización'
	)]
	public ?string $updated_at;

	private ?array $planets = null;

	/**
	 * Save Planet list
	 *
	 * @param array $planets Planet list
	 *
	 * @return void
	 */
	public function setPlanets(array $planets): void {
		$this->planets = $planets;
	}

	/**
	 * Get Planet list
	 *
	 * @return array planet list
	 */
	public function getPlanets(): array {
		if (is_null($this->planets)) {
			$this->loadPlanets();
		}
		return $this->planets;
	}

	/**
	 * Load Planet list
	 *
	 * @return void
	 */
	private function loadPlanets(): void {
		$this->planets = Planet::where(['id_system' => $this->id]);
	}
	private ?array $player_presences = null;

	/**
	 * Save PlayerPresence list
	 *
	 * @param array $player_presences PlayerPresence list
	 *
	 * @return void
	 */
	public function setPlayerPresences(array $player_presences): void {
		$this->player_presences = $player_presences;
	}

	/**
	 * Get PlayerPresence list
	 *
	 * @return array player_presence list
	 */
	public function getPlayerPresences(): array {
		if (is_null($this->player_presences)) {
			$this->loadPlayerPresences();
		}
		return $this->player_presences;
	}

	/**
	 * Load PlayerPresence list
	 *
	 * @return void
	 */
	private function loadPlayerPresences(): void {
		$this->player_presences = PlayerPresence::where(['id_system' => $this->id]);
	}
	private ?array $player_system_homes = null;

	/**
	 * Save PlayerSystemHome list
	 *
	 * @param array $player_system_homes PlayerSystemHome list
	 *
	 * @return void
	 */
	public function setPlayerSystemHomes(array $player_system_homes): void {
		$this->player_system_homes = $player_system_homes;
	}

	/**
	 * Get PlayerSystemHome list
	 *
	 * @return array player_system_home list
	 */
	public function getPlayerSystemHomes(): array {
		if (is_null($this->player_system_homes)) {
			$this->loadPlayerSystemHomes();
		}
		return $this->player_system_homes;
	}

	/**
	 * Load PlayerSystemHome list
	 *
	 * @return void
	 */
	private function loadPlayerSystemHomes(): void {
		$this->player_system_homes = PlayerSystemHome::where(['id_system' => $this->id]);
	}

	private ?array $system_links = null;

	/**
	 * Save SystemLink list
	 *
	 * @param array $system_links SystemLink list
	 *
	 * @return void
	 */
	public function setSystemLinks(array $system_links): void {
		$this->system_links = $system_links;
	}

	/**
	 * Get SystemLink list
	 *
	 * @return array system_link list
	 */
	public function getSystemLinks(): array {
		if (is_null($this->system_links)) {
			$this->loadSystemLinks();
		}
		return $this->system_links;
	}

	/**
	 * Load SystemLink list
	 *
	 * @return void
	 */
	private function loadSystemLinks(): void {
		$this->system_links = SystemLink::where(['id_system_a' => $this->id]);
	}
}
