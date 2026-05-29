<?php 
  declare(strict_types = 1);

  require_once(__DIR__ . '/../database/classType.class.php');
  require_once(__DIR__ . '/../database/gymClass.class.php');
  require_once(__DIR__ . '/common.php');
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
    <span class="symbol">sports</span>
    Trainer: <a class="trainer" href="../pages/trainer.php?id=<?= $trainerId; ?>"><?= htmlspecialchars($trainerName); ?></a>
  </p>
<?php } ?>

<?php function drawClassDuration(string $startTime, string $endTime) { ?>
  <p>
    <span class="symbol">timer</span>
    <?= calculateDurationMinutes($startTime, $endTime) ?> min
  </p>
<?php } ?>

<?php function drawClassIntro(ClassType $classType, Session $session, ?array $classTypeRating) { ?>
  <div class="intro">
    <section class="container">
      <div id="class-hero">
        <p class="detail"><?= htmlspecialchars($classType->weekday) ?></p>
        <h1><?= htmlspecialchars($classType->name) ?></h1>
        <?php if($classTypeRating === null) { ?>
          <p class="rating">No reviews yet</p>
        <?php } else { ?>
          <p class="rating">
            <?php for($i = 0; $i < floor($classTypeRating[0]); $i++) { ?>
              <span class="symbol">⭐</span>
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
        if($session->isLoggedIn() && !$session->isTrainer()) { ?>
            <a class="btn large blue" href=<?= $session->isMember() ? "#enrollment" : "../pages/index.php#membership" ?>>Join Class</a>
        <?php } ?>
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
    <span class="symbol">calendar_today</span>
    <?= date('M j, Y', strtotime($startTimestamp)) . ',' ?>
    <span class="date-weekday"><?= date('l', strtotime($startTimestamp)) ?></span>
  </p>
<?php } ?>

<?php function drawClassTime(string $startTimestamp, string $endTimestamp) { ?>
  <p>
    <span class="symbol">schedule</span>
    <?= date('g:i A', strtotime($startTimestamp)) ?> - <?= date('g:i A', strtotime($endTimestamp)) ?>
  </p>
<?php } ?>

<?php function drawClassCapacity(int $currentEnrollment, int $capacity) { ?>
  <p>
    <span class="symbol">person</span>
    <?= htmlspecialchars((string)$currentEnrollment) . '/' . htmlspecialchars((string)$capacity) ?> enrolled
  </p>
<?php } ?>

<?php function drawClassEnrollmentList(array $classes, array $enrolledClasses, array $waitlistedClasses, Session $session) { ?>
  <div id="enrollment">
    <section class="container floating">
      <h2>Available Classes</h2>

      <?php if (empty($classes)) { ?>
        <p>No classes available of this type at the moment.</p>

      <?php } else { ?>
        <ul class="scheduled-classes">
          <?php foreach ($classes as $class) { ?>
            <li class="card">
              <?php drawScheduledClassInfo($class, $session); ?>

              <?php if ($session->isMember() && !classInEnrolledClasses($class->id, $enrolledClasses) && !classInWaitlistedClasses($class->id, $waitlistedClasses)) { 
                  if ($class->hasRemainingCapacity()) { ?>
                  <form data-action="enroll">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
                    <input type="hidden" name="class_id" value="<?= $class->id ?>">
                    <button class="btn medium blue" type="submit">Enroll Now</button>
                  </form>
                <?php } else { ?>
                  <form data-action="join_waitlist">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
                    <input type="hidden" name="class_id" value="<?= $class->id ?>">
                    <button class="btn medium blue" type="submit">Join Waitlist</button>
                  </form>
                <?php } ?>

              <?php } else if ($session->isMember() && classInEnrolledClasses($class->id, $enrolledClasses)) { ?>
                <form data-action="cancel">
                  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
                  <input type="hidden" name="class_id" value="<?= $class->id ?>" />
                  <button class="btn medium warning outline" type="submit">Cancel Enrollment</button>
                </form>

              <?php } else if ($session->isMember() && classInWaitlistedClasses($class->id, $waitlistedClasses)) { ?>
                <form data-action="leave_waitlist">
                  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
                  <input type="hidden" name="class_id" value="<?= $class->id ?>" />
                  <button class="btn medium warning outline" type="submit">Leave Waitlist</button>
                </form>
              <?php } ?>
            </li>
          <?php } ?>
        </ul>
      <?php } ?>
    </section>
  </div>
<?php } ?>

<?php function drawReview(string $username, string $firstName, string $lastName, int $rating, string $comment, string $timestamp) { ?>
  <article class="card review">
    <h3><?= htmlspecialchars($username) ?> <span><?= htmlspecialchars($firstName . ' ' . $lastName) ?></span></h3>
    <p><?= htmlspecialchars((string)$rating) .  "/5" ?></p>
    <p id="review-text"><?= htmlspecialchars($comment) ?></p>
    <p id="timestamp"><?= htmlspecialchars($timestamp) ?></p>
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