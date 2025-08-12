<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class TradeMode extends OModel {
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
		$this->merchant_stock_resources = MerchantStockResource::where(['id_trade_mode' => $this->id]);
	}

}
