<?php
class Crew extends OBase{
  function __construct(){
    $table_name  = 'crew';
    $model = [
      'id' => [
        'type'    => Base::PK,
        'comment' => 'Id único de cada tripulante'
      ],
      'id_player' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'ref' => 'player.id',
        'comment' => 'Id del jugador que contrata al tripulante'
      ],
      'name' => [
        'type'    => Base::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 50,
        'comment' => 'Nombre del tripulante'
      ],
      'id_race' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Id de la raza del tripulante'
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