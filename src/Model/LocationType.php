<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class LocationType extends OModel {
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

	private ?array $discovery_logs = null;

	/**
	 * Save DiscoveryLog list
	 *
	 * @param array $discovery_logs DiscoveryLog list
	 *
	 * @return void
	 */
	public function setDiscoveryLogs(array $discovery_logs): void {
		$this->discovery_logs = $discovery_logs;
	}

	/**
	 * Get DiscoveryLog list
	 *
	 * @return array discovery_log list
	 */
	public function getDiscoveryLogs(): array {
		if (is_null($this->discovery_logs)) {
			$this->loadDiscoveryLogs();
		}
		return $this->discovery_logs;
	}

	/**
	 * Load DiscoveryLog list
	 *
	 * @return void
	 */
	private function loadDiscoveryLogs(): void {
		$this->discovery_logs = DiscoveryLog::where(['id_location_type' => $this->id]);
	}
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
		$this->location_resources = LocationResource::where(['id_location_type' => $this->id]);
	}
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
		$this->merchants = Merchant::where(['id_location_type' => $this->id]);
	}
	private ?array $rename_logs = null;

	/**
	 * Save RenameLog list
	 *
	 * @param array $rename_logs RenameLog list
	 *
	 * @return void
	 */
	public function setRenameLogs(array $rename_logs): void {
		$this->rename_logs = $rename_logs;
	}

	/**
	 * Get RenameLog list
	 *
	 * @return array rename_log list
	 */
	public function getRenameLogs(): array {
		if (is_null($this->rename_logs)) {
			$this->loadRenameLogs();
		}
		return $this->rename_logs;
	}

	/**
	 * Load RenameLog list
	 *
	 * @return void
	 */
	private function loadRenameLogs(): void {
		$this->rename_logs = RenameLog::where(['id_location_type' => $this->id]);
	}

}
