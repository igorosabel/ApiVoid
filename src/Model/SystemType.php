<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class SystemType extends OModel {
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

	private ?array $systems = null;

	/**
	 * Save System list
	 *
	 * @param array $systems System list
	 *
	 * @return void
	 */
	public function setSystems(array $systems): void {
		$this->systems = $systems;
	}

	/**
	 * Get System list
	 *
	 * @return array system list
	 */
	public function getSystems(): array {
		if (is_null($this->systems)) {
			$this->loadSystems();
		}
		return $this->systems;
	}

	/**
	 * Load System list
	 *
	 * @return void
	 */
	private function loadSystems(): void {
		$this->systems = System::where(['id_system_type' => $this->id]);
	}

}
