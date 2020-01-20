<?php
class Planet extends OBase{
  function __construct(){
    $table_name  = 'planet';
    $model = [
      'id' => [
        'type'    => Base::PK,
        'comment' => 'Id único del planeta'
      ],
      'id_system' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'ref' => 'system.id',
        'comment' => 'Id del sistema al que pertenece el planeta'
      ],
      'original_name' => [
        'type'    => Base::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 50,
        'comment' => 'Nombre original del planeta'
      ],
      'name' => [
        'type'    => Base::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 50,
        'comment' => 'Nombre actual del planeta'
      ],
      'type' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Tipo de planeta'
      ],
      'radius' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Radio del planeta'
      ],
      'rotation' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Velocidad de rotación del planeta alrededor del sol'
      ],
      'distance' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Distancia del planeta a su sol'
      ],
      'num_moons' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => '0',
        'comment' => 'Número de lunas que tiene el planeta'
      ],
      'explored' => [
        'type'    => Base::BOOL,
        'comment' => 'Indica si el planeta ha sido explorado 1 o no 0'
      ],
      'explore_time' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Tiempo necesario para explorar el planeta'
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