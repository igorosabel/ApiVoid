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

  private $ships = null;

  public function getShips(){
    if (is_null($this->ships)){
      $this->loadShips();
    }
    return $this->ships;
  }

  public function setShips($ships){
    $this->ships = $ships;
  }

  public function loadShips(){
    $sql = "SELECT * FROM `npc_ship` WHERE `id_npc` = ?";
    $this->db->query($sql, [$this->get('id')]);
    $ships = [];
    while ($res = $this->db->next()){
      $npc_ship = new NPCShip();
      $npc_ship->update($res);
      $npc_ship->loadShip();

      array_push($ships, $npc_ship);
    }
    $this->setShips($ships);
  }

  private $modules = null;

  public function getModules(){
    if (is_null($this->modules)){
      $this->loadModules();
    }
    return $this->modules;
  }

  public function setModules($modules){
    $this->modules = $modules;
  }

  public function loadModules(){
    $sql = "SELECT * FROM `npc_module` WHERE `id_npc` = ?";
    $this->db->query($sql, [$this->get('id')]);
    $modules = [];
    while ($res = $this->db->next()){
      $npc_module = new NPCModule();
      $npc_module->update($res);
      $npc_module->loadModule();

      array_push($modules, $npc_module);
    }
    $this->setModules($modules);
  }

  private $resources = null;

  public function getResources(){
    if (is_null($this->resources)){
      $this->loadResources();
    }
    return $this->resources;
  }

  public function setResources($resources){
    $this->resources = $resources;
  }

  public function loadResources(){
    $sql = "SELECT * FROM `npc_resource` WHERE `id_npc` = ?";
    $this->db->query($sql, [$this->get('id')]);
    $resources = [];
    while ($res = $this->db->next()){
      $npc_resource = new NPCResource();
      $npc_resource->update($res);
      $npc_resource->loadResource();

      array_push($resources, $npc_resource);
    }
    $this->setResources($resources);
  }
}