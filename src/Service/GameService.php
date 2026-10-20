<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Service;

use Osumi\OsumiFramework\Core\OService;
use Osumi\OsumiFramework\ORM\ODB;

class GameService extends OService {
  /**
   * Obtiene el sistema actual del jugador desde presencia o su "home".
   *
   * @param int $id_player Id del jugador
   * @return int|null Id del sistema o null si no existe
   */
  public function getCurrentSystemId(int $id_player): ?int {
    $db = new ODB();

    $db->query(
      'SELECT `id_system` FROM `player_presence` WHERE `id_player` = :p LIMIT 1',
      [':p' => $id_player]
    );
    if ($db->count() === 0) {
      $db->query(
        'SELECT `id_system` FROM `player_system_home` WHERE `id_player` = :p LIMIT 1',
        [':p' => $id_player]
      );
    }
    if ($db->count() === 0) {
      return null;
    }
    $row = $db->next();
    return isset($row['id_system']) ? (int) $row['id_system'] : null;
  }

  /**
   * Lee información resumida del sistema, nº de planetas y de lunas totales.
   *
   * @param int $id_system Id del sistema
   * @return array<string, mixed>|null Resumen del sistema o null si no existe
   */
  public function getSystemSummary(int $id_system): ?array {
    $db = new ODB();

    // Datos base del sistema + clase estelar
    $db->query(
      'SELECT s.`id`,
              s.`original_name`,
              s.`custom_name`,
              sc.`code` AS `star_class_code`
         FROM `system` s
         JOIN `star_class` sc ON sc.`id` = s.`id_star_class`
        WHERE s.`id` = :s
        LIMIT 1',
      [':s' => $id_system]
    );
    if ($db->count() === 0) return null;
    $row = $db->next();

    // Contadores (planetas / lunas)
    $db->query('SELECT COUNT(*) AS `c` FROM `planet` WHERE `id_system` = :s', [':s' => $id_system]);
    $planets_count = (int) ($db->next()['c'] ?? 0);

    $db->query(
      'SELECT COUNT(*) AS `c`
         FROM `moon` m
         JOIN `planet` p ON p.`id` = m.`id_planet`
        WHERE p.`id_system` = :s',
      [':s' => $id_system]
    );
    $moons_total = (int) ($db->next()['c'] ?? 0);

    return [
      'id'               => (int) $row['id'],
      'original_name'    => (string) $row['original_name'],
      'custom_name_json' => is_null($row['custom_name']) ? 'null' : json_encode((string) $row['custom_name'], JSON_UNESCAPED_UNICODE),
      'star_class_code'  => (string) $row['star_class_code'],
      'planets_count'    => $planets_count,
      'moons_total'      => $moons_total
    ];
  }

  /**
   * Lista jugadores presentes en el sistema actual.
   *
   * @param int $id_system Id del sistema
   * @param int $id_player Id del jugador actual (para excluirlo si se desea)
   * @return array<int, array{id:int,nickname:string}>
   */
  public function getPlayersInSystem(int $id_system, int $id_player): array {
    $db = new ODB();

    $db->query(
      'SELECT p.`id`, p.`nickname`
         FROM `player_presence` pp
         JOIN `player` p ON p.`id` = pp.`id_player`
        WHERE pp.`id_system` = :s
        ORDER BY p.`nickname` ASC',
      [':s' => $id_system]
    );
    $out = [];
    while ($row = $db->next()) {
      if ((int)$row['id'] === $id_player) { continue; }
      $out[] = [
        'id'       => (int) $row['id'],
        'nickname' => (string) $row['nickname']
      ];
    }
    return $out;
  }

  /**
   * Lista planetas del sistema con su tipo y número de lunas y si el jugador los descubrió.
   *
   * @param int $id_system Id del sistema
   * @param int $id_player Id del jugador
   * @return array<int, array{id:int,name:string,body_type_code:string,moons_count:int,is_discovered:bool}>
   */
  public function getPlanetsOfSystem(int $id_system, int $id_player): array {
    $db = new ODB();

    $db->query(
      'SELECT p.`id`,
              COALESCE(p.`custom_name`, p.`original_name`) AS `name`,
              bt.`code` AS `body_type_code`,
              (SELECT COUNT(*) FROM `moon` m WHERE m.`id_planet` = p.`id`) AS `moons_count`,
              (SELECT 1 FROM `player_planet_discovery` ppd
                WHERE ppd.`id_player` = :p AND ppd.`id_planet` = p.`id`
                LIMIT 1) AS `discovered`
         FROM `planet` p
         JOIN `body_type` bt ON bt.`id` = p.`id_body_type`
        WHERE p.`id_system` = :s
        ORDER BY p.`id` ASC',
      [':s' => $id_system, ':p' => $id_player]
    );

    $out = [];
    while ($row = $db->next()) {
      $out[] = [
        'id'             => (int) $row['id'],
        'name'           => (string) $row['name'],
        'body_type_code' => (string) $row['body_type_code'],
        'moons_count'    => (int) $row['moons_count'],
        'is_discovered'  => !is_null($row['discovered'])
      ];
    }
    return $out;
  }

  /**
   * Lista comerciantes (NPCs) del sistema, VISIBLES para el jugador:
   * - Si el NPC está en un planeta, sólo si ese planeta fue descubierto por el jugador.
   * - Si está en una luna, sólo si esa luna fue descubierta por el jugador.
   * - Si no tiene planeta ni luna (nivel sistema), por ahora NO se muestran (ajustable según tu diseño).
   *
   * @param int $id_system Id del sistema
   * @param int $id_player Id del jugador (para filtrar descubrimientos)
   * @return array<int, array{id:int,name:string,race_code:string,trades:array<int,array<string,string>>}>
   */
  public function getNpcsOfSystem(int $id_system, int $id_player): array {
    $db = new ODB();

    $db->query(
      'SELECT m.`id`,
              m.`name`,
              r.`code` AS `race_code`
         FROM `merchant` m
         JOIN `race` r ON r.`id` = m.`id_race`
         LEFT JOIN `player_planet_discovery` ppd
                ON ppd.`id_player` = :p1 AND ppd.`id_planet` = m.`id_planet`
         LEFT JOIN `player_moon_discovery` pmd
                ON pmd.`id_player` = :p2 AND pmd.`id_moon`   = m.`id_moon`
        WHERE m.`id_system` = :s
          AND (
                (m.`id_planet` IS NOT NULL AND ppd.`id` IS NOT NULL)
             OR (m.`id_moon`   IS NOT NULL AND pmd.`id` IS NOT NULL)
          )
        ORDER BY m.`name` ASC',
      [':s' => $id_system, ':p1' => $id_player, ':p2' => $id_player]
    );

    $out = [];
    while ($row = $db->next()) {
      $out[] = [
        'id'        => (int) $row['id'],
        'name'      => (string) $row['name'],
        'race_code' => (string) $row['race_code'],
        'trades'    => [] // ampliar con stock si quieres
      ];
    }
    return $out;
  }
}
