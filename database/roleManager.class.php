<?php 
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
          header('Location: ' . $_SERVER['HTTP_REFERER']);
          exit;
        }
        
        if ($oldRole === 'AuthenticatedUser' && $newRole === 'Member') {
          $stmt = $db->prepare('INSERT INTO Member (id) VALUES (?)');
          $stmt->execute([$userId]);
          
        } else {
          if ($oldRole === 'AuthenticatedUser') {
            $stmt = $db->prepare('INSERT INTO ' . $newRole . ' (id) VALUES (?)');
            $stmt->execute([$userId]);
          } else if ($newRole === 'AuthenticatedUser') {
            $stmt = $db->prepare('DELETE FROM ' . $oldRole . ' WHERE id = ?');
            $stmt->execute([$userId]);
          } else {
            $stmt = $db->prepare('DELETE FROM ' . $oldRole . ' WHERE id = ?');
            $stmt->execute([$userId]);
            $stmt = $db->prepare('INSERT INTO ' . $newRole . ' (id) VALUES (?)');
            $stmt->execute([$userId]);
          }

          // bump role change counter atomically within the transaction
          self::updateRoleChangeCount($db, $userId);
        }

        $db->commit();
        $session->addSuccessMessage('Role for user changed successfully.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      } catch (Exception $e) {
        if ($db->inTransaction()) {
          $db->rollBack();
        }
        $session->addErrorMessage('Error changing role: ' . $e->getMessage());
        $session->addErrorMessage('Failed to change role for user. Please try again.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      }
    }
  }
?>