<?php
class Message extends OBase{
  function __construct(){
    $table_name  = 'message';
    $model = [
      'id' => [
        'type'    => Base::PK,
        'comment' => 'Id único de cada mensaje'
      ],
      'id_player_from' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'ref' => 'player.id',
        'comment' => 'Id del jugador que envía un mensaje'
      ],
      'id_player_to' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'ref' => 'player.id',
        'comment' => 'Id del jugador al que se le envía un mensaje'
      ],
      'message' => [
        'type'    => Base::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 250,
        'comment' => 'Contenido del mensaje'
      ],
      'req_id_resource' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => null,
        'comment' => 'Tipo de recurso que se solicita'
      ],
      'req_value' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => null,
        'comment' => 'Cantidad del recurso que se solicita'
      ],
      'req_credits' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => null,
        'comment' => 'Cantidad de créditos que se solicitan'
      ],
      'offer_id_resource' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => null,
        'comment' => 'Tipo de recurso que se ofrece'
      ],
      'offer_value' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => null,
        'comment' => 'Cantidad del recurso que se ofrece'
      ],
      'offer_credits' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => null,
        'comment' => 'Cantidad de créditos que se ofrecen'
      ],
      'req_status' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => null,
        'comment' => 'Estado de la solicitud 0 sin aceptar, 1 aceptada, null no hay solicitud'
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