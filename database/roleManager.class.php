<?php

  require_once(__DIR__ . '/../utils/helpers.php');

  class RoleManager {
    private array $validTransitions;

    public function __construct() {
      $this->validTransitions = [
        'AuthenticatedUser' => ['Admin', 'Trainer'],
        'Admin' => ['AuthenticatedUser', 'Trainer'],
        'Trainer' => ['AuthenticatedUser', 'Admin'],
        'Member' => ['AuthenticatedUser', 'Admin', 'Trainer']
      ];
    }

    public function getAvailableRoles(string $currentRole): array {
      return $this->validTransitions[$currentRole] ?? [];
    }

    public static function updateRoleChangeCount(PDO $db, int $userId): void {
      $stmt = $db->prepare('UPDATE User SET role_change_count = role_change_count + 1 WHERE id = ?');
      $stmt->execute([$userId]);
    }

    public static function changeUserRole(PDO $db, int $userId, string $oldRole, string $newRole, Session $session): void {
      try {
        $db->beginTransaction();

        if ($oldRole === $newRole) {
          $db->rollBack();
          $session->addErrorMessage('User already has the role: ' . $newRole);
          redirectToPreviousPage();
        }
        
        if ($oldRole === 'AuthenticatedUser') { // transitions from AuthenticatedUser only make insertions
          $stmt = $db->prepare('INSERT INTO ? (id) VALUES (?)');
          $stmt->execute([$newRole, $userId]);
        } else if ($newRole === 'AuthenticatedUser') { // transitions to AuthenticatedUser only make deletions
          $stmt = $db->prepare('DELETE FROM ? WHERE id = ?');
          $stmt->execute([$oldRole, $userId]);

        } else { // transitions to/from other roles require both insertion and deletion
          $stmt = $db->prepare('DELETE FROM ? WHERE id = ?');
          $stmt->execute([$oldRole, $userId]);
          $stmt = $db->prepare('INSERT INTO ? (id) VALUES (?)');
          $stmt->execute([$newRole, $userId]);
        }

        // bump role change counter atomically within the transaction (used for role confirmation)
        self::updateRoleChangeCount($db, $userId);
      
        $db->commit();
        $session->addSuccessMessage('Role for user changed successfully.');
        redirectToPreviousPage();
        
      } catch (Exception $e) {
        if ($db->inTransaction()) {
          $db->rollBack();
        }
        $session->addErrorMessage('Error changing role: ' . $e->getMessage());
        $session->addErrorMessage('Failed to change role for user. Please try again.');
        redirectToPreviousPage();
      }
    }
  }
?>