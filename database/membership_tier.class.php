<?php 
  declare(strict_types = 1);

  class MembershipTier {
    private int $id;
    private string $name;
    private string $description;
    private float $monthly_fee;
    private int $booking_window_days;
    private bool $is_active;

    public function __construct(int $id, string $name, string $description, float $monthly_fee, int $booking_window_days, bool $is_active) {
      $this->id = $id;
      $this->name = $name;
      $this->description = $description;
      $this->monthly_fee = $monthly_fee;
      $this->booking_window_days = $booking_window_days;
      $this->is_active = $is_active;
    }

    public function getId() : int {
      return $this->id;
    }

    public function getName() : string {
      return $this->name;
    }

    public function getDescription() : string {
      return $this->description;
    }

    public function getMonthlyFee() : float {
      return $this->monthly_fee;
    }

    public function getBookingWindowDays() : int {
      return $this->booking_window_days;
    }

    public function isActive() : bool {
      return $this->is_active;
    }

    public function setName(string $name) : void {
      $this->name = $name;
    }

    public function setDescription(string $description) : void {
      $this->description = $description;
    }

    public function setMonthlyFee(float $monthly_fee) : void {
      $this->monthly_fee = $monthly_fee;
    }

    public function setBookingWindowDays(int $booking_window_days) : void {
      $this->booking_window_days = $booking_window_days;
    }

    public static function create(PDO $db, string $name, string $description, float $monthly_fee, int $booking_window_days) : int {
      $stmt = $db->prepare('INSERT INTO MembershipTier (name, description, monthly_fee, booking_window_days, is_active) VALUES (?, ?, ?, ?, ?)');
      $stmt->execute([
        $name,
        $description,
        $monthly_fee,
        $booking_window_days,
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
          $row['description'], 
          (float)$row['monthly_fee'], 
          (int)$row['booking_window_days'],
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
          $row['description'], 
          (float)$row['monthly_fee'], 
          (int)$row['booking_window_days'],
          (bool)$row['is_active']
        );
      }
      return $tiers;
    }

    public static function getMembershipTierById(PDO $db, int $id) : MembershipTier {
      $stmt = $db->prepare('SELECT * FROM MembershipTier WHERE id = ?');
      $stmt->execute([$id]);
      $row = $stmt->fetch();
      if (!$row) {
        throw new Exception('Membership tier not found.');
      }
      return new MembershipTier(
        (int)$row['id'], 
        $row['name'], 
        $row['description'], 
        (float)$row['monthly_fee'], 
        (int)$row['booking_window_days'],
        (bool)$row['is_active']
      );
    }

    public function save(PDO $db) : void {
      $stmt = $db->prepare('UPDATE MembershipTier SET name = ?, description = ?, monthly_fee = ?, booking_window_days = ?, is_active = ? WHERE id = ?');
      $stmt->execute([
        $this->name, 
        $this->description, 
        $this->monthly_fee, 
        $this->booking_window_days, 
        $this->is_active,
        $this->id
      ]);
    }

    public static function checkExistingMember(PDO $db, int $member_id) : ?array {
      $stmt = $db->prepare('SELECT id FROM Member WHERE id = ?');
      $stmt->execute([$member_id]);
      return $stmt->fetch();
    }

    public static function createMembership(PDO $db, int $member_id, int $tier_id, float $monthly_fee, int $duration, string $payment_type) : void {
      // First month insertion
      try {
        $db->beginTransaction();

        $stmt = $db->prepare("
          INSERT INTO Membership (member_id, start_timestamp, end_timestamp, status, 
          membership_tier_id, payment_due_timestamp, payment_amount, payment_status) 
          VALUES (?, datetime('now', 'localtime'), datetime('now', '+1 month', 'localtime'), ?, ?, datetime('now', 'localtime'), ?, ?)");
        $stmt->execute([$member_id, 'active', $tier_id, $monthly_fee, 'paid']);
        
        $db->commit();
      } catch (Exception $e) {
        $db->rollBack();
        throw new Exception('Error creating first month of membership: ' . $e->getMessage());
      }
      
      // Future months insertion
      if ($duration > 1) {
        try {
          $db->beginTransaction();
          for ($i = 1; $i < $duration; $i++) {
            $month_offset = '+' . $i . ' months';
            $next_month_offset = '+' . ($i + 1) . ' months';
            
            if ($payment_type === 'monthly') {
              $stmt = $db->prepare("
                INSERT INTO Membership (member_id, start_timestamp, end_timestamp, status, membership_tier_id,
                payment_due_timestamp, payment_amount, payment_status) 
                VALUES (?, datetime('now', ?, 'localtime'), datetime('now', ?, 'localtime'), ?, ?, datetime('now', ?, 'localtime'), ?, ?)");
              $stmt->execute([$member_id, $month_offset, $next_month_offset, 'pending', $tier_id, $month_offset, $monthly_fee, 'pending']);
            } else if ($payment_type === 'upfront') {
              $stmt = $db->prepare("
                INSERT INTO Membership (member_id, start_timestamp, end_timestamp, status, membership_tier_id, 
                payment_due_timestamp, payment_amount, payment_status) 
                VALUES (?, datetime('now', ?, 'localtime'), datetime('now', ?, 'localtime'), ?, ?, datetime('now', 'localtime'), ?, ?)");
              $stmt->execute([$member_id, $month_offset, $next_month_offset, 'planned', $tier_id, $monthly_fee, 'paid']);
            }
          }
          $db->commit();
        } catch (Exception $e) {
          if ($db->inTransaction()) $db->rollBack();
          throw new Exception('Error creating future months of membership: ' . $e->getMessage());
        }
      }
    }
  }

?>
