<?php
class NPC extends OBase{
  function __construct(){
    $table_name  = 'npc';
    $model = [
      'id' => [
        'type'    => Base::PK,
        'comment' => 'Id único de cada NPC'
      ],
      'name' => [
        'type'    => Base::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 50,
        'comment' => 'Nombre del NPC'
      ],
      'id_race' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Id de la raza del NPC'
      ],
      'id_system' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Id del sistema en el que está el NPC'
      ],
      'last_reset' => [
        'type'    => Base::DATE,
        'nullable' => true,
        'default' => null,
        'comment' => 'Fecha del último reseteo del NPC'
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