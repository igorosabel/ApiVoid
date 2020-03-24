<?php
class NPCResource extends OModel{
  function __construct(){
    $table_name  = 'npc_resource';
    $model = [
      'id_npc' => [
        'type'    => OCore::PK,
        'incr' => false,
        'ref' => 'npc.id',
        'comment' => 'Id del NPC que tiene un recurso a la venta'
      ],
      'type' => [
        'type'    => OCore::PK,
        'incr' => false,
        'comment' => 'Tipo de recurso'
      ],
      'start_value' => [
        'type'    => OCore::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Cantidad inicial del recurso que vende'
      ],
      'value' => [
        'type'    => OCore::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Cantidad del recurso que le queda disponible'
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

  public function loadResource($margin = 0){
    $resources = OTools::getCache('resource');
    $key = array_search($this->get('type'), array_column($resources['resources'], 'id'));
    $resource = $resources['resources'][$key];
    $resource['credits'] = $resource['price'] * (1 + ($margin/100));

    $this->setResource($resource);
  }
}