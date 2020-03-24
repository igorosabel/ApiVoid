<?php
class NPC extends OModel{
  function __construct(){
    $table_name  = 'npc';
    $model = [
      'id' => [
        'type'    => OCore::PK,
        'comment' => 'Id único de cada NPC'
      ],
      'name' => [
        'type'    => OCore::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 50,
        'comment' => 'Nombre del NPC'
      ],
      'id_race' => [
        'type'    => OCore::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Id de la raza del NPC'
      ],
      'id_system' => [
        'type'    => OCore::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Id del sistema en el que está el NPC'
      ],
      'margin' => [
        'type'    => OCore::NUM,
        'nullable' => false,
        'default' => 0,
        'comment' => 'Margen del NPC respecto a precios originales (-20/20 %)'
      ],
      'found' => [
        'type'    => OCore::BOOL,
        'nullable' => false,
        'default' => false,
        'comment' => 'Indica si el NPC ya ha sido encontrado 1 o no 0'
      ],
      'last_reset' => [
        'type'    => OCore::DATE,
        'nullable' => true,
        'default' => null,
        'comment' => 'Fecha del último reseteo del NPC'
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
      $npc_ship->loadShip($this->get('margin'));

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
      $npc_module->loadModule($this->get('margin'));

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
      $npc_resource->loadResource($this->get('margin'));

      array_push($resources, $npc_resource);
    }
    $this->setResources($resources);
  }
}