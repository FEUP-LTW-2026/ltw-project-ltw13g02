<?php
  class Session {
    private array $messages;

    public function __construct() {
      if (session_status() === PHP_SESSION_NONE) {
        session_start();
      }

      $this->messages = isset($_SESSION['messages']) ? $_SESSION['messages'] : array();
      unset($_SESSION['messages']);

      if (isset($_SESSION['is_active']) && !$_SESSION['is_active']) {
        $this->addInactiveAccountMessage();
      }
    }

    public function isLoggedIn() : bool {
      return isset($_SESSION['id']);    
    }

    public function logout() {
      session_destroy();
    }

    public function invalidateAuth(): void {
      unset($_SESSION['id'], $_SESSION['username'], $_SESSION['email'], $_SESSION['role'], $_SESSION['role_change_count'], $_SESSION['is_active']);
    }

    public function getId() : ?int {
      return isset($_SESSION['id']) ? $_SESSION['id'] : null;    
    }

    public function getUsername() : ?string {
      return isset($_SESSION['username']) ? $_SESSION['username'] : null;
    }

    public function isActive() : bool {
      return isset($_SESSION['is_active']) ? $_SESSION['is_active'] : false;
    }

    public function isAdmin() : bool {
      return isset($_SESSION['role']) && $_SESSION['role'] === 'Admin';
    }

    public function isMember() : bool {
      return isset($_SESSION['role']) && $_SESSION['role'] === 'Member';
    }

    public function isTrainer() : bool {
      return isset($_SESSION['role']) && $_SESSION['role'] === 'Trainer';
    }

    public function setRole(string $role) {
      $_SESSION['role'] = $role;
    }

    public function getRole() : ?string {
      return isset($_SESSION['role']) ? $_SESSION['role'] : null;
    }

    public function setRoleChangeCount(int $count) {
      $_SESSION['role_change_count'] = $count;
    }

    public function getRoleChangeCount() : ?int {
      return isset($_SESSION['role_change_count']) ? $_SESSION['role_change_count'] : null;
    }

    public function setIsActive(bool $is_active) {
      $_SESSION['is_active'] = $is_active;
    }

    public function setId(int $id) {
      $_SESSION['id'] = $id;
    }

    public function setEmail(string $email) {
        $_SESSION['email'] = $email;
    }

    public function setUsername(string $username) {
      $_SESSION['username'] = $username;
    }

    private function addMessage(string $type, string $text) {
      $_SESSION['messages'][] = array('type' => $type, 'text' => $text);
    }

    public function addErrorMessage(string $text) {
      $this->addMessage('error', $text);
    }

    public function addSuccessMessage(string $text) {
      $this->addMessage('success', $text);
    }

    public function addWarningMessage(string $text) {
      $this->addMessage('warning', $text);
    }

    public function addInactiveAccountMessage() : void {
      $this->addErrorMessage('This account is currently not active. Please contact the administrator if you want to reactivate it.');
    }

    public function getMessages() {
      return $this->messages;
    }

    public function generateCSRFToken() : string {
      if(!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
      }
      return $_SESSION['csrf_token'];
    }

    public function verifyCSRFToken(string $token) : bool {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    public function getCSRFToken() : ?string {
      return isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : null;
    }
  }
?>