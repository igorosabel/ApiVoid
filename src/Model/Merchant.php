<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class Merchant extends OModel {
	#[OPK(
		comment: 'PK'
	)]
	public ?int $id;

	#[OField(
		comment: 'FK race',
		nullable: false,
		default: 0,
		ref: 'race.id'
	)]
	public ?int $id_race;

	#[OField(
		comment: 'FK location_type',
		nullable: false,
		default: 0,
		ref: 'location_type.id'
	)]
	public ?int $id_location_type;

	#[OField(
		comment: 'ID localización',
		nullable: false,
		default: 0
	)]
	public ?int $id_location;

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

	private ?array $merchant_stock_modules = null;

	/**
	 * Save MerchantStockModule list
	 *
	 * @param array $merchant_stock_modules MerchantStockModule list
	 *
	 * @return void
	 */
	public function setMerchantStockModules(array $merchant_stock_modules): void {
		$this->merchant_stock_modules = $merchant_stock_modules;
	}

	/**
	 * Get MerchantStockModule list
	 *
	 * @return array merchant_stock_module list
	 */
	public function getMerchantStockModules(): array {
		if (is_null($this->merchant_stock_modules)) {
			$this->loadMerchantStockModules();
		}
		return $this->merchant_stock_modules;
	}

	/**
	 * Load MerchantStockModule list
	 *
	 * @return void
	 */
	private function loadMerchantStockModules(): void {
		$this->merchant_stock_modules = MerchantStockModule::where(['id_merchant' => $this->id]);
	}
	private ?array $merchant_stock_resources = null;

	/**
	 * Save MerchantStockResource list
	 *
	 * @param array $merchant_stock_resources MerchantStockResource list
	 *
	 * @return void
	 */
	public function setMerchantStockResources(array $merchant_stock_resources): void {
		$this->merchant_stock_resources = $merchant_stock_resources;
	}

	/**
	 * Get MerchantStockResource list
	 *
	 * @return array merchant_stock_resource list
	 */
	public function getMerchantStockResources(): array {
		if (is_null($this->merchant_stock_resources)) {
			$this->loadMerchantStockResources();
		}
		return $this->merchant_stock_resources;
	}

	/**
	 * Load MerchantStockResource list
	 *
	 * @return void
	 */
	private function loadMerchantStockResources(): void {
		$this->merchant_stock_resources = MerchantStockResource::where(['id_merchant' => $this->id]);
	}
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
		$this->merchant_stock_ships = MerchantStockShip::where(['id_merchant' => $this->id]);
	}

}
