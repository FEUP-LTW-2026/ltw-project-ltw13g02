<?php
    declare(strict_types = 1);

    class GymClass {
        // TODO: create getters and setters?
        public int $id;
        public int $classTypeId;
        public int $trainerId;
        public string $name;
        public string $trainerName;
        public string $startTimestamp;
        public string $endTimestamp;
        public string $status;
        public int $currentEnrollment;
        public int $capacity;

        public function __construct(int $id, int $classTypeId, int $trainerId, string $name, string $trainerName, string $startTimestamp, string $endTimestamp, string $status, int $currentEnrollment = 0, int $capacity = 0) {
            $this->id = $id;
            $this->classTypeId = $classTypeId;
            $this->trainerId = $trainerId;
            $this->name = $name;
            $this->trainerName = $trainerName;
            $this->startTimestamp = $startTimestamp;
            $this->endTimestamp = $endTimestamp;
            $this->status = $status;
            $this->currentEnrollment = $currentEnrollment;
            $this->capacity = $capacity;
        }
        
        public static function getClassById(PDO $db, int $classId) : ?GymClass {
            $stmt = $db->prepare('
                SELECT Class.id, Class.class_type_id, ClassType.trainer_id, ClassType.name, ClassType.capacity,
                    CONCAT(User.first_name, " ", User.last_name) as trainer_name,
                    Class.start_timestamp, Class.end_timestamp, Class.status,
                    COUNT(Enrollment.id) as current_enrollment
                FROM Class
                JOIN ClassType ON Class.class_type_id = ClassType.id
                LEFT JOIN User ON ClassType.trainer_id = User.id
                LEFT JOIN Enrollment ON Class.id = Enrollment.class_id AND Enrollment.status = "enrolled"
                WHERE Class.id = ?
                GROUP BY Class.id
            ');

            $stmt->execute([$classId]);
            $row = $stmt->fetch();

            if (empty($row)) return null;

            return new GymClass(
                $row['id'],
                $row['class_type_id'],
                $row['trainer_id'],
                $row['name'],
                $row['trainer_name'],
                $row['start_timestamp'],
                $row['end_timestamp'],
                $row['status'],
                $row['current_enrollment'],
                $row['capacity']
            );
        }

        public static function getClassesByTypeId(PDO $db, int $classTypeId) : array {
            $stmt = $db->prepare('
                SELECT Class.id, Class.class_type_id, ClassType.trainer_id, ClassType.name, ClassType.capacity,
                    CONCAT(User.first_name, " ", User.last_name) AS trainer_name,
                    Class.start_timestamp, Class.end_timestamp, Class.status,
                    (SELECT COUNT(*) FROM Enrollment WHERE Enrollment.class_id = Class.id AND Enrollment.status = "enrolled") AS current_enrollment
                FROM Class
                JOIN ClassType ON Class.class_type_id = ClassType.id
                LEFT JOIN User ON ClassType.trainer_id = User.id
                WHERE Class.class_type_id = ? AND Class.status = "scheduled"
                ORDER BY Class.start_timestamp ASC
            ');

            $stmt->execute([$classTypeId]);
            
            $classes = [];
            while ($row = $stmt->fetch()) {
                $classes[] = new GymClass(
                    $row['id'],
                    $row['class_type_id'],
                    $row['trainer_id'],
                    $row['name'],
                    $row['trainer_name'],
                    $row['start_timestamp'],
                    $row['end_timestamp'],
                    $row['status'],
                    $row['current_enrollment'],
                    $row['capacity']
                );
            }

            return $classes;
        }

        public static function scheduleClass(PDO $db, int $classTypeId, string $startTimestamp, string $endTimestamp) : void {
            $stmt = $db->prepare('
                INSERT INTO Class (class_type_id, start_timestamp, end_timestamp, status)
                VALUES (?, ?, ?, "scheduled")
            ');

            $stmt->execute([$classTypeId, $startTimestamp, $endTimestamp]);
        }

        public static function getEnrolledMembers(PDO $db, int $classId) : array {
            $stmt = $db->prepare('
                SELECT User.id, User.first_name, User.last_name, User.username, User.email, Enrollment.status
                FROM Enrollment
                JOIN User ON Enrollment.member_id = User.id
                WHERE Enrollment.class_id = ? AND Enrollment.status IN ("enrolled", "waitlisted")
                ORDER BY Enrollment.status ASC, User.first_name ASC, User.last_name ASC
            ');

            $stmt->execute([$classId]);
            return $stmt->fetchAll();
        }

        public function isAvailable(int $capacity) : bool {
            return $this->currentEnrollment < $capacity && $this->status === 'scheduled';
        }

        public function hasRemainingCapacity() : bool {
            return $this->currentEnrollment < $this->capacity;
        }
    }
?>
