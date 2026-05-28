<?php 
    require_once(__DIR__ . '/../database/membership_tier_perk.class.php');
    class MembershipTierPerksMap {
        private array $map;

        public function __construct(PDO $db) {
            $this->map = [];
            $stmt = $db->query('SELECT id, membership_tier_id, description FROM MembershipTierPerk');
            while ($row = $stmt->fetch()) {
                $perk = new MembershipTierPerk((int)$row['id'], (int)$row['membership_tier_id'], (string)$row['description']);
                if (!isset($this->map[$perk->getMembershipTierId()])) {
                    $this->map[$perk->getMembershipTierId()] = [];
                }
                $this->map[$perk->getMembershipTierId()][] = $perk;
            }
        }

        public function getPerksForMembershipTierId(int $membershipTierId) : array {
            return $this->map[$membershipTierId] ?? [];
        }
    }

?>