<?php

  require_once(__DIR__ . '/../utils/helpers.php');

  class RoleManager {
    private array $validTransitions;
    private static $allRoles = ['User', 'Admin', 'Trainer', 'Member'];

    public function __construct() {
      $this->validTransitions = [
        'User' => ['Admin', 'Trainer'],
        'Admin' => ['User', 'Trainer'],
        'Trainer' => ['User', 'Admin'],
        'Member' => ['User', 'Admin', 'Trainer']
      ];
    }

    public static function getCreationRoles() : array {
      return array_filter(self::$allRoles, function($role) {
        return $role !== 'Member'; // cannot create a member
      });
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

        // Verify if the role is valid
        if (!in_array($oldRole, self::$allRoles) || !in_array($newRole, self::$allRoles)) {
          $db->rollBack();
          $session->addErrorMessage('Invalid role specified.');
          redirectToPreviousPage();
        }

        // Verify if the transition is valid
        $roleManager = new RoleManager();
        $availableRoles = $roleManager->getAvailableRoles($oldRole);
        if (!in_array($newRole, $availableRoles)) {
          $db->rollBack();
          $session->addErrorMessage('Invalid role transition from ' . $oldRole . ' to ' . $newRole);
          redirectToPreviousPage();
        }

        if ($oldRole === 'User') { // transitions from User only make insertions
          $stmt = $db->prepare("INSERT INTO $newRole (id) VALUES (?)");
          $stmt->execute([$userId]);
        } else if ($newRole === 'User') { // transitions to User only make deletions
          $stmt = $db->prepare("DELETE FROM $oldRole WHERE id = ?");
          $stmt->execute([$userId]);
        } else { // transitions to/from other roles require both insertion and deletion
          $stmt = $db->prepare("DELETE FROM $oldRole WHERE id = ?");
          $stmt->execute([$userId]);
          $stmt = $db->prepare("INSERT INTO $newRole (id) VALUES (?)");
          $stmt->execute([$userId]);
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