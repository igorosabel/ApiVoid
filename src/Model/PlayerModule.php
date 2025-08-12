<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class PlayerModule extends OModel {
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
		comment: 'FK catalog_module',
		nullable: false,
		default: 0,
		ref: 'catalog_module.id'
	)]
	public ?int $id_module;

	#[OField(
		comment: 'Cantidad',
		nullable: false,
		default: 1
	)]
	public ?int $qty;

	#[OCreatedAt(
		comment: 'Creación'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
		comment: 'Última actualización'
	)]
	public ?string $updated_at;


}
