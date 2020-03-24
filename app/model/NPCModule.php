<?php
class NPCModule extends OModel{
  function __construct(){
    $table_name  = 'npc_module';
    $model = [
      'id_npc' => [
        'type'    => OCore::PK,
        'incr' => false,
        'ref' => 'npc.id',
        'comment' => 'Id del NPC que hace la venta'
      ],
      'id_module' => [
        'type'    => OCore::PK,
        'incr' => false,
        'ref' => 'module.id',
        'comment' => 'Id del módulo que vende'
      ],
      'start_value' => [
        'type'    => OCore::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Cantidad inicial de módulos que vende'
      ],
      'value' => [
        'type'    => OCore::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Cantidad de módulos que le quedan disponibles'
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

  private $module = null;

  public function getModule(){
    if (is_null($this->module)){
      $this->loadModule();
    }
    return $this->module;
  }

  public function setModule($module){
    $this->module = $module;
  }

  public function loadModule($margin = 0){
    $sql = "SELECT * FROM `module` WHERE `id` = ?";
    $this->db->query($sql, [$this->get('id_module')]);
    $res = $this->db->next();

    $module = new Module();
    $module->update($res);
    $credits = $module->get('credits') * (1 + ($margin/100));
    $module->set('credits', $credits);

    $this->setModule($module);
  }
}