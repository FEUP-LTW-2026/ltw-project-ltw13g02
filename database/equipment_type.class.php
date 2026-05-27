<?php
  declare(strict_types=1);

  class EquipmentType {
    private int $id;
    private string $name;

    public function __construct(int $id, string $name) {
      $this->id = $id;
      $this->name = $name;
    }

    public function getId() : int {
      return $this->id;
    }

    public function getName() : string {
      return $this->name;
    }

    public function setName(string $name) : void {
      $this->name = $name;
    }

    public static function create(PDO $db, string $name) : int {
      $stmt = $db->prepare('INSERT INTO EquipmentType (name) VALUES (?)');
      $stmt->execute([$name]);
      return (int)$db->lastInsertId();
    }

    public static function getAllEquipmentTypes(PDO $db) : array {
      $stmt = $db->prepare('SELECT * FROM EquipmentType ORDER BY name ASC');
      $stmt->execute();

      $equipmentTypes = [];
      while ($row = $stmt->fetch()) {
        $equipmentTypes[] = new EquipmentType((int)$row['id'], (string)$row['name']);
      }

      return $equipmentTypes;
    }

    public static function getEquipmentTypeById(PDO $db, int $id) : EquipmentType {
      $stmt = $db->prepare('SELECT * FROM EquipmentType WHERE id = ?');
      $stmt->execute([$id]);
      $row = $stmt->fetch();
      if($row){
        return new EquipmentType((int)$row['id'], (string)$row['name']);
      }
      return new EquipmentType(0, ''); // Return an empty EquipmentType if not found
    }

    public function save(PDO $db) : void {
      $stmt = $db->prepare('UPDATE EquipmentType SET name = ? WHERE id = ?');
      $stmt->execute([$this->name, $this->id]);
    }
  }
?>