<?php
class Resource extends OModel{
  function __construct(){
    $table_name  = 'resource';
    $model = [
      'id_planet' => [
        'type'    => OCore::PK,
        'incr' => false,
        'ref' => 'planet.id',
        'comment' => 'Id del planeta que contiene el recurso'
      ],
      'id_moon' => [
        'type'    => OCore::PK,
        'incr' => false,
        'ref' => 'moon.id',
        'comment' => 'Id de la luna que contiene el recurso'
      ],
      'type' => [
        'type'    => OCore::PK,
        'incr' => false,
        'comment' => 'Tipo de recurso'
      ],
      'value' => [
        'type'    => OCore::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Cantidad del recurso'
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

  private $resource = null;

  public function getResource(){
    if (is_null($this->resource)){
      $this->loadResource();
    }
    return $this->resource;
  }

  public function setResource($resource){
    $this->resource = $resource;
  }

  public function loadResource(){
    $resources = OTools::getCache('resource', true);
    $list = $resources->get('resources');
    $key = array_search($this->get('type'), array_column($list, 'id'));
    $resource = $list[$key];
    $resource['value'] = $this->get('value');

    $this->setResource($resource);
  }
}