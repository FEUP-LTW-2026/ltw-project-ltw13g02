<?php
    declare (strict_types = 1);

    class GymClass {
        public int $id;
        public string $name;
        public int $trainerId;
        public string $trainerName;
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

        public function __construct(int $id, string $name, int $trainerId, string $trainerName, int $capacity, array $enrolled, string $weekday, string $startTime, string $endTime) {
            $this->id = $id;
            $this->name = $name;
            $this->trainerId = $trainerId;
            $this->trainerName = $trainerName;
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
                $class['trainer_name'],
                $class['capacity'],
                $enrolled,
                GymClass::$weekdays[$class['weekday']],
                $class['start_time'],
                $class['end_time']
            );
        }

        // Get the trainer name here so that it is easier to display
        public static function getAllClasses(PDO $db) : array {
            $stmt = $db->prepare('
                    SELECT Class.id, ClassType.name, Class.trainer_id, 
                        CONCAT(User.first_name, " ", User.last_name) as trainer_name,
                        ClassType.capacity, ClassType.weekday, ClassType.start_time, 
                        ClassType.end_time, Enrollment.member_id
                    FROM Class
                    JOIN ClassType ON Class.class_type_id = ClassType.id
                    JOIN User ON Class.trainer_id = User.id
                    LEFT JOIN Enrollment ON Class.id = Enrollment.class_id
            ');
            
            $stmt->execute();

            $classes = array();

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

        public static function getClassById(PDO $db, int $id) : ?GymClass {
            $stmt = $db->prepare('
                SELECT Class.id, ClassType.name, Class.trainer_id, 
                    CONCAT(User.first_name, " ", User.last_name) as trainer_name,
                    ClassType.capacity, ClassType.weekday, ClassType.start_time, 
                    ClassType.end_time, Enrollment.member_id
                FROM Class
                JOIN ClassType ON Class.class_type_id = ClassType.id
                JOIN User ON Class.trainer_id = User.id
                LEFT JOIN Enrollment ON Class.id = Enrollment.class_id
                WHERE Class.id = ?
            ');

            $stmt->execute([$id]);

            $class = null;

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

        public static function getEnrolledClasses(PDO $db, int $memberId) : array {
            $stmt = $db->prepare('
                SELECT Class.id, ClassType.name, Class.trainer_id, 
                    CONCAT(User.first_name, " ", User.last_name) as trainer_name,
                    ClassType.capacity, ClassType.weekday, ClassType.start_time, 
                    ClassType.end_time, E2.member_id
                FROM Enrollment E1
                JOIN Class ON E1.class_id = Class.id
                JOIN ClassType ON Class.class_type_id = ClassType.id
                JOIN User ON Class.trainer_id = User.id
                LEFT JOIN Enrollment E2 ON Class.id = E2.class_id
                WHERE E1.member_id = ?
            ');

            $stmt->execute([$memberId]);

            $classes = array();

            while($row = $stmt->fetch()) {
                $id = $row['id']; // Class ID

                if(!isset($classes[$id])) {
                    $classes[$id] = GymClass::build($row, []);
                }

                if($row['member_id'] !== null) {
                    $classes[$id]->enrolled[] = $row['member_id'];
                }
            }

            return array_values($classes);
        }
    };
?>