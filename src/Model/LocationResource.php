<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class LocationResource extends OModel {
	#[OPK(
		comment: 'PK'
	)]
	public ?int $id;

	#[OField(
		comment: 'FK location_type',
		nullable: false,
		default: 0,
		ref: 'location_type.id'
	)]
	public ?int $id_location_type;

	#[OField(
		comment: 'ID de la localización',
		nullable: false,
		default: 0
	)]
	public ?int $id_location;

	#[OField(
		comment: 'FK resource',
		nullable: false,
		default: 0,
		ref: 'resource.id'
	)]
	public ?int $id_resource;

	#[OField(
		comment: 'Capacidad máx',
		nullable: false,
		default: 0
	)]
	public ?int $max_capacity;

	#[OField(
		comment: 'Capacidad actual',
		nullable: false,
		default: 0
	)]
	public ?int $current_capacity;

	#[OField(
		comment: 'Último agotado',
		nullable: true,
		type: OField::DATE,
		default: null
	)]
	public ?string $last_depleted_at;

	#[OCreatedAt(
		comment: 'Creación'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
		comment: 'Última actualización'
	)]
	public ?string $updated_at;


}
