<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class RenameLog extends OModel {
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
		comment: 'Nombre anterior',
		nullable: false,
		max: 100,
		default: null
	)]
	public ?string $old_name;

	#[OField(
		comment: 'Nombre nuevo',
		nullable: false,
		max: 100,
		default: null
	)]
	public ?string $new_name;

	#[OCreatedAt(
		comment: 'Creación'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
		comment: 'Última actualización'
	)]
	public ?string $updated_at;


}
