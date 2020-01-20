<?php
class Player extends OBase{
  function __construct(){
    $table_name  = 'player';
    $model = [
      'id' => [
        'type'    => Base::PK,
        'comment' => 'Id único del jugador'
      ],
      'name' => [
        'type'    => Base::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 50,
        'comment' => 'Nombre de usuario del jugador'
      ],
      'pass' => [
        'type'    => Base::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 120,
        'comment' => 'Contraseña cifrada del jugador'
      ],
      'credits' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Cantidad de créditos que posee el jugador'
      ],
      'id_ship' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'ref' => 'ship.id',
        'comment' => 'Id de la nave que actualmente pilota el jugador'
      ],
      'id_system' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'ref' => 'system.id',
        'comment' => 'Id del sistema en el que se encuentra el jugador'
      ],
      'id_job' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => null,
        'ref' => 'job.id',
        'comment' => 'Id de la tarea que está desempeñando el jugador'
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