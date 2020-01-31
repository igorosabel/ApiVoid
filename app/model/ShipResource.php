<?php
class ShipResource extends OBase{
  function __construct(){
    $table_name  = 'ship_resource';
    $model = [
      'id_ship' => [
        'type'    => Base::PK,
        'incr' => false,
        'ref' => 'ship.id',
        'comment' => 'Id de la nave que almacena los recursos'
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
        'comment' => 'Cantidad del recurso almacenado'
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