<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class ShipClass extends OModel {
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

	private ?array $ship_types = null;

	/**
	 * Save ShipType list
	 *
	 * @param array $ship_types ShipType list
	 *
	 * @return void
	 */
	public function setShipTypes(array $ship_types): void {
		$this->ship_types = $ship_types;
	}

	/**
	 * Get ShipType list
	 *
	 * @return array ship_type list
	 */
	public function getShipTypes(): array {
		if (is_null($this->ship_types)) {
			$this->loadShipTypes();
		}
		return $this->ship_types;
	}

	/**
	 * Load ShipType list
	 *
	 * @return void
	 */
	private function loadShipTypes(): void {
		$this->ship_types = ShipType::where(['id_ship_class' => $this->id]);
	}

}
