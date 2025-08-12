<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class CatalogModule extends OModel {
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
		comment: 'FK module_type',
		nullable: false,
		default: 0,
		ref: 'module_type.id'
	)]
	public ?int $id_module_type;

	#[OField(
		comment: 'FK module_rarity',
		nullable: false,
		default: 0,
		ref: 'module_rarity.id'
	)]
	public ?int $id_module_rarity;

	#[OField(
		comment: 'Slots que ocupa',
		nullable: false,
		default: 0
	)]
	public ?int $size_slots;

	#[OField(
		comment: 'Multiplicador de velocidad',
		nullable: true,
		default: null
	)]
	public ?float $speed_multiplier;

	#[OField(
		comment: 'Puntos de escudo',
		nullable: true,
		default: null
	)]
	public ?int $shield_points;

	#[OField(
		comment: 'Bonus carga (kg)',
		nullable: true,
		default: null
	)]
	public ?int $cargo_bonus_kg;

	#[OField(
		comment: 'Puntos de ataque',
		nullable: true,
		default: null
	)]
	public ?int $attack_points;

	#[OField(
		comment: 'Precio',
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
		$this->merchant_stock_modules = MerchantStockModule::where(['id_module' => $this->id]);
	}
	private ?array $player_modules = null;

	/**
	 * Save PlayerModule list
	 *
	 * @param array $player_modules PlayerModule list
	 *
	 * @return void
	 */
	public function setPlayerModules(array $player_modules): void {
		$this->player_modules = $player_modules;
	}

	/**
	 * Get PlayerModule list
	 *
	 * @return array player_module list
	 */
	public function getPlayerModules(): array {
		if (is_null($this->player_modules)) {
			$this->loadPlayerModules();
		}
		return $this->player_modules;
	}

	/**
	 * Load PlayerModule list
	 *
	 * @return void
	 */
	private function loadPlayerModules(): void {
		$this->player_modules = PlayerModule::where(['id_module' => $this->id]);
	}
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
		$this->player_ship_modules = PlayerShipModule::where(['id_module' => $this->id]);
	}

}
