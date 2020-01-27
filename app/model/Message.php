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
      'type' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => 0,
        'comment' => 'Tipo de mensaje: normal 0 solicitud 1'
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
      'is_read' => [
        'type'    => Base::BOOL,
        'nullable' => false,
        'default' => false,
        'comment' => 'Mensaje no leído 0 o leído 1'
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

  private $to = null;

  public function getTo(){
    if (is_null($this->to)){
      $this->loadTo();
    }
    return $this->to;
  }

  public function setTo($to){
    $this->to = $to;
  }

  public function loadTo(){
    $to = new Player();
    $to->find(['id'=>$this->get('id_player_to')]);
    $this->setTo($to);
  }

  private $from = null;

  public function getFrom(){
    if (is_null($this->from)){
      $this->loadFrom();
    }
    return $this->from;
  }

  public function setFrom($from){
    $this->from = $from;
  }

  public function loadFrom(){
    $from = new Player();
    $from->find(['id'=>$this->get('id_player_from')]);
    $this->setFrom($from);
  }
}