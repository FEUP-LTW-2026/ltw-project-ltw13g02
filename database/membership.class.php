<?php 
  	declare(strict_types = 1);

    class Membership {
        public int $id;
        public int $membershipTierId;
        public string $startTimestamp;
        public string $endTimestamp;
        public string $status;
        public string $paymentStatus;
        public string $tierName;
        public string $tierDescription;
        public float $monthlyFee;
        public int $bookingWindowDays;

        public function __construct(int $id, int $membershipTierId, string $startTimestamp, string $endTimestamp, string $status, string $paymentStatus, string $tierName, string $tierDescription, float $monthlyFee, int $bookingWindowDays) {
            $this->id = $id;
            $this->membershipTierId = $membershipTierId;
            $this->startTimestamp = $startTimestamp;
            $this->endTimestamp = $endTimestamp;
            $this->status = $status;
            $this->paymentStatus = $paymentStatus;
            $this->tierName = $tierName;
            $this->tierDescription = $tierDescription;
            $this->monthlyFee = $monthlyFee;
            $this->bookingWindowDays = $bookingWindowDays;
        }

        public static function getMemberMemberships(PDO $db, int $memberId): array {
            $stmt = $db->prepare('
                SELECT m.id, m.membership_tier_id AS membershipTierId, m.start_timestamp AS startTimestamp, m.end_timestamp AS endTimestamp, m.status, m.payment_status AS paymentStatus, mt.name AS tierName, mt.description AS tierDescription, mt.monthly_fee AS monthlyFee, mt.booking_window_days AS bookingWindowDays
                FROM Membership m
                JOIN MembershipTier mt ON m.membership_tier_id = mt.id
                WHERE m.member_id = ?
                ORDER BY m.start_timestamp ASC
            ');
            $stmt->execute([$memberId]);

            $memberships = [];

            foreach ($stmt->fetchAll() as $row) {
				$memberships[] = new Membership(
					$row['id'],
					$row['membershipTierId'],
					$row['startTimestamp'],
					$row['endTimestamp'],
					$row['status'],
					$row['paymentStatus'],
					$row['tierName'],
					$row['tierDescription'],
					$row['monthlyFee'],
					$row['bookingWindowDays']
				);
			}
			return $memberships;
        }

		public static function cancelMembership(PDO $db, int $membershipId): void {
			$stmt = $db->prepare('UPDATE Membership SET status = "cancelled" WHERE id = ?');
			$stmt->execute([$membershipId]);
		}
    }
?>