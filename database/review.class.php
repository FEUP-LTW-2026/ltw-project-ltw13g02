<?php
  declare(strict_types = 1);

  class Review {
    private int $id;
    private int $member_id;
    private int $class_type_id;
    private string $comment;
    private int $rating;
    private string $review_timestamp;

    public function __construct(int $id, int $member_id, int $class_type_id, string $comment, int $rating, string $review_timestamp){
      $this->id = $id;
      $this->member_id = $member_id;
      $this->class_type_id = $class_type_id;
      $this->comment = $comment;
      $this->rating = $rating;
      $this->review_timestamp = $review_timestamp;
    }

    public function getId() : int {
      return $this->id;
    }

    public function getMemberId() : int {
      return $this->member_id;
    }

    public function getClassTypeId() : int {
      return $this->class_type_id;
    }

    public function getComment() : string {
      return $this->comment;
    }

    public function getRating() : int {
      return $this->rating;
    }

    public function getReviewTimestamp() : string {
      return $this->review_timestamp;
    }

    public static function hasReviewedClassTypeInCurrentMonth(PDO $db, int $memberId, int $classTypeId) : bool {
      $stmt = $db->prepare('
        SELECT 1
        FROM Review r
        WHERE r.member_id = ? AND r.class_type_id = ? AND r.review_timestamp >= datetime("now", "-1 month")
      ');
      $stmt->execute([$memberId, $classTypeId]);
      return $stmt->fetch() !== false;
    }

    public static function create(PDO $db, int $memberId, int $classTypeId, string $comment, int $rating) : void {
      $stmt = $db->prepare('
        INSERT INTO Review (review_text, rating, review_timestamp, class_type_id, member_id)
        VALUES (?, ?, datetime("now", "localtime"), ?, ?)
      ');
      $stmt->execute([$comment, $rating, $classTypeId, $memberId]);
    }

    public static function getReviewsByClassType(PDO $db, int $classTypeId) : array {
      $stmt = $db->prepare('
        SELECT u.username, u.first_name, u.last_name, r.review_text, r.rating, r.review_timestamp
        FROM Review r
        JOIN Member m ON r.member_id = m.id
        JOIN User u ON m.id = u.id
        WHERE r.class_type_id = ?
        ORDER BY r.review_timestamp DESC
      ');
      $stmt->execute([$classTypeId]);
      return $stmt->fetchAll();
    }

    public static function getRatingAndCountByClassType(PDO $db, int $classTypeId) : ?array {
      $stmt = $db->prepare('
        SELECT AVG(rating) as average_rating, COUNT(*) as review_counts
        FROM Review
        WHERE class_type_id = ?
      ');
      $stmt->execute([$classTypeId]);
      $result = $stmt->fetch();
      // If there are no reviews avg will return null and count will return 0, so we must check both
      if($result === false || $result['review_counts'] == 0) {
        return null;
      }
      return [$result['average_rating'], $result['review_counts']];
    }
  }
?>