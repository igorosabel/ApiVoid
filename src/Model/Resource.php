<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class Resource extends OModel {
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
		comment: 'Masa (kg/u)',
		nullable: false,
		default: 1
	)]
	public ?float $mass_unit;

	#[OField(
		comment: 'Valor base',
		nullable: false,
		default: 0
	)]
	public ?float $base_value;

	#[OCreatedAt(
		comment: 'Creación'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
		comment: 'Última actualización'
	)]
	public ?string $updated_at;

	private ?array $location_resources = null;

	/**
	 * Save LocationResource list
	 *
	 * @param array $location_resources LocationResource list
	 *
	 * @return void
	 */
	public function setLocationResources(array $location_resources): void {
		$this->location_resources = $location_resources;
	}

	/**
	 * Get LocationResource list
	 *
	 * @return array location_resource list
	 */
	public function getLocationResources(): array {
		if (is_null($this->location_resources)) {
			$this->loadLocationResources();
		}
		return $this->location_resources;
	}

	/**
	 * Load LocationResource list
	 *
	 * @return void
	 */
	private function loadLocationResources(): void {
		$this->location_resources = LocationResource::where(['id_resource' => $this->id]);
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
		$this->merchant_stock_resources = MerchantStockResource::where(['id_resource' => $this->id]);
	}
	private ?array $player_resources = null;

	/**
	 * Save PlayerResource list
	 *
	 * @param array $player_resources PlayerResource list
	 *
	 * @return void
	 */
	public function setPlayerResources(array $player_resources): void {
		$this->player_resources = $player_resources;
	}

	/**
	 * Get PlayerResource list
	 *
	 * @return array player_resource list
	 */
	public function getPlayerResources(): array {
		if (is_null($this->player_resources)) {
			$this->loadPlayerResources();
		}
		return $this->player_resources;
	}

	/**
	 * Load PlayerResource list
	 *
	 * @return void
	 */
	private function loadPlayerResources(): void {
		$this->player_resources = PlayerResource::where(['id_resource' => $this->id]);
	}

}
