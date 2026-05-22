<?php 
  class RoleManager {
    private array $validTransitions;

    public function __construct() {
      $this->validTransitions = [
        'AuthenticatedUser' => ['AuthenticatedUser', 'Admin', 'Trainer'],
        'Admin' => ['AuthenticatedUser', 'Trainer'],
        'Trainer' => ['AuthenticatedUser', 'Trainer', 'Admin'],
        'Member' => ['AuthenticatedUser', 'Admin', 'Trainer']
      ];
    }

    public function getAvailableRoles(string $currentRole): array {
      return $this->validTransitions[$currentRole] ?? [];
    }

    public static function changeUserRole(PDO $db, int $userId, string $oldRole, string $newRole, Session $session): void {
      try {
        if($oldRole === 'AuthenticatedUser'){
          $stmt = $db->prepare('INSERT INTO ' . $newRole . ' (id) VALUES (?)');
          $stmt->execute([$userId]);
        }
        else if ($newRole === 'AuthenticatedUser') {
          $stmt = $db->prepare('DELETE FROM ' . $oldRole . ' WHERE id = ?');
          $stmt->execute([$userId]);
        }
        else {
          $db->beginTransaction();
          $stmt = $db->prepare('DELETE FROM ' . $oldRole . ' WHERE id = ?');
          $stmt->execute([$userId]);
          $stmt = $db->prepare('INSERT INTO ' . $newRole . ' (id) VALUES (?)');
          $stmt->execute([$userId]);
          $db->commit();
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      } catch (Exception $e) {
        if ($db->inTransaction()) {
          $db->rollBack();
        }
        $session->addErrorMessage('Failed to change role for user. Please try again.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }
    }
  }
?>