<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class Player extends OModel {
	#[OPK(
		comment: 'PK'
	)]
	public ?int $id;

	#[OField(
		comment: 'Email del jugador',
		nullable: false,
		max: 255,
		default: null
	)]
	public ?string $email;

	#[OField(
		comment: 'Contraseña hasheada',
		nullable: false,
		max: 255,
		default: null
	)]
	public ?string $password_hash;

	#[OField(
		comment: 'Apodo/nombre visible',
		nullable: false,
		max: 50,
		default: null
	)]
	public ?string $nickname;

	#[OField(
		comment: 'Créditos',
		nullable: false,
		default: 0
	)]
	public ?float $credits;

	#[OCreatedAt(
		comment: 'Creación'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
		comment: 'Última actualización'
	)]
	public ?string $updated_at;

	private ?array $auth_refresh_tokens = null;

	/**
	 * Save AuthRefreshToken list
	 *
	 * @param array $auth_refresh_tokens AuthRefreshToken list
	 *
	 * @return void
	 */
	public function setAuthRefreshTokens(array $auth_refresh_tokens): void {
		$this->auth_refresh_tokens = $auth_refresh_tokens;
	}

	/**
	 * Get AuthRefreshToken list
	 *
	 * @return array auth_refresh_token list
	 */
	public function getAuthRefreshTokens(): array {
		if (is_null($this->auth_refresh_tokens)) {
			$this->loadAuthRefreshTokens();
		}
		return $this->auth_refresh_tokens;
	}

	/**
	 * Load AuthRefreshToken list
	 *
	 * @return void
	 */
	private function loadAuthRefreshTokens(): void {
		$this->auth_refresh_tokens = AuthRefreshToken::where(['id_player' => $this->id]);
	}
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
		$this->conversation_participants = ConversationParticipant::where(['id_player' => $this->id]);
	}
	private ?array $discovery_logs = null;

	/**
	 * Save DiscoveryLog list
	 *
	 * @param array $discovery_logs DiscoveryLog list
	 *
	 * @return void
	 */
	public function setDiscoveryLogs(array $discovery_logs): void {
		$this->discovery_logs = $discovery_logs;
	}

	/**
	 * Get DiscoveryLog list
	 *
	 * @return array discovery_log list
	 */
	public function getDiscoveryLogs(): array {
		if (is_null($this->discovery_logs)) {
			$this->loadDiscoveryLogs();
		}
		return $this->discovery_logs;
	}

	/**
	 * Load DiscoveryLog list
	 *
	 * @return void
	 */
	private function loadDiscoveryLogs(): void {
		$this->discovery_logs = DiscoveryLog::where(['id_player' => $this->id]);
	}
	private ?array $jobs = null;

	/**
	 * Save Job list
	 *
	 * @param array $jobs Job list
	 *
	 * @return void
	 */
	public function setJobs(array $jobs): void {
		$this->jobs = $jobs;
	}

	/**
	 * Get Job list
	 *
	 * @return array job list
	 */
	public function getJobs(): array {
		if (is_null($this->jobs)) {
			$this->loadJobs();
		}
		return $this->jobs;
	}

	/**
	 * Load Job list
	 *
	 * @return void
	 */
	private function loadJobs(): void {
		$this->jobs = Job::where(['id_player' => $this->id]);
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
		$this->messages = Message::where(['id_sender' => $this->id]);
	}

	private ?array $moons = null;

	/**
	 * Save Moon list
	 *
	 * @param array $moons Moon list
	 *
	 * @return void
	 */
	public function setMoons(array $moons): void {
		$this->moons = $moons;
	}

	/**
	 * Get Moon list
	 *
	 * @return array moon list
	 */
	public function getMoons(): array {
		if (is_null($this->moons)) {
			$this->loadMoons();
		}
		return $this->moons;
	}

	/**
	 * Load Moon list
	 *
	 * @return void
	 */
	private function loadMoons(): void {
		$this->moons = Moon::where(['id_player_renamed_by' => $this->id]);
	}

	private ?array $planets = null;

	/**
	 * Save Planet list
	 *
	 * @param array $planets Planet list
	 *
	 * @return void
	 */
	public function setPlanets(array $planets): void {
		$this->planets = $planets;
	}

	/**
	 * Get Planet list
	 *
	 * @return array planet list
	 */
	public function getPlanets(): array {
		if (is_null($this->planets)) {
			$this->loadPlanets();
		}
		return $this->planets;
	}

	/**
	 * Load Planet list
	 *
	 * @return void
	 */
	private function loadPlanets(): void {
		$this->planets = Planet::where(['id_player_renamed_by' => $this->id]);
	}

	private ?array $player_modules = null;

	/**
	 * Save PlayerModule list
	 *
	 * @param array $player_modules PlayerModule list
	 *
	 * @return void
	 */
	public function setPlayerModules(array $player_modules): void {
		$this->player_modules = $player_modules;
	}

	/**
	 * Get PlayerModule list
	 *
	 * @return array player_module list
	 */
	public function getPlayerModules(): array {
		if (is_null($this->player_modules)) {
			$this->loadPlayerModules();
		}
		return $this->player_modules;
	}

	/**
	 * Load PlayerModule list
	 *
	 * @return void
	 */
	private function loadPlayerModules(): void {
		$this->player_modules = PlayerModule::where(['id_player' => $this->id]);
	}
	private ?array $player_presences = null;

	/**
	 * Save PlayerPresence list
	 *
	 * @param array $player_presences PlayerPresence list
	 *
	 * @return void
	 */
	public function setPlayerPresences(array $player_presences): void {
		$this->player_presences = $player_presences;
	}

	/**
	 * Get PlayerPresence list
	 *
	 * @return array player_presence list
	 */
	public function getPlayerPresences(): array {
		if (is_null($this->player_presences)) {
			$this->loadPlayerPresences();
		}
		return $this->player_presences;
	}

	/**
	 * Load PlayerPresence list
	 *
	 * @return void
	 */
	private function loadPlayerPresences(): void {
		$this->player_presences = PlayerPresence::where(['id_player' => $this->id]);
	}
	private ?array $player_resources = null;

	/**
	 * Save PlayerResource list
	 *
	 * @param array $player_resources PlayerResource list
	 *
	 * @return void
	 */
	public function setPlayerResources(array $player_resources): void {
		$this->player_resources = $player_resources;
	}

	/**
	 * Get PlayerResource list
	 *
	 * @return array player_resource list
	 */
	public function getPlayerResources(): array {
		if (is_null($this->player_resources)) {
			$this->loadPlayerResources();
		}
		return $this->player_resources;
	}

	/**
	 * Load PlayerResource list
	 *
	 * @return void
	 */
	private function loadPlayerResources(): void {
		$this->player_resources = PlayerResource::where(['id_player' => $this->id]);
	}
	private ?array $player_ships = null;

	/**
	 * Save PlayerShip list
	 *
	 * @param array $player_ships PlayerShip list
	 *
	 * @return void
	 */
	public function setPlayerShips(array $player_ships): void {
		$this->player_ships = $player_ships;
	}

	/**
	 * Get PlayerShip list
	 *
	 * @return array player_ship list
	 */
	public function getPlayerShips(): array {
		if (is_null($this->player_ships)) {
			$this->loadPlayerShips();
		}
		return $this->player_ships;
	}

	/**
	 * Load PlayerShip list
	 *
	 * @return void
	 */
	private function loadPlayerShips(): void {
		$this->player_ships = PlayerShip::where(['id_player' => $this->id]);
	}
	private ?array $player_system_homes = null;

	/**
	 * Save PlayerSystemHome list
	 *
	 * @param array $player_system_homes PlayerSystemHome list
	 *
	 * @return void
	 */
	public function setPlayerSystemHomes(array $player_system_homes): void {
		$this->player_system_homes = $player_system_homes;
	}

	/**
	 * Get PlayerSystemHome list
	 *
	 * @return array player_system_home list
	 */
	public function getPlayerSystemHomes(): array {
		if (is_null($this->player_system_homes)) {
			$this->loadPlayerSystemHomes();
		}
		return $this->player_system_homes;
	}

	/**
	 * Load PlayerSystemHome list
	 *
	 * @return void
	 */
	private function loadPlayerSystemHomes(): void {
		$this->player_system_homes = PlayerSystemHome::where(['id_player' => $this->id]);
	}
	private ?array $rename_logs = null;

	/**
	 * Save RenameLog list
	 *
	 * @param array $rename_logs RenameLog list
	 *
	 * @return void
	 */
	public function setRenameLogs(array $rename_logs): void {
		$this->rename_logs = $rename_logs;
	}

	/**
	 * Get RenameLog list
	 *
	 * @return array rename_log list
	 */
	public function getRenameLogs(): array {
		if (is_null($this->rename_logs)) {
			$this->loadRenameLogs();
		}
		return $this->rename_logs;
	}

	/**
	 * Load RenameLog list
	 *
	 * @return void
	 */
	private function loadRenameLogs(): void {
		$this->rename_logs = RenameLog::where(['id_player' => $this->id]);
	}
	private ?array $systems = null;

	/**
	 * Save System list
	 *
	 * @param array $systems System list
	 *
	 * @return void
	 */
	public function setSystems(array $systems): void {
		$this->systems = $systems;
	}

	/**
	 * Get System list
	 *
	 * @return array system list
	 */
	public function getSystems(): array {
		if (is_null($this->systems)) {
			$this->loadSystems();
		}
		return $this->systems;
	}

	/**
	 * Load System list
	 *
	 * @return void
	 */
	private function loadSystems(): void {
		$this->systems = System::where(['id_player_renamed_by' => $this->id]);
	}

}
