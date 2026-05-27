<?php
  declare(strict_types=1);

  class EquipmentBooking {
    public static function create(PDO $db, int $memberId, int $equipmentId, int $timeslotId, string $bookingDate) : void {
      $stmt = $db->prepare('INSERT INTO EquipmentBooking (member_id, equipment_id, timeslot_id, booking_date) VALUES (?, ?, ?, ?)');
      $stmt->execute([$memberId, $equipmentId, $timeslotId, $bookingDate]);
    }

    public static function getBookingsForTimeslot(PDO $db, int $equipmentId, int $timeslotId, string $bookingDate) : array {
      $stmt = $db->prepare('SELECT * FROM EquipmentBooking WHERE equipment_id = ? AND timeslot_id = ? AND booking_date = ?');
      $stmt->execute([$equipmentId, $timeslotId, $bookingDate]);
      return $stmt->fetchAll();
    }

    public static function getBookingsByEquipmentAndDate(PDO $db, int $equipmentId, string $bookingDate) : array {
      $stmt = $db->prepare('SELECT * FROM EquipmentBooking WHERE equipment_id = ? AND booking_date = ? ORDER BY timeslot_id ASC');
      $stmt->execute([$equipmentId, $bookingDate]);
      return $stmt->fetchAll();
    }

    //TODO: fix this due to sql injection
    public static function getRemainingBookings(PDO $db, int $equipmentId) : int {
      $stmt = $db->prepare("
      SELECT COUNT(*) AS remaining_bookings
      FROM EquipmentBooking
      JOIN Timeslot ON EquipmentBooking.timeslot_id = Timeslot.id
      WHERE EquipmentBooking.equipment_id = ?
      AND datetime(EquipmentBooking.booking_date || ' ' || Timeslot.start_time) >= datetime('now', 'localtime')");
      $stmt->execute([$equipmentId]);
      return (int)$stmt->fetchColumn();
    }

    public static function getBookedTimeslotsByEquipId(PDO $db, int $equipmentId, string $bookingDate) : array {
      // Get all booked timeslots for the date presented and for the equipmentid
      $stmt = $db->prepare('
        SELECT t.id
        FROM Timeslot t
        JOIN EquipmentBooking eb ON t.id = eb.timeslot_id
        WHERE eb.equipment_id = ? AND eb.booking_date = ?
        ORDER BY t.start_time ASC
      ');
      $stmt->execute([$equipmentId, $bookingDate]);
      return $stmt->fetchAll(); // returns an array of timeslot ids that are booked for the given equipment and date
    }

    public static function isBooked(array $bookedTimeslots, int $timeslotId) : bool {
      return in_array($timeslotId, array_column($bookedTimeslots, 'id')); // checks if the given timeslot id is in the array of booked timeslot ids
    }
  }
?>