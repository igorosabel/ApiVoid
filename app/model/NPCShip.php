<?php
class NPCShip extends OBase{
  function __construct(){
    $table_name  = 'npc_ship';
    $model = [
      'id_npc' => [
        'type'    => Base::PK,
        'incr' => false,
        'ref' => 'npc.id',
        'comment' => 'Id del NPC que hace la venta'
      ],
      'id_ship' => [
        'type'    => Base::PK,
        'incr' => false,
        'ref' => 'ship.id',
        'comment' => 'Id de la nave que vende'
      ],
      'start_value' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Cantidad inicial de naves que vende'
      ],
      'value' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Cantidad de naves que le quedan disponibles'
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