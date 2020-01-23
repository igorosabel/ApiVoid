<?php
class NPCResource extends OBase{
  function __construct(){
    $table_name  = 'npc_resource';
    $model = [
      'id_npc' => [
        'type'    => Base::PK,
        'incr' => false,
        'ref' => 'npc.id',
        'comment' => 'Id del NPC que tiene un recurso a la venta'
      ],
      'type' => [
        'type'    => Base::PK,
        'incr' => false,
        'comment' => 'Tipo de recurso'
      ],
      'start_value' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Cantidad inicial del recurso que vende'
      ],
      'value' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Cantidad del recurso que le queda disponible'
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