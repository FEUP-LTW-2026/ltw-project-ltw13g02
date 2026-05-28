<?php 
    class MembershipTierPerk {
        private int $id;
        private int $membership_tier_id;
        private string $description;

        public function __construct(int $id, int $membership_tier_id, string $description) {
            $this->id = $id;
            $this->membership_tier_id = $membership_tier_id;
            $this->description = $description;
        }

        public function getId() : int {
            return $this->id;
        }

        public function getMembershipTierId() : int {
            return $this->membership_tier_id;
        }

        public function getDescription() : string {
            return $this->description;
        }

        public static function create(PDO $db, int $membership_tier_id, string $description) : void {
            $stmt = $db->prepare('INSERT INTO MembershipTierPerk (membership_tier_id, description) VALUES (?, ?)');
            $stmt->execute([$membership_tier_id, $description]);
        }

        public static function deleteByMembershipTierId(PDO $db, int $membership_tier_id) : void {
            $stmt = $db->prepare('DELETE FROM MembershipTierPerk WHERE membership_tier_id = ?');
            $stmt->execute([$membership_tier_id]);
        }
    }
?>