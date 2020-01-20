<?php
class Moon extends OBase{
  function __construct(){
    $table_name  = 'moon';
    $model = [
      'id' => [
        'type'    => Base::PK,
        'comment' => 'Id único de cada luna'
      ],
      'id_planet' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'ref' => 'planet.id',
        'comment' => 'Id del planeta al que pertenece la luna'
      ],
      'original_name' => [
        'type'    => Base::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 50,
        'comment' => 'Nombre original de la luna'
      ],
      'name' => [
        'type'    => Base::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 50,
        'comment' => 'Nombre actual de la luna'
      ],
      'radius' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Radio de la luna'
      ],
      'rotation' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Velocidad de rotación de la luna alrededor del planeta'
      ],
      'distance' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Distancia de la luna a su planeta'
      ],
      'explored' => [
        'type'    => Base::BOOL,
        'comment' => 'Indica si la luna ha sido explorada 1 o no 0'
      ],
      'explore_time' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Tiempo necesario para explorar la luna'
      ],
      'id_npc' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => null,
        'ref' => 'npc.id',
        'comment' => 'Id del NPC que habita el planeta o null si no tiene'
      ],
      'id_construction' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => null,
        'ref' => 'construction.id',
        'comment' => 'Id de la construcción que hay en el planeta o null si no tiene'
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