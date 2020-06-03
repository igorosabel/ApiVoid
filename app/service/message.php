<?php declare(strict_types=1);
class messageService extends OService {
	/**
	 * Load service tools
	 */
	function __construct() {
		$this->loadService();
	}

	/**
	 * Obtiene la lista de mensajes no leídos de un jugador
	 *
	 * @param int $id_player Id del jugador
	 *
	 * @return array Lista de mensajes no leídos
	 */
	public function getUnreadMessages(int $id_player): array {
		$db = new ODB();
		$messages = [];

		$sql = "SELECT * FROM `message` WHERE `id_player_to` = ? AND `is_read` = 0 ORDER BY `updated_at` DESC";
		$db->query($sql, [$id_player]);
		while ($res = $db->next()) {
			$m = new Message();
			$m->update($res);
			array_push($messages, $m);
		}

		return $messages;
	}
}