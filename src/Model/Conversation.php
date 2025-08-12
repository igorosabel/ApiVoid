<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class Conversation extends OModel {
	#[OPK(
		comment: 'PK'
	)]
	public ?int $id;

	#[OCreatedAt(
		comment: 'Creación'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
		comment: 'Última actualización'
	)]
	public ?string $updated_at;

	private ?array $conversation_participants = null;

	/**
	 * Save ConversationParticipant list
	 *
	 * @param array $conversation_participants ConversationParticipant list
	 *
	 * @return void
	 */
	public function setConversationParticipants(array $conversation_participants): void {
		$this->conversation_participants = $conversation_participants;
	}

	/**
	 * Get ConversationParticipant list
	 *
	 * @return array conversation_participant list
	 */
	public function getConversationParticipants(): array {
		if (is_null($this->conversation_participants)) {
			$this->loadConversationParticipants();
		}
		return $this->conversation_participants;
	}

	/**
	 * Load ConversationParticipant list
	 *
	 * @return void
	 */
	private function loadConversationParticipants(): void {
		$this->conversation_participants = ConversationParticipant::where(['id_conversation' => $this->id]);
	}
	private ?array $messages = null;

	/**
	 * Save Message list
	 *
	 * @param array $messages Message list
	 *
	 * @return void
	 */
	public function setMessages(array $messages): void {
		$this->messages = $messages;
	}

	/**
	 * Get Message list
	 *
	 * @return array message list
	 */
	public function getMessages(): array {
		if (is_null($this->messages)) {
			$this->loadMessages();
		}
		return $this->messages;
	}

	/**
	 * Load Message list
	 *
	 * @return void
	 */
	private function loadMessages(): void {
		$this->messages = Message::where(['id_conversation' => $this->id]);
	}

}
