<?php 
  declare(strict_types = 1);

  require_once(__DIR__ . '/../database/classType.class.php');
  require_once(__DIR__ . '/../database/gymClass.class.php');
?>

<?php
// Helper function to calculate class duration in minutes
function calculateDurationMinutes(string $startTime, string $endTime) : int {
    $start = new DateTime($startTime);
    $end = new DateTime($endTime);
    $interval = $start->diff($end);
    return (int)$interval->format('%i') + ((int)$interval->format('%h') * 60);
} ?>

<?php function drawClassTrainer(int $trainerId, string $trainerName) { ?>
  <p>
    <span class="material-symbols-outlined">sports</span>
    Trainer: <a class="trainer" href="../pages/trainer.php?id=<?= $trainerId; ?>"><?= $trainerName; ?></a>
  </p>
<?php } ?>

<?php function drawClassDuration(string $startTime, string $endTime) { ?>
  <p>
    <span class="material-symbols-outlined">timer</span>
    <?= calculateDurationMinutes($startTime, $endTime) ?> min
  </p>
<?php } ?>

<?php function drawClassTypeCapacity(int $capacity) { ?>
  <p>
    <span class="material-symbols-outlined">person</span>
    Capacity: <?= $capacity ?>
  </p>
<?php } ?>

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

        <?php
          drawClassTime($classType->startTime, $classType->endTime); 
          drawClassTrainer($classType->trainerId, $classType->trainerName);
        ?>
        <div class="stats">
          <?php
            drawClassDuration($classType->startTime, $classType->endTime);
            drawClassTypeCapacity($classType->capacity);
          ?>
        </div>
        <?php
        if($session->isLoggedIn()) {
            ?>
            <a class="btn large blue" href=<?= $session->isMember() ? "#enrollment" : "../pages/index.php#membership" ?>>Join Class</a>
            <?php
        }
        ?>
      </div>
      <img src="https://picsum.photos/800/600" alt="class image" class="hero-image" >
    </section>
  </div>
<?php } ?>

<?php 
function classInEnrolledClasses(int $classId, array $enrolledClasses) : bool {
    foreach ($enrolledClasses as $enrolledClass) {
        if ($enrolledClass->id === $classId) {
            return true;
        }
    }
    return false;
}

function classInWaitlistedClasses(int $classId, array $waitlistedClasses) : bool {
    foreach ($waitlistedClasses as $waitlistedClass) {
        if ($waitlistedClass->id === $classId) {
            return true;
        }
    }
    return false;
}
?>

<?php function drawClassDate(string $startTimestamp) { ?>
  <p>
    <span class="material-symbols-outlined">calendar_today</span>
    <?= date('M j, Y', strtotime($startTimestamp)) . ',' ?>
    <span class="date-weekday"><?= date('l', strtotime($startTimestamp)) ?></span>
  </p>
<?php } ?>

<?php function drawClassTime(string $startTimestamp, string $endTimestamp) { ?>
  <p>
    <span class="material-symbols-outlined">schedule</span>
    <?= date('g:i A', strtotime($startTimestamp)) ?> - <?= date('g:i A', strtotime($endTimestamp)) ?>
  </p>
<?php } ?>

<?php function drawClassCapacity(int $currentEnrollment, int $capacity) { ?>
  <p>
    <span class="material-symbols-outlined">person</span>
    <?= $currentEnrollment . '/' . $capacity ?> enrolled
  </p>
<?php } ?>

<?php function drawClassEnrollmentList(array $classes, array $enrolledClasses, array $waitlistedClasses, Session $session) { ?>
  <div id="enrollment">
    <section class="container">
      <h2>Available Classes</h2>
      <ul class="classes-enrollment">
        <?php foreach ($classes as $class) { ?>
          <li class="card">
            <div class="class-info">
              <h3><?= $class->name ?></h3>
              <?php drawClassDate($class->startTimestamp); ?>
              <?php drawClassTime($class->startTimestamp, $class->endTimestamp); ?>

              <?php if ($session->isMember() || $session->isTrainer() || $session->isAdmin()) { ?>
                <?php drawClassCapacity($class->currentEnrollment, $class->capacity); ?>
              <?php } ?>

            </div>

            <?php if ($session->isMember() && !classInEnrolledClasses($class->id, $enrolledClasses) && !classInWaitlistedClasses($class->id, $waitlistedClasses)) { 
                if ($class->hasRemainingCapacity()) { ?>
                <form action="../actions/action_enrollment.php" method="post">
                  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
                  <input type="hidden" name="class_id" value="<?= $class->id ?>" />
                  <button class="btn medium blue" type="submit">Enroll Now</button>
                </form>
              <?php } else { ?>
                <form action="../actions/action_join_waitlist.php" method="post">
                  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
                  <input type="hidden" name="class_id" value="<?= $class->id ?>" />
                  <button class="btn medium blue" type="submit">Join Waitlist</button>
                </form>
              <?php } ?>

            <?php } else if ($session->isMember() && classInEnrolledClasses($class->id, $enrolledClasses)) { ?>
              <form action="../actions/action_cancel_enrollment.php" method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
                <input type="hidden" name="class_id" value="<?= $class->id ?>" />
                <button class="btn medium warning outline" type="submit">Cancel Enrollment</button>
              </form>

            <?php } else if ($session->isMember() && classInWaitlistedClasses($class->id, $waitlistedClasses)) { ?>
              <form action="../actions/action_leave_waitlist.php" method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
                <input type="hidden" name="class_id" value="<?= $class->id ?>" />
                <button class="btn medium warning outline" type="submit">Leave Waitlist</button>
              </form>
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

<?php function drawReviewForm(int $classTypeId, Session $session) { ?>
  <form class="comment-form" action="../actions/action_sumbitReview.php" method="POST">
    <h3>Leave a comment</h3>
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">

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
            drawReviewForm($classType->id, $session);
        } 
      ?>
    </section>
  </div>
<?php } ?>

<?php function drawClassPage(Session $session, ClassType $classType, array $classes, array $enrolledClasses, array $waitlistedClasses, array $reviews, ?array $classTypeRating) {
    drawClassIntro($classType, $session, $classTypeRating);
    drawClassEnrollmentList($classes, $enrolledClasses, $waitlistedClasses, $session);
    drawReviewSection($classType, $session, $reviews);
} ?>