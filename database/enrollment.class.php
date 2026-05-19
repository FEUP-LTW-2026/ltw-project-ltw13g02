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
    }
?>