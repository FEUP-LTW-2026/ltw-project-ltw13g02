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

    public static function getMembershipTiers(PDO $db) : array {
      $stmt = $db->prepare('SELECT * FROM MembershipTier ORDER BY monthly_fee ASC');
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
  }

?>
