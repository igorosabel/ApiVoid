<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class ModuleType extends OModel {
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

	private ?array $catalog_modules = null;

	/**
	 * Save CatalogModule list
	 *
	 * @param array $catalog_modules CatalogModule list
	 *
	 * @return void
	 */
	public function setCatalogModules(array $catalog_modules): void {
		$this->catalog_modules = $catalog_modules;
	}

	/**
	 * Get CatalogModule list
	 *
	 * @return array catalog_module list
	 */
	public function getCatalogModules(): array {
		if (is_null($this->catalog_modules)) {
			$this->loadCatalogModules();
		}
		return $this->catalog_modules;
	}

	/**
	 * Load CatalogModule list
	 *
	 * @return void
	 */
	private function loadCatalogModules(): void {
		$this->catalog_modules = CatalogModule::where(['id_module_type' => $this->id]);
	}

}
