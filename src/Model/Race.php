<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class Race extends OModel {
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

	#[OField(
		comment: 'Descripción',
		nullable: true,
		type: OField::LONGTEXT,
		default: null
	)]
	public ?string $description;

	#[OCreatedAt(
		comment: 'Creación'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
		comment: 'Última actualización'
	)]
	public ?string $updated_at;

	private ?array $merchants = null;

	/**
	 * Save Merchant list
	 *
	 * @param array $merchants Merchant list
	 *
	 * @return void
	 */
	public function setMerchants(array $merchants): void {
		$this->merchants = $merchants;
	}

	/**
	 * Get Merchant list
	 *
	 * @return array merchant list
	 */
	public function getMerchants(): array {
		if (is_null($this->merchants)) {
			$this->loadMerchants();
		}
		return $this->merchants;
	}

	/**
	 * Load Merchant list
	 *
	 * @return void
	 */
	private function loadMerchants(): void {
		$this->merchants = Merchant::where(['id_race' => $this->id]);
	}

}
