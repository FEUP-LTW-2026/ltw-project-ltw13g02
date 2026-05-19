<?php
  class Session {
    private array $messages;

    public function __construct() {
      session_start(); // does not necessarily create a new session, if one already exists for the user (identified by a cookie)

    $this->messages = isset($_SESSION['messages']) ? $_SESSION['messages'] : array();
      unset($_SESSION['messages']);
    }

    public function isLoggedIn() : bool {
      return isset($_SESSION['id']);    
    }

    public function logout() {
      session_destroy();
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
      return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
    }

    public function isMember() : bool {
      return isset($_SESSION['is_member']) && $_SESSION['is_member'] === true;
    }

    public function isTrainer() : bool {
      return isset($_SESSION['is_trainer']) && $_SESSION['is_trainer'] === true;
    }

    public function setAdmin(bool $is_admin) {
      $_SESSION['is_admin'] = $is_admin;
    }

    public function setMember(bool $is_member) {
      $_SESSION['is_member'] = $is_member;
    }

    public function setTrainer(bool $is_trainer) {
      $_SESSION['is_trainer'] = $is_trainer;
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

    public function addMessage(string $type, string $text) {
      $_SESSION['messages'][] = array('type' => $type, 'text' => $text);
    }

    public function getMessages() {
      return $this->messages;
    }
  }
?>