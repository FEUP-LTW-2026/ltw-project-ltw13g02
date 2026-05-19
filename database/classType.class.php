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

        static array $weekdays = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];

        public function __construct(int $id, string $name, int $trainerId, string $trainerName, int $capacity, string $weekday, string $startTime, string $endTime) {
            $this->id = $id;
            $this->name = $name;
            $this->trainerId = $trainerId;
            $this->trainerName = $trainerName;
            $this->capacity = $capacity;
            $this->weekday = $weekday;
            $this->startTime = $startTime;
            $this->endTime = $endTime;
        }

        private static function build(array $class) : ClassType {
            return new ClassType(
                $class['id'],
                $class['name'],
                $class['trainer_id'],
                $class['trainer_name'],
                $class['capacity'],
                ClassType::$weekdays[$class['weekday']],
                $class['start_time'],
                $class['end_time'],
            );
        }

        public static function getAllClassesTypes(PDO $db) : array {
            $stmt = $db->prepare('
                SELECT DISTINCT ClassType.id, ClassType.name, ClassType.trainer_id, 
                    CONCAT(User.first_name, " ", User.last_name) as trainer_name,
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
                SELECT ClassType.id, ClassType.name, ClassType.trainer_id, 
                    CONCAT(User.first_name, " ", User.last_name) as trainer_name,
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
    };
?>