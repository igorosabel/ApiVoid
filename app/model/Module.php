<?php
class Module extends OBase{
  const ENGINE = 1;
  const SHIELD = 2;
  const CARGO = 3;
  const GUN = 4;
  const CABIN = 5;
  const ENERGY = 6;

  function __construct(){
    $table_name  = 'module';
    $model = [
      'id' => [
        'type'    => Base::PK,
        'comment' => 'Id único de cada módulo'
      ],
      'id_player' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => null,
        'ref' => 'player.id',
        'comment' => 'Id del jugador dueño del módulo o null si lo vende un NPC'
      ],
      'id_npc' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => null,
        'ref' => 'npc.id',
        'comment' => 'Id del NPC que vende el módulo o null si es de un jugador'
      ],
      'id_ship' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => null,
        'ref' => 'ship.id',
        'comment' => 'Id de la nave en la que está equipado o null si no está equipado'
      ],
      'name' => [
        'type'    => Base::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 100,
        'comment' => 'Nombre descriptivo del módulo'
      ],
      'id_type' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Tipo de módulo'
      ],
      'engine' => [
        'type'    => Base::FLOAT,
        'nullable' => true,
        'default' => null,
        'comment' => 'Multiplicador de velocidad en caso de ser un módulo de motor'
      ],
      'shield' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => null,
        'comment' => 'Puntos de escudo que aumenta el módulo en caso de ser un módulo de escudo'
      ],
      'cargo' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => null,
        'comment' => 'Capacidad de carga en caso de ser un módulo de carga'
      ],
      'damage' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => null,
        'comment' => 'Puntos de daño en caso de ser un módulo de arma'
      ],
      'crew' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => null,
        'comment' => 'Cantidad de tripulantes en caso de ser un módulo de cabinas'
      ],
      'energy' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Puntos de energía que consume el módulo o produce en caso de ser un módulo de energía'
      ],
      'slots' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Número de huecos que ocupa el módulo en la nave'
      ],
      'credits' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => null,
        'comment' => 'Cantidad de créditos que cuesta el módulo'
      ],
      'created_at' => [
        'type'    => Base::CREATED,
        'comment' => 'Fecha de creación del registro'
      ],
      'updated_at' => [
        'type'    => Base::UPDATED,
        'nullable' => true,
        'default' => null,
        'comment' => 'Fecha de última modificación del registro'
      ]
    ];

    parent::load($table_name, $model);
  }
}