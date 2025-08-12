<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class JobStatus extends OModel {
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

	private ?array $jobs = null;

	/**
	 * Save Job list
	 *
	 * @param array $jobs Job list
	 *
	 * @return void
	 */
	public function setJobs(array $jobs): void {
		$this->jobs = $jobs;
	}

	/**
	 * Get Job list
	 *
	 * @return array job list
	 */
	public function getJobs(): array {
		if (is_null($this->jobs)) {
			$this->loadJobs();
		}
		return $this->jobs;
	}

	/**
	 * Load Job list
	 *
	 * @return void
	 */
	private function loadJobs(): void {
		$this->jobs = Job::where(['id_job_status' => $this->id]);
	}

}
