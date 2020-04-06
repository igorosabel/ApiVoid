<?php
class messageService extends OService{
	function __construct(){
		$this->loadService();
	}

  public function getUnreadMessages($id_player){
    $db = new ODB();
    $messages = [];

    $sql = "SELECT * FROM `message` WHERE `id_player_to` = ? AND `is_read` = 0 ORDER BY `updated_at` DESC";
    $db->query($sql, [$id_player]);
    while ($res = $db->next()){
      $m = new Message();
      $m->update($res);
      array_push($messages, $m);
    }

    return $messages;
  }
}