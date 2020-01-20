<?php
class Ship extends OBase{
  function __construct(){
    $table_name  = 'ship';
    $model = [
      'id' => [
        'type'    => Base::PK,
        'comment' => 'Id único para cada nave'
      ],
      'id_player' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => null,
        'ref' => 'player.id',
        'comment' => 'Id del jugador dueño de la nave o null si la vende un NPC'
      ],
      'id_npc' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => null,
        'ref' => 'npc.id',
        'comment' => 'Id del NPC que vende la nave o null si es de un jugador'
      ],
      'original_name' => [
        'type'    => Base::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 50,
        'comment' => 'Nombre original de la nave'
      ],
      'name' => [
        'type'    => Base::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 50,
        'comment' => 'Nombre actual de la nave'
      ],
      'id_type' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Tipo de nave'
      ],
      'max_strength' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Puntos máximos de integridad de la nave'
      ],
      'strength' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Puntos de integridad actuales de la nave'
      ],
      'endurance' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Puntos de resistencia'
      ],
      'shield' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => null,
        'comment' => 'Puntos de escudo'
      ],
      'id_engine' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Tipo de motor que lleva la nave'
      ],
      'speed' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Velocidad base de la nave'
      ],
      'max_cargo' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Capacidad total de carga de la nave'
      ],
      'cargo' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Capacidad inicial de carga de la nave'
      ],
      'damage' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => null,
        'comment' => 'Daño total que puede hacer la nave'
      ],
      'id_generator' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Tipo de generador de energía'
      ],
      'max_energy' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Puntos de energía totales de la nave'
      ],
      'energy' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Puntos de energía iniciales de la nave'
      ],
      'slots' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Número de huecos disponibles para módulos'
      ],
      'crew' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => null,
        'comment' => 'Número de tripulantes que se pueden llevar en la nave'
      ],
      'credits' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => null,
        'comment' => 'Cantidad de créditos que cuesta la nave'
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