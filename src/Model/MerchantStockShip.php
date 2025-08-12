<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class MerchantStockShip extends OModel {
	#[OPK(
		comment: 'PK'
	)]
	public ?int $id;

	#[OField(
		comment: 'FK merchant',
		nullable: false,
		default: 0,
		ref: 'merchant.id'
	)]
	public ?int $id_merchant;

	#[OField(
		comment: 'FK ship_type',
		nullable: false,
		default: 0,
		ref: 'ship_type.id'
	)]
	public ?int $id_ship_type;

	#[OField(
		comment: 'Precio unitario',
		nullable: false,
		default: 0
	)]
	public ?float $price;

	#[OField(
		comment: 'Cantidad disponible',
		nullable: false,
		default: 0
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
