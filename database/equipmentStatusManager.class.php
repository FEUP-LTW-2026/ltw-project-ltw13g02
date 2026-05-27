<?php
  declare(strict_types=1);
  require_once(__DIR__ . '/../utils/helpers.php');

  class EquipmentStatusManager {
    private array $validTransitions;

    public function __construct() {
      $this->validTransitions = [
        'available' => ['maintenance'],
        'maintenance' => ['available']
      ];
    }

    public function getAvailableStatuses(string $currentStatus) : array {
      return $this->validTransitions[$currentStatus] ?? [];
    }

    public static function changeStatus(PDO $db, int $equipmentId, string $oldStatus, string $newStatus, Session $session) : void {
      if(empty($oldStatus) || empty($newStatus)) {
        $session->addErrorMessage('Status values cannot be empty.');
        redirectToPreviousPage();
        exit;
      }
      try {
        $stmt = $db->prepare('UPDATE Equipment SET status = ? WHERE id = ? AND status = ?');
        $stmt->execute([$newStatus, $equipmentId, $oldStatus]);

        $session->addSuccessMessage('Equipment status updated successfully.');
        redirectToPreviousPage();
        exit;
      } catch (Exception $e) {

        $session->addErrorMessage('Failed to update equipment status. Please try again.');
        redirectToPreviousPage();
        exit;
      }
    }
  }
?>