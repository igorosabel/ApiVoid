<?php
class ShipResource extends OModel{
  function __construct(){
    $table_name  = 'ship_resource';
    $model = [
      'id_ship' => [
        'type'    => OCore::PK,
        'incr' => false,
        'ref' => 'ship.id',
        'comment' => 'Id de la nave que almacena los recursos'
      ],
      'type' => [
        'type'    => OCore::PK,
        'incr' => false,
        'comment' => 'Tipo de recurso'
      ],
      'value' => [
        'type'    => OCore::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Cantidad del recurso almacenado'
      ],
      'created_at' => [
        'type'    => OCore::CREATED,
        'comment' => 'Fecha de creación del registro'
      ],
      'updated_at' => [
        'type'    => OCore::UPDATED,
        'nullable' => true,
        'default' => null,
        'comment' => 'Fecha de última modificación del registro'
      ]
    ];

    parent::load($table_name, $model);
  }
}