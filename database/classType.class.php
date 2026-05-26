<?php
    declare (strict_types = 1);

    class ClassType {
        public int $id;
        public string $name;
        public int $trainerId;
        public string $trainerName;
        public int $capacity;
        public string $weekday;
        public string $startTime;
        public string $endTime;
        public ?string $description;

        static array $weekdays = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];

        public function __construct(int $id, string $name, int $trainerId, string $trainerName, int $capacity, string $weekday, string $startTime, string $endTime, ?string $description = null) {
            $this->id = $id;
            $this->name = $name;
            $this->trainerId = $trainerId;
            $this->trainerName = $trainerName;
            $this->capacity = $capacity;
            $this->weekday = $weekday;
            $this->startTime = $startTime;
            $this->endTime = $endTime;
            $this->description = $description;
        }

        private static function build(array $class) : ClassType {
            return new ClassType(
                $class['id'],
                $class['name'],
                $class['trainer_id'],
                $class['first_name'] . ' ' . $class['last_name'],
                $class['capacity'],
                ClassType::$weekdays[$class['weekday']],
                $class['start_time'],
                $class['end_time'],
                $class['description']
            );
        }

        public static function create(PDO $db, string $name, string $description, int $trainerId, int $capacity, string $startTime, string $endTime, int $weekday) : void {
            $stmt = $db->prepare('
                INSERT INTO ClassType (name, description, trainer_id, capacity, weekday, start_time, end_time)
                VALUES (?, ?, ?, ?, ?, ?, ?)');

            $stmt->execute([$name, $description, $trainerId, $capacity, $weekday, $startTime, $endTime]);
        }

        public static function update(PDO $db, int $id, string $name, string $description, int $trainerId, int $capacity, string $startTime, string $endTime, string $weekday) : void {
            
            $weekdayIndex = array_search($weekday, ClassType::$weekdays);

            if ($weekdayIndex === false) {
                throw new Exception('Invalid weekday value: ' . $weekday);
            }

            $stmt = $db->prepare('
                UPDATE ClassType
                SET name = ?, description = ?, trainer_id = ?, capacity = ?, weekday = ?, start_time = ?, end_time = ?
                WHERE id = ?');

            $stmt->execute([$name, $description, $trainerId, $capacity, $weekdayIndex, $startTime, $endTime, $id]);
        }

        public static function delete(PDO $db, int $id) : void {
            $stmt = $db->prepare('DELETE FROM ClassType WHERE id = ?');
            $stmt->execute([$id]);
        }

        public static function getAllClassesTypes(PDO $db) : array {
            $stmt = $db->prepare('
                SELECT DISTINCT ClassType.id, ClassType.name, ClassType.description, ClassType.trainer_id, 
                    User.first_name, User.last_name,
                    ClassType.capacity, ClassType.weekday, ClassType.start_time, 
                    ClassType.end_time
                FROM ClassType
                LEFT JOIN User ON ClassType.trainer_id = User.id');

            $stmt->execute();

            $classTypes = array();

            while($row = $stmt->fetch()) {
                $classTypes[] = ClassType::build($row);
            }

            return $classTypes;
        }

        public static function getClassTypeById(PDO $db, int $classTypeId) : ?ClassType {
            $stmt = $db->prepare('
                SELECT ClassType.id, ClassType.name, ClassType.description, ClassType.trainer_id, 
                    User.first_name, User.last_name,
                    ClassType.capacity, ClassType.weekday, ClassType.start_time, 
                    ClassType.end_time
                FROM ClassType
                LEFT JOIN User ON ClassType.trainer_id = User.id
                WHERE ClassType.id = ?');

            $stmt->execute([$classTypeId]);
            $row = $stmt->fetch();

            if (empty($row)) return null;

            return ClassType::build($row);
        }

        // Returns an array where the keys are the weekday indices (0-6) and the values are arrays of ClassType objects for that weekday
        public static function getClassesByWeekday(PDO $db) : array {
            $stmt = $db->prepare('
                SELECT ClassType.id, ClassType.name, ClassType.description, ClassType.trainer_id, 
                    User.first_name, User.last_name,
                    ClassType.capacity, ClassType.weekday, ClassType.start_time, 
                    ClassType.end_time
                FROM ClassType
                LEFT JOIN User ON ClassType.trainer_id = User.id
                ORDER BY ClassType.weekday, ClassType.start_time');

            $stmt->execute();

            $classesByWeekday = array_fill(0, 7, []); // an array with 7 elements, for each weekday

            while($row = $stmt->fetch()) {
                $class = ClassType::build($row);
                $weekdayIndex = array_search($class->weekday, ClassType::$weekdays);
                if ($weekdayIndex !== false) {
                    $classesByWeekday[$weekdayIndex][] = $class;
                }
            }

            return $classesByWeekday;
        }
    };
?>