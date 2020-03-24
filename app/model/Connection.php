<?php
class Connection extends OModel{
  function __construct(){
    $table_name  = 'connection';
    $model = [
      'id' => [
        'type'    => OCore::PK,
        'comment' => 'Id única de cada conexión'
      ],
      'id_system_start' => [
        'type'    => OCore::NUM,
        'nullable' => false,
        'default' => null,
        'ref' => 'system.id',
        'comment' => 'Id del sistema del que se parte'
      ],
      'id_system_end' => [
        'type'    => OCore::NUM,
        'nullable' => true,
        'default' => null,
        'ref' => 'system.id',
        'comment' => 'Id del sistema destino o null si todavía no se ha investigado'
      ],
      'order' => [
        'type'    => OCore::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Orden de la conexión entre las que tiene un sistema'
      ],
      'navigate_time' => [
        'type'    => OCore::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Tiempo que se tarda en navegar al sistema destino'
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

  private $system_start = null;

  public function getSystemStart(){
    if (is_null($this->system_start)){
      $this->loadSystemStart();
    }
    return $this->system_start;
  }

  public function setSystemStart($system_start){
    $this->system_start = $system_start;
  }

  public function loadSystemStart(){
    $sql = "SELECT * FROM `system` WHERE `id` = ?";
    $this->db->query($sql, [$this->get('id_system_start')]);
    $res = $this->db->next();

    $system_start = new System();
    $system_start->update($res);

    $this->setSystemStart($system_start);
  }

  private $system_end = null;

  public function getSystemEnd(){
    if (is_null($this->system_end)){
      $this->loadSystemEnd();
    }
    return $this->system_end;
  }

  public function setSystemEnd($system_end){
    $this->system_end = $system_end;
  }

  public function loadSystemEnd(){
    $sql = "SELECT * FROM `system` WHERE `id` = ?";
    $this->db->query($sql, [$this->get('id_system_end')]);
    $res = $this->db->next();

    $system_end = new System();
    $system_end->update($res);

    $this->setSystemEnd($system_end);
  }
}