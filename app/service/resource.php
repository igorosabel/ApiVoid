<?php
class resourceService extends OService{
  function __construct(){
    $this->loadService();
  }

  public function getSellResources($ship, $npc){
    $db = new ODB();
    $resources = OTools::getCache('resource');
    $sql = "SELECT * FROM `ship_resource` WHERE `id_ship` = ?";
    $db->query($sql, [$ship->get('id')]);
    $ret = [];

    while ($res = $db->next()){
      $ship_resource = new ShipResource();
      $ship_resource->update($res);

      $key = array_search($ship_resource->get('type'), array_column($resources['resources'], 'id'));
      $resource = $resources['resources'][$key];
      $resource['credits'] = $resource['price'] * (1 + ($npc->get('margin')/100));
      $resource['value'] = $ship_resource->get('value');

      array_push($ret, $resource);
    }

    return $ret;
  }
}