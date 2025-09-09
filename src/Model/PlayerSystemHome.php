<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class PlayerSystemHome extends OModel {
	#[OPK(
		comment: 'PK = jugador',
		ref: 'player.id',
		incr: false
	)]
	public ?int $id_player;

	#[OField(
		comment: 'Sistema inicial',
		nullable: false,
		default: 0,
		ref: 'system.id'
	)]
	public ?int $id_system;

	#[OCreatedAt(
		comment: 'Creación'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
		comment: 'Última actualización'
	)]
	public ?string $updated_at;


}
