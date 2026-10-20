<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class PlayerPlanetDiscovery extends OModel {
	#[OPK(
		comment: 'PK'
	)]
	public ?int $id;

  #[OField(
		comment: 'FK a player.id',
		nullable: false,
		ref: 'player.id'
	)]
	public ?int $id_player;

  #[OField(
		comment: 'FK a planet.id',
		nullable: false,
		ref: 'planet.id'
	)]
	public ?int $id_planet;

  #[OField(
		comment: 'Fecha/hora del descubrimiento',
		nullable: false,
		type: OField::DATE,
	)]
	public ?string $discovered_at;

	#[OCreatedAt(
		comment: 'Creación'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
		comment: 'Última actualización'
	)]
	public ?string $updated_at;
}
