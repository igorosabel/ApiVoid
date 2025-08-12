<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class SystemLink extends OModel {
	#[OPK(
		comment: 'PK'
	)]
	public ?int $id;

	#[OField(
		comment: 'Sistema A',
		nullable: false,
		default: 0,
		ref: 'system.id'
	)]
	public ?int $id_system_a;

	#[OField(
		comment: 'Sistema B',
		nullable: false,
		default: 0,
		ref: 'system.id'
	)]
	public ?int $id_system_b;

	#[OField(
		comment: 'Tiempo base (s)',
		nullable: false,
		default: 30
	)]
	public ?int $base_travel_time_sec;

	#[OCreatedAt(
		comment: 'Creación'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
		comment: 'Última actualización'
	)]
	public ?string $updated_at;


}
