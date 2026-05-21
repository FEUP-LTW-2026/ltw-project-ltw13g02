<?php
    declare(strict_types=1);

    class Enrollment {
        static function create(PDO $db, int $memberId, int $classId, string $status) : void {
            $stmt = $db->prepare("
                INSERT INTO Enrollment (member_id, class_id, status, status_update_timestamp)
                VALUES (?, ?, ?, datetime('now', 'localtime'))
            ");
            $stmt->execute([$memberId, $classId, $status]);
        }

        static function isAlreadyEnrolled(PDO $db, int $memberId, int $classId) : bool {
            $stmt = $db->prepare("
                SELECT 1 FROM Enrollment 
                WHERE member_id = ? AND class_id = ? AND status IN ('enrolled', 'waitlisted')
            ");
            $stmt->execute([$memberId, $classId]);
            return $stmt->fetch() !== false;
        }

        static function cancelEnrollment(PDO $db, int $memberId, int $classId) : void {
            $stmt = $db->prepare("
                UPDATE Enrollment 
                SET status = 'cancelled', status_update_timestamp = datetime('now', 'localtime')
                WHERE member_id = ? AND class_id = ? AND status IN ('enrolled', 'waitlisted')
            ");
            $stmt->execute([$memberId, $classId]);
        }
    }
?>