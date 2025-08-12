<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class ShipType extends OModel {
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
		comment: 'FK ship_class',
		nullable: false,
		default: 0,
		ref: 'ship_class.id'
	)]
	public ?int $id_ship_class;

	#[OField(
		comment: 'HP base',
		nullable: false,
		default: 0
	)]
	public ?int $hp_base;

	#[OField(
		comment: 'Resistencia base',
		nullable: false,
		default: 0
	)]
	public ?int $resistance_base;

	#[OField(
		comment: 'Slots de módulos',
		nullable: false,
		default: 0
	)]
	public ?int $module_slots;

	#[OField(
		comment: 'Carga base (kg)',
		nullable: false,
		default: 0
	)]
	public ?int $cargo_base_kg;

	#[OField(
		comment: 'Velocidad base (km/s)',
		nullable: false,
		default: 0
	)]
	public ?float $base_speed_kms;

	#[OField(
		comment: 'Precio base',
		nullable: false,
		default: 0
	)]
	public ?float $price;

	#[OCreatedAt(
		comment: 'Creación'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
		comment: 'Última actualización'
	)]
	public ?string $updated_at;

	private ?array $merchant_stock_ships = null;

	/**
	 * Save MerchantStockShip list
	 *
	 * @param array $merchant_stock_ships MerchantStockShip list
	 *
	 * @return void
	 */
	public function setMerchantStockShips(array $merchant_stock_ships): void {
		$this->merchant_stock_ships = $merchant_stock_ships;
	}

	/**
	 * Get MerchantStockShip list
	 *
	 * @return array merchant_stock_ship list
	 */
	public function getMerchantStockShips(): array {
		if (is_null($this->merchant_stock_ships)) {
			$this->loadMerchantStockShips();
		}
		return $this->merchant_stock_ships;
	}

	/**
	 * Load MerchantStockShip list
	 *
	 * @return void
	 */
	private function loadMerchantStockShips(): void {
		$this->merchant_stock_ships = MerchantStockShip::where(['id_ship_type' => $this->id]);
	}
	private ?array $player_ships = null;

	/**
	 * Save PlayerShip list
	 *
	 * @param array $player_ships PlayerShip list
	 *
	 * @return void
	 */
	public function setPlayerShips(array $player_ships): void {
		$this->player_ships = $player_ships;
	}

	/**
	 * Get PlayerShip list
	 *
	 * @return array player_ship list
	 */
	public function getPlayerShips(): array {
		if (is_null($this->player_ships)) {
			$this->loadPlayerShips();
		}
		return $this->player_ships;
	}

	/**
	 * Load PlayerShip list
	 *
	 * @return void
	 */
	private function loadPlayerShips(): void {
		$this->player_ships = PlayerShip::where(['id_ship_type' => $this->id]);
	}

}
