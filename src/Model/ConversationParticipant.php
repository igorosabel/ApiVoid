<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class ConversationParticipant extends OModel {
	#[OPK(
		comment: 'FK conversación',
		ref: 'conversation.id',
		incr: false
	)]
	public ?int $id_conversation;

	#[OPK(
		comment: 'FK jugador',
		ref: 'player.id',
		incr: false
	)]
	public ?int $id_player;

	#[OField(
		comment: 'Bloqueado (1/0)',
		nullable: false,
		default: false
	)]
	public ?bool $blocked;

	#[OField(
		comment: 'Oculto para el usuario',
		nullable: false,
		default: false
	)]
	public ?bool $deleted;

	#[OCreatedAt(
		comment: 'Creación'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
		comment: 'Última actualización'
	)]
	public ?string $updated_at;


}
