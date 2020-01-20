<?php
class Resource extends OBase{
  function __construct(){
    $table_name  = 'resource';
    $model = [
      'id_planet' => [
        'type'    => Base::PK,
        'incr' => false,
        'ref' => 'planet.id',
        'comment' => 'Id del planeta que contiene el recurso'
      ],
      'id_moon' => [
        'type'    => Base::PK,
        'incr' => false,
        'ref' => 'moon.id',
        'comment' => 'Id de la luna que contiene el recurso'
      ],
      'type' => [
        'type'    => Base::PK,
        'incr' => false,
        'comment' => 'Tipo de recurso'
      ],
      'value' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Cantidad del recurso'
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