<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class BodyType extends OModel {
	#[OPK(
		comment: 'PK'
	)]
	public ?int $id;

	#[OField(
		comment: 'Code',
		nullable: false,
		max: 50,
		default: null
	)]
	public ?string $code;

	#[OField(
		comment: 'Nombre (i18n)',
		nullable: false,
		max: 100,
		default: null
	)]
	public ?string $name;

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
		$this->moons = Moon::where(['id_body_type' => $this->id]);
	}
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
		$this->planets = Planet::where(['id_body_type' => $this->id]);
	}

}
