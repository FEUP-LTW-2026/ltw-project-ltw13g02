<?php 
    declare(strict_types=1);

    class LazyCronJob {
        public static function execute() {
            require_once(__DIR__ . '/../database/connection.db.php');
            require_once(__DIR__ . '/../database/gymClass.class.php');
            require_once(__DIR__ . '/../database/enrollment.class.php');

            $db = getDatabaseConnection();

            // Memberships:
            // 1. Expire active memberships that have reached their end date
            // 2. Activate planned memberships that have reached their start date
            
            //MembershipTier::updateAllMembershipStatus($db);

            // Classes:
            // 1. Mark classes as completed if their end time has passed

            GymClass::updateAllClassesStatus($db, 'completed', date('Y-m-d H:i:s'));

            // Enrollments:
            // 1. Mark attended enrollments as completed if the class has ended and user enrolled

            Enrollment::updateAllAttendedEnrollments($db, date('Y-m-d H:i:s'));
        }
    }
?>