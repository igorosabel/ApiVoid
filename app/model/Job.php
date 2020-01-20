<?php
class Job extends OBase{
  function __construct(){
    $table_name  = 'job';
    $model = [
      'id' => [
        'type'    => Base::PK,
        'comment' => 'Id única para cada tarea'
      ],
      'id_player' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'ref' => 'player.id',
        'comment' => 'Id del jugador que hace la tarea'
      ],
      'type' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Tipo de tarea'
      ],
      'value' => [
        'type'    => Base::LONGTEXT,
        'nullable' => true,
        'default' => null,
        'comment' => 'Información extra de la tarea'
      ],
      'start' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Timestamp de inicio de la tarea'
      ],
      'end' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Timestamp de fin de la tarea'
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