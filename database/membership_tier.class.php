<?php 
  declare(strict_types = 1);

  class MembershipTier {
    private int $id;
    private string $name;
    private float $monthly_fee;
    private int $enrollment_window_days;
    private int $max_enrollments;
    private bool $is_active;

    public function __construct(int $id, string $name, float $monthly_fee, int $enrollment_window_days, int $max_enrollments, bool $is_active) {
      $this->id = $id;
      $this->name = $name;
      $this->monthly_fee = $monthly_fee;
      $this->enrollment_window_days = $enrollment_window_days;
      $this->max_enrollments = $max_enrollments;
      $this->is_active = $is_active;
    }

    public function getId() : int {
      return $this->id;
    }

    public function getName() : string {
      return $this->name;
    }

    public function getMonthlyFee() : float {
      return $this->monthly_fee;
    }

    public function getBookingWindowDays() : int {
      return $this->enrollment_window_days;
    }

    public function getMaxEnrollments() : int {
      return $this->max_enrollments;
    }

    public function isActive() : bool {
      return $this->is_active;
    }

    public function setName(string $name) : void {
      $this->name = $name;
    }

    public function setMonthlyFee(float $monthly_fee) : void {
      $this->monthly_fee = $monthly_fee;
    }

    public function setBookingWindowDays(int $enrollment_window_days) : void {
      $this->enrollment_window_days = $enrollment_window_days;
    }

    public function setMaxEnrollments(int $max_enrollments) : void {
      $this->max_enrollments = $max_enrollments;
    }

    public static function create(PDO $db, string $name, float $monthly_fee, int $enrollment_window_days, int $max_enrollments) : int {
      $stmt = $db->prepare('INSERT INTO MembershipTier (name, monthly_fee, enrollment_window_days, max_enrollments, is_active) VALUES (?, ?, ?, ?, ?)');
      $stmt->execute([
        $name,
        $monthly_fee,
        $enrollment_window_days,
        $max_enrollments,
        1
      ]);

      return (int)$db->lastInsertId();
    }

    public static function getAllMembershipTiers(PDO $db) : array {
      $stmt = $db->prepare('SELECT * FROM MembershipTier ORDER BY is_active DESC, monthly_fee ASC');
      $stmt->execute();
      $tiers = [];
      while ($row = $stmt->fetch()) {
        $tiers[] = new MembershipTier(
          (int)$row['id'], 
          $row['name'], 
          (float)$row['monthly_fee'], 
          (int)$row['enrollment_window_days'],
          (int)$row['max_enrollments'],
          (bool)$row['is_active']
        );
      }
      return $tiers;
    }

    public static function getActiveMembershipTiers(PDO $db) : array {
      $stmt = $db->prepare('SELECT * FROM MembershipTier WHERE is_active = 1 ORDER BY monthly_fee ASC');
      $stmt->execute();
      $tiers = [];
      while ($row = $stmt->fetch()) {
        $tiers[] = new MembershipTier(
          (int)$row['id'], 
          $row['name'], 
          (float)$row['monthly_fee'], 
          (int)$row['enrollment_window_days'],
          (int)$row['max_enrollments'],
          (bool)$row['is_active']
        );
      }
      return $tiers;
    }

    public static function getMembershipTierById(PDO $db, int $id) : MembershipTier {
      $stmt = $db->prepare('SELECT * FROM MembershipTier WHERE id = ?');
      $stmt->execute([$id]);
      $row = $stmt->fetch();
      return new MembershipTier(
        (int)$row['id'], 
        $row['name'], 
        (float)$row['monthly_fee'], 
        (int)$row['enrollment_window_days'],
        (int)$row['max_enrollments'],
        (bool)$row['is_active']
      );
    }

    public function save(PDO $db) : void {
      $stmt = $db->prepare('UPDATE MembershipTier SET name = ?, monthly_fee = ?, enrollment_window_days = ?, max_enrollments = ?, is_active = ? WHERE id = ?');
      $stmt->execute([
        $this->name, 
        $this->monthly_fee, 
        $this->enrollment_window_days, 
        $this->max_enrollments,
        $this->is_active,
        $this->id
      ]);
    }

    public static function checkExistingMember(PDO $db, int $member_id) : bool {
      $stmt = $db->prepare('SELECT id FROM Member WHERE id = ?');
      $stmt->execute([$member_id]);
      return $stmt->fetch() !== false;
    }

    public static function hasValidMemberships(PDO $db, int $member_id) : bool {
      $stmt = $db->prepare('SELECT id FROM Membership WHERE member_id = ? AND status IN ("active", "planned", "pending")');
      $stmt->execute([$member_id]);
      return $stmt->fetch() !== false;
    }

    public static function createMembership(PDO $db, int $member_id, int $tier_id, float $monthly_fee, int $duration, string $payment_type) : void {
      try {
        $db->beginTransaction();

        $timezone = new DateTimeZone('Europe/Lisbon');
        $now = new DateTimeImmutable('now', $timezone);
        $baseStart = $now;

        $lastMembership = false;

        // If not already a member, update the Member table
        if (!self::checkExistingMember($db, $member_id)) {
          $stmt = $db->prepare('INSERT INTO Member (id) VALUES (?)');
          $stmt->execute([$member_id]);
        }
        else {
          $stmt = $db->prepare("
            SELECT end_timestamp FROM Membership 
            WHERE member_id = ? AND status IN ('active', 'planned', 'pending') 
            ORDER BY end_timestamp DESC LIMIT 1
          ");
          $stmt->execute([$member_id]);
          $lastMembership = $stmt->fetch();

          if ($lastMembership !== false && !empty($lastMembership['end_timestamp'])) {
            $baseStart = new DateTimeImmutable($lastMembership['end_timestamp'], $timezone);
          }
        }


        $firstStart = $baseStart;
        $firstEnd = $firstStart->modify('+1 month');
        $firstStatus = $firstStart <= $now ? 'active' : 'planned';
        $paymentStatus = 'paid';

        $stmt = $db->prepare("
          INSERT INTO Membership (member_id, start_timestamp, end_timestamp, status, 
          membership_tier_id, payment_due_timestamp, payment_amount, payment_status) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        // First membership is pending upon membership renewal 
        if ($payment_type === 'monthly' && $lastMembership !== false) {
          $firstStatus = 'pending';
          $paymentStatus = 'pending';
        }

        $stmt->execute([
          $member_id,
          $firstStart->format('Y-m-d H:i:s'),
          $firstEnd->format('Y-m-d H:i:s'),
          $firstStatus,
          $tier_id,
          $now->format('Y-m-d H:i:s'),
          $monthly_fee,
          $paymentStatus
        ]);

        if ($duration > 1) {
          for ($i = 1; $i < $duration; $i++) {
            $monthStart = $baseStart->modify('+' . $i . ' months');
            $monthEnd = $monthStart->modify('+1 month');

            if ($payment_type === 'monthly') {
              $stmt = $db->prepare("
                INSERT INTO Membership (member_id, start_timestamp, end_timestamp, status, membership_tier_id,
                payment_due_timestamp, payment_amount, payment_status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
              $stmt->execute([
                $member_id,
                $monthStart->format('Y-m-d H:i:s'),
                $monthEnd->format('Y-m-d H:i:s'),
                'pending',
                $tier_id,
                $monthStart->format('Y-m-d H:i:s'),
                $monthly_fee,
                'pending'
              ]);
            } else if ($payment_type === 'upfront') {
              $stmt = $db->prepare("
                INSERT INTO Membership (member_id, start_timestamp, end_timestamp, status, membership_tier_id, 
                payment_due_timestamp, payment_amount, payment_status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
              $stmt->execute([
                $member_id,
                $monthStart->format('Y-m-d H:i:s'),
                $monthEnd->format('Y-m-d H:i:s'),
                'planned',
                $tier_id,
                $now->format('Y-m-d H:i:s'),
                $monthly_fee,
                'paid'
              ]);
            }
          }
        }

        $db->commit();
      } catch (Exception $e) {
        if ($db->inTransaction()) $db->rollBack();
        throw new Exception('Error creating membership: ' . $e->getMessage());
      }
    }

    public static function expirePastActiveMemberships(PDO $db, string $newStatus, string $timestamp) : void {
      $stmt = $db->prepare("
        UPDATE Membership 
        SET status = ? 
        WHERE end_timestamp < ? AND status = 'active'
      ");
      $stmt->execute([$newStatus, $timestamp]);
    }

    public static function activatePlannedMemberships(PDO $db, string $newStatus, string $timestamp) : void {
      $stmt = $db->prepare("
        UPDATE Membership 
        SET status = ? 
        WHERE start_timestamp <= ? AND status = 'planned'
      ");
      $stmt->execute([$newStatus, $timestamp]);
    }
  }

?>
