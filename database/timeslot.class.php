<?php
  declare(strict_types=1);

  require_once(__DIR__ . '/equipment_booking.class.php');

  class Timeslot {
    private int $id;
    private string $start_time;
    private string $end_time;

    public function __construct(int $id, string $start_time, string $end_time) {
      $this->id = $id;
      $this->start_time = $start_time;
      $this->end_time = $end_time;
    }

    public function getId() : int {
      return $this->id;
    }

    public function getStartTime() : string {
      return $this->start_time;
    }

    public function getEndTime() : string {
      return $this->end_time;
    }

    public static function create(PDO $db, string $start_time, string $end_time) : int {
      $stmt = $db->prepare('INSERT INTO Timeslot (start_time, end_time) VALUES (?, ?)');
      $stmt->execute([$start_time, $end_time]);
      return (int)$db->lastInsertId();
    }

    public static function getTimeslotById(PDO $db, int $id) : Timeslot {
      $stmt = $db->prepare('SELECT * FROM Timeslot WHERE id = ?');
      $stmt->execute([$id]);
      $row = $stmt->fetch();

      return new Timeslot((int)$row['id'], (string)$row['start_time'], (string)$row['end_time']);
    }

    public static function getAllTimeslots(PDO $db) : array {
      $stmt = $db->query('SELECT * FROM Timeslot ORDER BY start_time ASC');
      $timeslots = [];
      while ($row = $stmt->fetch()) {
        $timeslots[] = new Timeslot((int)$row['id'], (string)$row['start_time'], (string)$row['end_time']);
      }
      return $timeslots;
    }

    public static function isAvailable(PDO $db, int $timeslotId, int $equipmentId, string $bookingDate) : bool {
      $bookedTimeslots = EquipmentBooking::getBookedTimeslotsByEquipId($db, $equipmentId, $bookingDate);
      // Should check two conditions:
      // 1. The timeslot is not in the past (compare with current time)
      // 2. The timeslot is not booked
      return !Timeslot::isPast($db, $timeslotId) && !EquipmentBooking::isBooked($bookedTimeslots, $timeslotId);
    }

    public static function isPast(PDO $db, int $timeslotId) : bool {
      $stmt = $db->prepare('SELECT start_time FROM Timeslot WHERE id = ? AND time("now", "localtime") > time(start_time)');
      $stmt->execute([$timeslotId]);
      return (bool)$stmt->fetchColumn();
    }
  }
?>