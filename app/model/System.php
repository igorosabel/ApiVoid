<?php
class System extends OBase{
  function __construct(){
    $table_name  = 'system';
    $model = [
      'id' => [
        'type'    => Base::PK,
        'comment' => 'Id único de cada sistema'
      ],
      'id_player' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'ref' => 'player.id',
        'comment' => 'Id del jugador que descubre el sistema'
      ],
      'original_name' => [
        'type'    => Base::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 50,
        'comment' => 'Nombre original del sistema'
      ],
      'name' => [
        'type'    => Base::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 50,
        'comment' => 'Nombre actual del sistema'
      ],
      'num_planets' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => '0',
        'comment' => 'Número de planetas que tiene el sistema'
      ],
      'num_npc' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => '0',
        'comment' => 'Número de NPCs que hay en el sistema'
      ],
      'type' => [
        'type'    => Base::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 5,
        'comment' => 'Tipo de sol'
      ],
      'radius' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Radio del sol'
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