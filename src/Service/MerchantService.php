<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Service;

use Osumi\OsumiFramework\Core\OService;

/**
 * Genera nombres reproducibles de comerciantes (NPC) a partir de una semilla y la raza.
 */
class MerchantService extends OService {

  /**
   * Genera un nombre de comerciante determinista.
   *
   * @param string $race_code Código de raza (p.ej. 'HUMAN', 'ZAR_KUUN', 'AURIS', 'SYNT')
   * @param int $seed Semilla base (guardada en merchant.seed)
   * @return string Nombre generado
   */
  public function generateMerchantName(string $race_code, int $seed): string {
    $race = strtoupper($race_code);
    return match ($race) {
      'HUMAN'         => $this->humanName($seed),
      'SYNT', 'ROBOT' => $this->syntName($seed),
      'ZAR_KUUN'      => $this->alienHardName($seed),   // gutural, con apóstrofes
      'AURIS'         => $this->alienSoftName($seed),   // vocálico, suave
      default         => $this->genericName($seed)
    };
  }

  /**
   * Genera un entero en [min, max] a partir de seed e índice.
   * No usa RNG global; es puro y reproducible.
   *
   * @param int $seed Semilla base
   * @param int $i Índice de iteración (para obtener distintos valores)
   * @param int $min Límite inferior
   * @param int $max Límite superior
   * @return int Número pseudoaleatorio en rango
   */
  private function randRange(int $seed, int $i, int $min, int $max): int {
    $h = hash('sha256', $seed . ':' . $i, true);
    $val = unpack('N', substr($h, 0, 4))[1]; // 32-bit
    $span = $max - $min + 1;
    return $min + ($val % $span);
  }

  /**
   * Selecciona un elemento de un array de forma determinista.
   *
   * @param int $seed Semilla
   * @param int $i Índice de paso
   * @param array<int, string> $list Lista de opciones
   * @return string Elemento seleccionado
   */
  private function pick(int $seed, int $i, array $list): string {
    $idx = $this->randRange($seed, $i, 0, count($list) - 1);
    return $list[$idx];
  }

  /** HUMAN: nombre + apellido de listas breves (pon más si quieres). */
  private function humanName(int $seed): string {
    $first = [
      'Armin','Lena','Kade','Nova','Iris','Mara','Jon','Vera','Axel','Dara',
      'Rhea','Kael','Tess','Orion','Luca','Nora','Milo','Elia','Seth','Ada'
    ];
    $last = [
      'Kane','Vega','Cross','Drake','Solace','Roth','Reyes','Quinn','Vale','Nyx',
      'Sato','Morgan','Hart','Voss','Nash','Kepler','Holt','Arden','Rayne','Black'
    ];
    return $this->pick($seed, 1, $first) . ' ' . $this->pick($seed, 2, $last);
  }

  /** SYNT/ROBOT: prefijo + 3 dígitos + sufijo alfa. */
  private function syntName(int $seed): string {
    $prefix = ['SX','NX','RX','QF','VL','OM','KZ','HT','AI','CY'];
    $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $d1 = $this->randRange($seed, 1, 0, 9);
    $d2 = $this->randRange($seed, 2, 0, 9);
    $d3 = $this->randRange($seed, 3, 0, 9);
    $l1 = $letters[$this->randRange($seed, 4, 0, 25)];
    $l2 = $letters[$this->randRange($seed, 5, 0, 25)];
    return $this->pick($seed, 0, $prefix) . '-' . $d1 . $d2 . $d3 . '-' . $l1 . $l2;
  }

  /** ALIEN duro (ZAR_KUUN): sílabas guturales + apóstrofe. */
  private function alienHardName(int $seed): string {
    $c1 = ['Zr','Kr','X','Q','Sk','Tr','Vr','Zh','Gr','K'];
    $v  = ['a','o','u','aa','au','oa'];
    $c2 = ['k','g','x','z','rk','q','sh','gz','kk','th'];
    $part1 = $this->pick($seed, 1, $c1) . $this->pick($seed, 2, $v) . $this->pick($seed, 3, $c2);
    $part2 = $this->pick($seed, 4, $c1) . $this->pick($seed, 5, $v);
    return $part1 . "'" . $part2;
  }

  /** ALIEN suave (AURIS): vocales/semivocales y sílabas fluidas. */
  private function alienSoftName(int $seed): string {
    $s1 = ['Ae','Ia','Eo','Ari','Ona','Ily','Yva','Ael','Lua','Nai'];
    $s2 = ['ria','lith','nora','thea','vion','lyra','mara','selle','thiel','lune'];
    return $this->pick($seed, 1, $s1) . $this->pick($seed, 2, $s2);
  }

  /** Fallback genérico: combinaciones CV-CV con control determinista. */
  private function genericName(int $seed): string {
    $c = ['b','d','g','k','l','m','n','r','s','t','v','z'];
    $v = ['a','e','i','o','u','ae','ia','ou'];
    $p1 = $this->pick($seed, 1, $c) . $this->pick($seed, 2, $v);
    $p2 = $this->pick($seed, 3, $c) . $this->pick($seed, 4, $v);
    // Capitalizar primera letra
    return ucfirst($p1 . $p2);
  }
}
