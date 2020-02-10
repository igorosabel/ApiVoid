<?php
class Planet extends OBase{
  function __construct(){
    $table_name  = 'planet';
    $model = [
      'id' => [
        'type'    => Base::PK,
        'comment' => 'Id único del planeta'
      ],
      'id_system' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'ref' => 'system.id',
        'comment' => 'Id del sistema al que pertenece el planeta'
      ],
      'original_name' => [
        'type'    => Base::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 50,
        'comment' => 'Nombre original del planeta'
      ],
      'name' => [
        'type'    => Base::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 50,
        'comment' => 'Nombre actual del planeta'
      ],
      'type' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Tipo de planeta'
      ],
      'radius' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Radio del planeta'
      ],
      'rotation' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Velocidad de rotación del planeta alrededor del sol'
      ],
      'distance' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Distancia del planeta a su sol'
      ],
      'num_moons' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => '0',
        'comment' => 'Número de lunas que tiene el planeta'
      ],
      'explored' => [
        'type'    => Base::BOOL,
        'comment' => 'Indica si el planeta ha sido explorado 1 o no 0'
      ],
      'explore_time' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Tiempo necesario para explorar el planeta'
      ],
      'id_npc' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => null,
        'ref' => 'npc.id',
        'comment' => 'Id del NPC que habita el planeta o null si no tiene'
      ],
      'id_construction' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => null,
        'ref' => 'construction.id',
        'comment' => 'Id de la construcción que hay en el planeta o null si no tiene'
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

  private $moons = null;

  public function getMoons(){
    if (is_null($this->moons)){
      $this->loadMoons();
    }
    return $this->moons;
  }

  public function setMoons($moons){
    $this->moons = $moons;
  }

  public function loadMoons(){
    $sql = "SELECT * FROM `moon` WHERE `id_planet` = ?";
    $this->db->query($sql, [$this->get('id')]);
    $moons = [];
    while($res = $this->db->next()){
      $moon = new Moon();
      $moon->update($res);
      array_push($moons, $moon);
    }
    $this->setMoons($moons);
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
    $sql = "SELECT * FROM `resource` WHERE `id_planet` = ?";
    $this->db->query($sql, [$this->get('id')]);
    $resources = [];
    while($res = $this->db->next()){
      $resource = new Resource();
      $resource->update($res);
      array_push($resources, $resource);
    }
    $this->setResources($resources);
  }

  private $planet_type_name = null;

  public function getTypeName(){
    if (is_null($this->planet_type_name)){
      $this->loadPlanetTypeName();
    }
    return $this->planet_type_name;
  }

  public function setTypeName($planet_type_name){
    $this->planet_type_name = $planet_type_name;
  }

  public function loadPlanetTypeName(){
    $planet_types = Base::getCache('planet');
    $this->setTypeName($planet_types['planet_types']['type_'.$this->get('type')]['type']);
  }

  public function getPlanetInfoLink(){
    $system = Base::getCache('planet');
    return $system['info'];
  }
}