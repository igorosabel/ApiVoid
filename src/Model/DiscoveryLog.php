<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class DiscoveryLog extends OModel {
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
		comment: 'Fue el primero (1/0)',
		nullable: false,
		default: false
	)]
	public ?bool $first;

	#[OCreatedAt(
		comment: 'Creación'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
		comment: 'Última actualización'
	)]
	public ?string $updated_at;


}
