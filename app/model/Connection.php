<?php
class Connection extends OBase{
  function __construct(){
    $table_name  = 'connection';
    $model = [
      'id' => [
        'type'    => Base::PK,
        'comment' => 'Id única de cada conexión'
      ],
      'id_system_start' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'ref' => 'system.id',
        'comment' => 'Id del sistema del que se parte'
      ],
      'id_system_end' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => null,
        'ref' => 'system.id',
        'comment' => 'Id del sistema destino o null si todavía no se ha investigado'
      ],
      'order' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Orden de la conexión entre las que tiene un sistema'
      ],
      'navigate_time' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Tiempo que se tarda en navegar al sistema destino'
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