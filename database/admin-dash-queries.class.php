<?php 
    class QueriesForAdminDashboard {
        public static function getMonthlyRevenueByTier(PDO $db): array {
            $stmt = $db->prepare("
                SELECT
                    mt.name AS tier,
                    COUNT(m.id) AS active_memberships,
                    SUM(m.payment_amount) AS monthly_revenue
                FROM Membership m
                JOIN MembershipTier mt ON m.membership_tier_id = mt.id
                WHERE m.payment_status = 'paid'
                AND strftime('%Y-%m', m.start_timestamp) = strftime('%Y-%m', 'now')
                GROUP BY mt.name
                ORDER BY monthly_revenue DESC;
            ");
            $stmt->execute();
            return $stmt->fetchAll();
        }

        public static function getTotalMonthlyRevenue(PDO $db): float {
            $stmt = $db->prepare("
                SELECT
                    SUM(m.payment_amount) AS total_monthly_revenue
                FROM Membership m
                WHERE m.payment_status = 'paid'
                AND strftime('%Y-%m', m.start_timestamp) = strftime('%Y-%m', 'now');
            ");
            $stmt->execute();
            $result = $stmt->fetch();
            return $result['total_monthly_revenue'] ?? 0.0;
        }

        public static function getActiveMembershipsByTier(PDO $db): array {
            $stmt = $db->prepare("
                SELECT
                    mt.name AS tier,
                    COUNT(m.id) AS active_memberships
                FROM Membership m
                JOIN MembershipTier mt ON m.membership_tier_id = mt.id
                WHERE m.payment_status = 'paid'
                AND m.start_timestamp <= datetime('now', 'localtime')
                AND m.end_timestamp >= datetime('now', 'localtime')
                GROUP BY mt.name
                ORDER BY active_memberships DESC;
            ");
            $stmt->execute();
            return $stmt->fetchAll();
        }

        // Top 5 enrolled classes for a given year-month (format: 'YYYY-MM').
        // If $yearMonth is null, defaults to the current month.
        public static function getMostAttendedClassesThisMonth(PDO $db, int $limit = 3, ?string $yearMonth = null): array {
            $yearMonth = $yearMonth ?? date('2026-06');
            $stmt = $db->prepare(
                "
                SELECT
                    c.id AS class_id,
                    ct.name AS class_name,
                    u.first_name || ' ' || u.last_name AS trainer_name,
                    c.start_timestamp,
                    c.end_timestamp,
                    COUNT(e.id) AS enrollments
                FROM Class c
                JOIN ClassType ct ON c.class_type_id = ct.id
                JOIN Trainer t ON ct.trainer_id = t.id
                JOIN User u ON t.id = u.id
                JOIN Enrollment e ON e.class_id = c.id AND e.status = 'attended'
                WHERE strftime('%Y-%m', c.start_timestamp) = ?
                GROUP BY c.id, ct.name, u.first_name, u.last_name, c.start_timestamp, c.end_timestamp
                ORDER BY enrollments DESC, c.start_timestamp ASC
                LIMIT ?
                "
            );
            $stmt->execute([$yearMonth, $limit]);
            return $stmt->fetchAll();
        }

        public static function getBestReviewedClasses(PDO $db, int $limit = 3): array {
            $stmt = $db->prepare(
                "SELECT ct.name AS class_name, AVG(r.rating) AS average_rating, COUNT(r.id) AS review_count
                FROM Review r
                JOIN ClassType ct ON r.class_type_id = ct.id
                GROUP BY ct.name
                HAVING review_count > 0
                ORDER BY average_rating DESC, review_count DESC
                LIMIT ?"
            );
            $stmt->execute([$limit]);
            return $stmt->fetchAll();
        }

        public static function equipmentsInMaintenance(PDO $db): array {
            $stmt = $db->prepare("SELECT et.name as equipment_type_name, e.name as equipment_name 
            FROM Equipment e 
            JOIN EquipmentType et ON e.equipment_type_id = et.id
            WHERE e.status = 'maintenance'");
            $stmt->execute();
            return $stmt->fetchAll();
        }

        public static function getMostBookedEquipmentThisMonth(PDO $db, int $limit = 5, ?string $yearMonth = null): array {
            $yearMonth = $yearMonth ?? date('Y-m');
            $stmt = $db->prepare(
                "SELECT eq.id AS equipment_id, et.name as equipment_type_name, eq.name AS equipment_name, COUNT(eb.id) AS bookings
                FROM EquipmentBooking eb
                JOIN Equipment eq ON eb.equipment_id = eq.id
                LEFT JOIN EquipmentType et ON eq.equipment_type_id = et.id
                WHERE strftime('%Y-%m', eb.booking_date) = ?
                GROUP BY eq.id, eq.name, et.name
                ORDER BY bookings DESC
                LIMIT ?"
            );
            $stmt->execute([$yearMonth, $limit]);
            return $stmt->fetchAll();
        }

        
    }
?>