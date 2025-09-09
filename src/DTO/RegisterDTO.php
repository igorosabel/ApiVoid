<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\DTO\ODTO;
use Osumi\OsumiFramework\DTO\ODTOField;

class RegisterDTO extends ODTO{
  #[ODTOField(required: true)]
  public ?string $email = null;

  #[ODTOField(required: true)]
  public ?string $nickname = null;

  #[ODTOField(required: true)]
  public ?string $password = null;

  #[ODTOField(required: true)]
  public ?bool $acceptTerms = null;
}
