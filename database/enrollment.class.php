<?php
    declare(strict_types=1);

    class Enrollment {

        private static function checkEnrollmentLimit(PDO $db, int $memberId, int $maxEnrollments) : bool {

            $stmt = $db->prepare("
                SELECT COUNT(*) FROM Enrollment 
                WHERE member_id = ? AND status IN ('enrolled', 'waitlisted')
            ");
            $stmt->execute([$memberId]);
            $currentEnrollments = (int)$stmt->fetchColumn();

            return $currentEnrollments < $maxEnrollments;
        }

        private static function checkBookingWindow(PDO $db, int $bookingDays, int $classId) : bool {
            $stmt = $db->prepare('
                SELECT c.start_timestamp
                FROM Class c
                WHERE c.id = ?
            ');

            $stmt->execute([$classId]);
            $classStartTimestamp = $stmt->fetchColumn();
            $currentTimestamp = new DateTime();
            $classStartTime = new DateTime($classStartTimestamp);
            $interval = $currentTimestamp->diff($classStartTime);

            $daysUntilClass = (int)$interval->format('%a'); // %a gives total days
            return $daysUntilClass <= $bookingDays;
        }

        public static function create(PDO $db, int $memberId, int $classId, string $status) : void {
            // TODO: add here the other perks
            $stmt = $db->prepare('
                SELECT mt.enrollment_window_days, mt.max_enrollments
                FROM MembershipTier mt
                JOIN Membership m ON mt.id = m.membership_tier_id
                WHERE m.member_id = ? AND m.status = "active"
            ');

            $stmt->execute([$memberId]);
            $data = $stmt->fetch();
            $bookingWindowDays = (int)$data['enrollment_window_days'];

            if (!self::checkBookingWindow($db, $bookingWindowDays, $classId)) {
                throw new Exception("Your membership tier only allows you to book classes within {$bookingWindowDays} days of the class date.");
            }

            $maxEnrollments = (int)$data['max_enrollments'];
            if (!self::checkEnrollmentLimit($db, $memberId, $maxEnrollments)) {
                throw new Exception("You have reached the maximum number of enrollments for your membership tier - {$maxEnrollments} enrollments.");
            }

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

        public static function hasAttendedClassType(PDO $db, int $memberId, int $classTypeId) : bool {
            $stmt = $db->prepare("
                SELECT 1
                FROM Enrollment e
                JOIN Class c ON e.class_id = c.id
                WHERE e.member_id = ? AND c.class_type_id = ? AND e.status = 'attended'
            ");
            $stmt->execute([$memberId, $classTypeId]);
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

        public static function updateAllAttendedEnrollments(PDO $db, string $currentTimestamp) : void {
            $stmt = $db->prepare('
                UPDATE Enrollment 
                SET status = "attended", status_update_timestamp = ?
                WHERE status = "enrolled" AND class_id IN (
                    SELECT id FROM Class WHERE end_timestamp <= ? AND status = "completed"
                )
            ');
            $stmt->execute([$currentTimestamp, $currentTimestamp]);
        }

        public static function getNumAttendedClasses(PDO $db, int $memberId) : int {
            $stmt = $db->prepare('
                SELECT COUNT(*) as attended_count
                FROM Enrollment e
                JOIN Class c ON e.class_id = c.id
                WHERE e.member_id = ? AND e.status = "attended"
            ');
            $stmt->execute([$memberId]);
            $row = $stmt->fetch();
            return (int) ($row['attended_count'] ?? 0);
        }
    }
?>