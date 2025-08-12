<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class Job extends OModel {
	#[OPK(
		comment: 'PK'
	)]
	public ?int $id;

	#[OField(
		comment: 'FK player',
		nullable: false,
		default: 0,
		ref: 'player.id'
	)]
	public ?int $id_player;

	#[OField(
		comment: 'FK job_type',
		nullable: false,
		default: 0,
		ref: 'job_type.id'
	)]
	public ?int $id_job_type;

	#[OField(
		comment: 'FK job_status',
		nullable: false,
		default: 0,
		ref: 'job_status.id'
	)]
	public ?int $id_job_status;

	#[OField(
		comment: 'Datos del trabajo',
		nullable: false,
		type: OField::LONGTEXT,
		default: null
	)]
	public ?string $payload;

	#[OField(
		comment: 'Inicio',
		nullable: false,
		type: OField::DATE,
		default: null
	)]
	public ?string $started_at;

	#[OField(
		comment: 'Fin previsto',
		nullable: false,
		type: OField::DATE,
		default: null
	)]
	public ?string $ends_at;

	#[OCreatedAt(
		comment: 'Creación'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
		comment: 'Última actualización'
	)]
	public ?string $updated_at;


}
