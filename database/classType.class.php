<?php
    declare (strict_types = 1);

    class ClassType {
        private int $id;
        private string $name;
        private ?int $trainerId;
        private string $trainerName;
        private int $capacity;
        private string $weekday;
        private string $startTime;
        private string $endTime;
        private ?string $description;

        public function getId() : int { return $this->id; }
        public function getName() : string { return $this->name; }
        public function getTrainerId() : ?int { return $this->trainerId; }
        public function getTrainerName() : string { return $this->trainerName; }
        public function getCapacity() : int { return $this->capacity; }
        public function getWeekday() : string { return $this->weekday; }
        public function getStartTime() : string { return $this->startTime; }
        public function getEndTime() : string { return $this->endTime; }
        public function getDescription() : ?string { return $this->description; }

        static array $weekdays = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];

        public function __construct(int $id, string $name, ?int $trainerId, string $trainerName, int $capacity, string $weekday, string $startTime, string $endTime, ?string $description = null) {
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
                $trainerId = $row['trainer_id'];
                if (!AuthenticatedUser::isUserActive($db, $trainerId)) $row['trainer_id'] = null;
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
            if (!AuthenticatedUser::isUserActive($db, $row['trainer_id'])) $row['trainer_id'] = null;

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