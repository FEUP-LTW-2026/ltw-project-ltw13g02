<?php 
  declare(strict_types = 1);

  require_once(__DIR__ . '/../database/classType.class.php');
  require_once(__DIR__ . '/../database/gymClass.class.php');
?>

<?php function drawClassIntro(ClassType $classType, Session $session, ?array $classTypeRating) { ?>
  <div class="intro">
    <section class="container">
      <div id="class-hero">
        <p class="detail"><?= $classType->weekday ?></p>
        <h1><?= $classType->name ?></h1>
        <?php if($classTypeRating === null) { ?>
          <p class="rating">No reviews yet</p>
        <?php } else { ?>
          <p class="rating">
            <?php for($i = 0; $i < floor($classTypeRating[0]); $i++) { ?>
              <span class="material-symbols-outlined">⭐</span>
            <?php } ?>
            <?= number_format($classTypeRating[0], 1) ?> / 5 (<?= $classTypeRating[1] ?> reviews)
          </p>
        <?php } ?>
        <p class="time">
            <time datetime="19:00">7:00 PM</time> -
            <time datetime="20:00">8:00 PM</time>
        </p>
        <p class="trainer-info">
          Trainer:
          <a class="trainer" href="../pages/trainer.php?id=<?= $classType->trainerId ?>"><?= $classType->trainerName ?></a>
        </p>
        <div class="stats">
          <p>
            <span class="material-symbols-outlined">timer</span>
            <time datetime="PT60M">60 min</time>
          </p>

          <p>
            <span class="material-symbols-outlined">person</span>
            <span><?= $classType->capacity ?></span>
          </p>
        </div>
        <?php
        if($session->isLoggedIn()) {
            ?>
            <a class="btn large blue" href="#enrollment">Join Class</a>
            <?php
        }
        ?>
      </div>
      <img src="https://picsum.photos/800/600" alt="class image" class="hero-image" >
    </section>
  </div>
<?php } ?>

<?php function classInEnrolledClasses(int $classId, array $enrolledClasses) : bool {
    foreach ($enrolledClasses as $enrolledClass) {
        if ($enrolledClass->id === $classId) {
            return true;
        }
    }
    return false;
}
?>

<?php function drawClassCardInfo(GymClass $class) { ?>
  <div class="class-info">
    <h3><?= $class->name ?></h3>
    <p class="date-time">
      <span class="material-symbols-outlined">event</span>
      <?= date('M j, Y g:i A', strtotime($class->startTimestamp)) ?>
    </p>
    <p class="enrollment-status">
      <span class="material-symbols-outlined">person</span>
      <?= $class->currentEnrollment . '/' . $class->capacity ?> enrolled
    </p>
  </div>
<?php } ?>

<?php function drawClassEnrollmentList(array $classes, array $enrolledClasses, bool $isMember, bool $isTrainer) { ?>
  <div id="enrollment">
    <section class="container">
      <h2>Available Classes</h2>
      <ul class="classes-enrollment">
        <?php foreach ($classes as $class) { ?>
          <li class="card">
            <?php drawClassCardInfo($class); ?>

            <?php if ($isMember && !classInEnrolledClasses($class->id, $enrolledClasses)) { ?>
              <form action="../actions/action_enrollment.php" method="post">
                <input type="hidden" name="class_id" value="<?= $class->id ?>" />
                <button class="btn medium blue" type="submit">Enroll Now</button>
              </form>
            <?php } else if ($isMember && classInEnrolledClasses($class->id, $enrolledClasses)) { ?>
              <form action="../actions/action_cancel_enrollment.php" method="post">
                <input type="hidden" name="class_id" value="<?= $class->id ?>" />
                <button class="btn medium warning outline" type="submit">Cancel Enrollment</button>
              </form>
            <?php } else if (!$isTrainer) { ?>
              <a class="btn medium blue" href="../pages/index.php#membership">Enroll Now</a>
            <?php } ?>
          </li>
        <?php } ?>
      </ul>
    </section>
  </div>
<?php } ?>

<?php function drawReview(string $username, string $firstName, string $lastName, int $rating, string $comment, string $timestamp) { ?>
  <article class="card review">
    <h3><?= $username ?> <span><?= $firstName . ' ' . $lastName ?></span></h3>
    <p><?= $rating .  "/5" ?></p>
    <p id="review-text"><?= $comment ?></p>
    <p id="timestamp"><?= $timestamp ?></p>
  </article>
<?php } ?>

<?php function drawReviewForm(int $classTypeId) { ?>
  <form class="comment-form" action="../actions/action_sumbitReview.php" method="POST">
    <h3>Leave a comment</h3>

    <label>
      Rating
      <select name="rating" required>
        <option value="" disabled selected>
            Select a rating
        </option>
        <option value="5">5 - Excellent</option>
        <option value="4">4 - Very Good</option>
        <option value="3">3 - Good</option>
        <option value="2">2 - Fair</option>
        <option value="1">1 - Poor</option>
      </select>
    </label>

    <label>
      Comment
      <textarea name="comment" placeholder="Your feedback" required></textarea>
    </label>

    <input type="hidden" name="class_type_id" value="<?= $classTypeId ?>" />
    <button class="btn medium blue" type="submit">Submit</button>
  </form>
<?php } ?>

<?php function drawReviewSection(ClassType $classType, Session $session, array $reviews) { ?>
  <div id="reviews">
    <section class="container">
      <h2>Reviews</h2>
      <?php if($session->isLoggedIn()) { ?>
        <p class="detail">LEAVE US YOUR FEEDBACK</p>
      <?php } ?>
      <div id="reviews-list">
        <?php foreach ($reviews as $review) {
          drawReview($review['username'], $review['first_name'], $review['last_name'], $review['rating'], $review['review_text'], $review['review_timestamp']);
        } ?>
      </div>
      <?php 
        if($session->isLoggedIn()) {
            drawReviewForm($classType->id);
        } 
      ?>
    </section>
  </div>
<?php } ?>

<?php function drawClassPage(Session $session, ClassType $classType, array $classes, array $enrolledClasses, array $reviews, ?array $classTypeRating) {
    drawClassIntro($classType, $session, $classTypeRating);
    if($session->isLoggedIn()) {
        drawClassEnrollmentList($classes, $enrolledClasses, $session->isMember(), $session->isTrainer());
    }
    drawReviewSection($classType, $session, $reviews);
} ?>