<?php
  declare (strict_types = 1);

  class GymClass {
    public int $id;
    public string $name;
    public int $trainerId;
    public int $capacity;
    public array $enrolled;
    public string $weekday;
    public string $startTime;
    public string $endTime;

    static array $weekdays = [
      1 => 'Monday',
      2 => 'Tuesday',
      3 => 'Wednesday',
      4 => 'Thursday',
      5 => 'Friday',
      6 => 'Saturday',
      7 => 'Sunday'
    ];

    public function __construct(int $id, string $name, int $trainerId, int $capacity, array $enrolled, string $weekday, string $startTime, string $endTime) {
      $this->id = $id;
      $this->name = $name;
      $this->trainerId = $trainerId;
      $this->capacity = $capacity;
      $this->enrolled = $enrolled;
      $this->weekday = $weekday;
      $this->startTime = $startTime;
      $this->endTime = $endTime;
    }

    private static function build(array $class, array $enrolled) : GymClass {
      return new GymClass(
        $class['id'],
        $class['name'],
        $class['trainer_id'],
        $class['capacity'],
        $enrolled,
        GymClass::$weekdays[$class['weekday']],
        $class['start_time'],
        $class['end_time']
      );
    }

    static function getAllClasses(PDO $db) : array {
      
      $stmt = $db->prepare('
            SELECT Class.id, ClassType.name, Class.trainer_id, ClassType.capacity,
                   ClassType.weekday, ClassType.start_time, ClassType.end_time,
                   Enrollment.member_id
            FROM Class
            JOIN ClassType ON Class.class_type_id = ClassType.id
            LEFT JOIN Enrollment ON Class.id = Enrollment.class_id
      ');
      $stmt->execute();

      $classes = array();

      // Each row repsents one enrollment. The class data is repeated in each row.
      while($row = $stmt->fetch()) {
        $id = $row['id'];

        if(!isset($classes[$id])) {
          $classes[$id] = GymClass::build($row, []);
        }

        if($row['member_id'] !== null) {
          $classes[$id]->enrolled[] = $row['member_id'];
        }
      }

      return array_values($classes);
    }

    static function getClassById(PDO $db, int $id) : ?GymClass {
      $stmt = $db->prepare('
            SELECT Class.id, ClassType.name, Class.trainer_id, ClassType.capacity,
                   ClassType.weekday, ClassType.start_time, ClassType.end_time,
                   Enrollment.member_id
            FROM Class
            JOIN ClassType ON Class.class_type_id = ClassType.id
            LEFT JOIN Enrollment ON Class.id = Enrollment.class_id
            WHERE Class.id = ?
      ');
      $stmt->execute([$id]);

      $class = null;

      // Each row repsents one enrollment. The class data is repeated in each row.
      while($row = $stmt->fetch()) {
        if($class === null) {
          $class = GymClass::build($row, []);
        }

        if($row['member_id'] !== null) {
          $class->enrolled[] = $row['member_id'];
        }
      }

      return $class;
    }
  };
?>