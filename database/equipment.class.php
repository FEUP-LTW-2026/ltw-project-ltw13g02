<?php
  declare(strict_types=1);

  class Equipment {
    private int $id;
    private string $name;
    private string $status;
    private int $equipment_type_id;

    public function __construct(int $id, string $name, string $status, int $equipment_type_id) {
      $this->id = $id;
      $this->name = $name;
      $this->status = $status;
      $this->equipment_type_id = $equipment_type_id;
    }

    public function getId() : int {
      return $this->id;
    }

    public function getName() : string {
      return $this->name;
    }

    public function getStatus() : string {
      return $this->status;
    }

    public function getEquipmentTypeId() : int {
      return $this->equipment_type_id;
    }

    public function setName(string $name) : void {
      $this->name = $name;
    }

    public function setDescription(string $description) : void {
      $this->description = $description;
    }

    public function setStatus(string $status) : void {
      $this->status = $status;
    }

    public function setEquipmentTypeId(int $equipment_type_id) : void {
      $this->equipment_type_id = $equipment_type_id;
    }

    public static function create(PDO $db, string $name, string $status, int $equipment_type_id) : int {
      $stmt = $db->prepare('INSERT INTO Equipment (name, status, equipment_type_id) VALUES (?, ?, ?)');
      $stmt->execute([$name, $status, $equipment_type_id]);
      return (int)$db->lastInsertId();
    }

    public static function getAllEquipment(PDO $db) : array {
      $stmt = $db->prepare('SELECT * FROM Equipment ORDER BY equipment_type_id ASC, name ASC');
      $stmt->execute();

      $equipment = [];
      while ($row = $stmt->fetch()) {
        $equipment[] = new Equipment(
          (int)$row['id'],
          (string)$row['name'],
          (string)$row['status'],
          (int)$row['equipment_type_id']
        );
      }

      return $equipment;
    }

    public static function getEquipmentById(PDO $db, int $id) : Equipment {
      $stmt = $db->prepare('SELECT * FROM Equipment WHERE id = ?');
      $stmt->execute([$id]);
      $row = $stmt->fetch();

      if (!$row) {
        throw new Exception('Equipment not found.');
      }

      return new Equipment(
        (int)$row['id'],
        (string)$row['name'],
        (string)$row['status'],
        (int)$row['equipment_type_id']
      );
    }

    public static function getEquipmentByTypeId(PDO $db, int $equipment_type_id) : array {
      $stmt = $db->prepare('SELECT * FROM Equipment WHERE equipment_type_id = ? ORDER BY name, equipment_type_id ASC');
      $stmt->execute([$equipment_type_id]);

      $equipment = [];
      while ($row = $stmt->fetch()) {
        $equipment[] = new Equipment(
          (int)$row['id'],
          (string)$row['name'],
          (string)$row['status'],
          (int)$row['equipment_type_id']
        );
      }

      return $equipment;
    }

    public static function isAvailable(Equipment $equipment) : bool {
      return $equipment->getStatus() === 'available';
    }

    public function save(PDO $db) : void {
      $stmt = $db->prepare('UPDATE Equipment SET name = ?, equipment_type_id = ? WHERE id = ?');
      $stmt->execute([$this->name, $this->equipment_type_id, $this->id]);
    }

    public static function getEquipmentTypeCount(PDO $db, int $equipment_type_id) : int {
      $stmt = $db->prepare('SELECT COUNT(*) FROM Equipment WHERE equipment_type_id = ?');
      $stmt->execute([$equipment_type_id]);
      return (int)$stmt->fetchColumn();
    }

    public static function getAvailableEquipmentCount(PDO $db, int $equipment_type_id) : int {
      $stmt = $db->prepare('SELECT COUNT(*) FROM Equipment WHERE equipment_type_id = ? AND status = "available"');
      $stmt->execute([$equipment_type_id]);
      return (int)$stmt->fetchColumn();
    }
  
    public static function getCountsByTypeMap(PDO $db) : array {
      $stmt = $db->prepare('
        SELECT equipment_type_id,
              SUM(CASE WHEN status = "available" THEN 1 ELSE 0 END) AS available,
              COUNT(*) AS total
        FROM Equipment
        GROUP BY equipment_type_id
      ');
      $stmt->execute();
      $out = [];
      while ($row = $stmt->fetch()) {
        $out[(int)$row['equipment_type_id']] = [
          'available' => (int)$row['available'],
          'total' => (int)$row['total']
        ];
      }
      return $out;
    }
  }
?>