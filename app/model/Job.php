<?php
class Job extends OModel{
  function __construct(){
    $table_name  = 'job';
    $model = [
      'id' => [
        'type'    => OCore::PK,
        'comment' => 'Id única para cada tarea'
      ],
      'id_player' => [
        'type'    => OCore::NUM,
        'nullable' => false,
        'default' => null,
        'ref' => 'player.id',
        'comment' => 'Id del jugador que hace la tarea'
      ],
      'type' => [
        'type'    => OCore::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Tipo de tarea'
      ],
      'value' => [
        'type'    => OCore::LONGTEXT,
        'nullable' => true,
        'default' => null,
        'comment' => 'Información extra de la tarea'
      ],
      'start' => [
        'type'    => OCore::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Timestamp de inicio de la tarea'
      ],
      'end' => [
        'type'    => OCore::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Timestamp de fin de la tarea'
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