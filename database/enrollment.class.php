<?php
    declare(strict_types=1);

    class Enrollment {
        static function create(PDO $db, int $memberId, int $classId, string $status) : void {
            $stmt = $db->prepare("
                INSERT INTO Enrollment (member_id, class_id, status, status_update_timestamp)
                VALUES (?, ?, ?, datetime('now'))
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

        static function hasAttendedClassType(PDO $db, int $memberId, int $classTypeId) : bool {
            $stmt = $db->prepare("
                SELECT 1
                FROM Enrollment e
                JOIN Class c ON e.class_id = c.id
                WHERE e.member_id = ? AND c.class_type_id = ? AND e.status = 'attended'
            ");
            $stmt->execute([$memberId, $classTypeId]);
            return $stmt->fetch() !== false;
        }
    }
?>