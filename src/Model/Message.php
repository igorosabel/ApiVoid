<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class Message extends OModel {
	#[OPK(
		comment: 'PK'
	)]
	public ?int $id;

	#[OField(
		comment: 'FK conversación',
		nullable: false,
		default: 0,
		ref: 'conversation.id'
	)]
	public ?int $id_conversation;

	#[OField(
		comment: 'FK remitente',
		nullable: false,
		default: 0,
		ref: 'player.id'
	)]
	public ?int $id_sender;

	#[OField(
		comment: 'Contenido',
		nullable: false,
		type: OField::LONGTEXT,
		default: null
	)]
	public ?string $content;

	#[OField(
		comment: 'Reportado (1/0)',
		nullable: false,
		default: false
	)]
	public ?bool $reported;

	#[OCreatedAt(
		comment: 'Creación'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
		comment: 'Última actualización'
	)]
	public ?string $updated_at;


}
