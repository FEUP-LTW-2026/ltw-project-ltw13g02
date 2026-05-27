<?php 
  
  require_once (__DIR__ . '/../database/equipment.class.php');

  class EquipmentTypeMap {
    private array $map;

    public function __construct(array $map) {
      $this->map = $map;
    }

    public function getAvailableCount(int $equipment_type_id) : int {
      $equipments = $this->map[$equipment_type_id] ?? [];
      $availableCount = 0;
      foreach ($equipments as $equipment) {
        if ($equipment->getStatus() === 'available') {
          $availableCount++;
        }
      }
      return $availableCount;
    }

    public function getTotalCount(int $equipment_type_id) : int {
      return count($this->map[$equipment_type_id] ?? []);
    }

    public static function getMapEquipmentTypeEquipments(PDO $db) : array {
      $stmt = $db->prepare('SELECT * FROM Equipment');
      $stmt->execute();

      $equipmentMap = [];
      while ($row = $stmt->fetch()) {
        $equipmentMap[(int)$row['equipment_type_id']][] = new Equipment(
          (int)$row['id'],
          (string)$row['name'],
          (string)$row['status'],
          (int)$row['equipment_type_id']
        );
      }

      return $equipmentMap;
    }

    public function getEquipmentsByTypeId(int $equipment_type_id) : array {
      return $this->map[$equipment_type_id] ?? [];
    }
  }

?>