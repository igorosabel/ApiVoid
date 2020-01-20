<?php
class ShipCrew extends OBase{
  function __construct(){
    $table_name  = 'ship_crew';
    $model = [
      'id_ship' => [
        'type'    => Base::PK,
        'incr' => false,
        'ref' => 'ship.id',
        'comment' => 'Id de la nave'
      ],
      'id_crew' => [
        'type'    => Base::PK,
        'incr' => false,
        'ref' => 'crew.id',
        'comment' => 'Id del tripulante'
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