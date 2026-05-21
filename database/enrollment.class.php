<?php
    declare(strict_types=1);

    class Enrollment {
        public static function create(PDO $db, int $memberId, int $classId, string $status) : void {
            $stmt = $db->prepare("
                INSERT INTO Enrollment (member_id, class_id, status, status_update_timestamp)
                VALUES (?, ?, ?, datetime('now', 'localtime'))
            ");
            $stmt->execute([$memberId, $classId, $status]);
        }

        public static function getEnrolledClasses(PDO $db, int $memberId) : array {
            $stmt = $db->prepare('
                SELECT Class.id, Class.class_type_id, ClassType.trainer_id, ClassType.name, ClassType.capacity,
                    CONCAT(User.first_name, " ", User.last_name) as trainer_name,
                    Class.start_timestamp, Class.end_timestamp, Class.status,
                    (SELECT COUNT(*) FROM Enrollment WHERE Enrollment.class_id = Class.id AND Enrollment.status = "enrolled") as current_enrollment
                FROM Class
                JOIN ClassType ON Class.class_type_id = ClassType.id
                LEFT JOIN User ON ClassType.trainer_id = User.id
                JOIN Enrollment ON Class.id = Enrollment.class_id
                WHERE Enrollment.member_id = ? AND Enrollment.status = "enrolled"
                ORDER BY Class.start_timestamp ASC
            ');

            $stmt->execute([$memberId]);
            
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

        public static function getWaitlistedClasses(PDO $db, int $memberId) : array {
            $stmt = $db->prepare('
                SELECT Class.id, Class.class_type_id, ClassType.trainer_id, ClassType.name, ClassType.capacity,
                    CONCAT(User.first_name, " ", User.last_name) as trainer_name,
                    Class.start_timestamp, Class.end_timestamp, Class.status,
                    (SELECT COUNT(*) FROM Enrollment WHERE Enrollment.class_id = Class.id AND Enrollment.status = "enrolled") as current_enrollment
                FROM Class
                JOIN ClassType ON Class.class_type_id = ClassType.id
                LEFT JOIN User ON ClassType.trainer_id = User.id
                JOIN Enrollment ON Class.id = Enrollment.class_id
                WHERE Enrollment.member_id = ? AND Enrollment.status = "waitlisted"
                ORDER BY Class.start_timestamp ASC
            ');

            $stmt->execute([$memberId]);
            
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

        public static function isEnrolled(PDO $db, int $memberId, int $classId) : bool {
            $stmt = $db->prepare("
                SELECT 1 FROM Enrollment 
                WHERE member_id = ? AND class_id = ? AND status = 'enrolled'
            ");
            $stmt->execute([$memberId, $classId]);
            return $stmt->fetch() !== false;
        }

        public static function isInWaitlist(PDO $db, int $memberId, int $classId) : bool {
            $stmt = $db->prepare("
                SELECT 1 FROM Enrollment 
                WHERE member_id = ? AND class_id = ? AND status = 'waitlisted'
            ");
            $stmt->execute([$memberId, $classId]);
            return $stmt->fetch() !== false;
        }

        public static function cancelEnrollment(PDO $db, int $memberId, int $classId) : void {
            $stmt = $db->prepare("
                UPDATE Enrollment 
                SET status = 'cancelled', status_update_timestamp = datetime('now', 'localtime')
                WHERE member_id = ? AND class_id = ? AND status = 'enrolled'
            ");
            $stmt->execute([$memberId, $classId]);
        }

        public static function leaveWaitlist(PDO $db, int $memberId, int $classId) : void {
            $stmt = $db->prepare("
                UPDATE Enrollment 
                SET status = 'cancelled', status_update_timestamp = datetime('now', 'localtime')
                WHERE member_id = ? AND class_id = ? AND status = 'waitlisted'
            ");
            $stmt->execute([$memberId, $classId]);
        }
    
        public static function addToWaitlist(PDO $db, int $memberId, int $classId) : void {
            self::create($db, $memberId, $classId, 'waitlisted');
        }
    }
?>