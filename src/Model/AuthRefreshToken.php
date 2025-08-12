<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class AuthRefreshToken extends OModel {
	#[OPK(
		comment: 'PK'
	)]
	public ?int $id;

	#[OField(
		comment: 'FK jugador',
		nullable: false,
		default: 0,
		ref: 'player.id'
	)]
	public ?int $id_player;

	#[OField(
		comment: 'Token de refresco',
		nullable: false,
		max: 64,
		default: null
	)]
	public ?string $token;

	#[OField(
		comment: 'Expiración',
		nullable: false,
		type: OField::DATE,
		default: null
	)]
	public ?string $expires_at;

	#[OField(
		comment: 'Revocado (1/0)',
		nullable: false,
		default: false
	)]
	public ?bool $revoked;

	#[OCreatedAt(
		comment: 'Creación'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
		comment: 'Última actualización'
	)]
	public ?string $updated_at;


}
