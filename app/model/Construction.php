<?php
class Construction extends OBase{
  function __construct(){
    $table_name  = 'construction';
    $model = [
      'id' => [
        'type'    => Base::PK,
        'comment' => 'Id único para cada construcción'
      ],
      'id_player' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'ref' => 'player.id',
        'comment' => 'Id del jugador que hace la construcción'
      ],
      'commerce' => [
        'type'    => Base::BOOL,
        'comment' => 'Indica si tiene puesto de comercio 1 o no 0'
      ],
      'repair' => [
        'type'    => Base::BOOL,
        'comment' => 'Indica si tiene taller de reparaciones 1 o no 0'
      ],
      'workshop' => [
        'type'    => Base::BOOL,
        'comment' => 'Indica si tiene taller de construcciones 1 o no 0'
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