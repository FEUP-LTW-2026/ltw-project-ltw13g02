<?php 
  declare(strict_types = 1);

  require_once(__DIR__ . '/../database/classType.class.php');
  require_once(__DIR__ . '/../database/gymClass.class.php');
?>

<?php function drawClassIntro(ClassType $classType, Session $session) { ?>
  <div class="intro">
    <section class="container">
      <div id="class-hero">
        <p class="detail"><?= $classType->weekday ?></p>
        <h1><?= $classType->name ?></h1>
        <p class="rating">⭐⭐⭐⭐⭐ 4.9 / 5 (127 reviews)</p>
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
                  <input type="hidden" name="class_id" value="<?= $class->id ?>" />
                  <button class="btn medium blue" type="submit">Enroll Now</button>
                </form>
              <?php } else { ?>
                <form action="../actions/action_join_waitlist.php" method="post">
                  <input type="hidden" name="class_id" value="<?= $class->id ?>" />
                  <button class="btn medium blue" type="submit">Join Waitlist</button>
                </form>
              <?php } ?>

            <?php } else if ($session->isMember() && classInEnrolledClasses($class->id, $enrolledClasses)) { ?>
              <form action="../actions/action_cancel_enrollment.php" method="post">
                <input type="hidden" name="class_id" value="<?= $class->id ?>" />
                <button class="btn medium warning outline" type="submit"><?= 'Cancel Enrollment' ?></button>
              </form>

            <?php } else if ($session->isMember() && classInWaitlistedClasses($class->id, $waitlistedClasses)) { ?>
              <form action="../actions/action_leave_waitlist.php" method="post">
                <input type="hidden" name="class_id" value="<?= $class->id ?>" />
                <button class="btn medium warning outline" type="submit"><?= 'Leave Waitlist' ?></button>
              </form>
            <?php } ?>
          </li>
        <?php } ?>
      </ul>
    </section>
  </div>
<?php } ?>

<?php function drawReview($name, $rating, $comment) { ?>
  <article class="card review">
    <h3><?= $name ?></h3>
    <p><?= $rating .  "/5" ?></p>
    <p><?= $comment ?></p>
  </article>
<?php } ?>

<?php function drawReviewForm() { ?>
  <form class="comment-form" action="#" method="post">
    <h3>Leave a comment</h3>
    <label>
      Name
      <input type="text" name="name" placeholder="Your name" required />
    </label>

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

    <button class="btn medium blue" type="submit">Submit</button>
  </form>
<?php } ?>

<?php function drawReviewSection(Session $session, array $reviews) { ?>
  <div id="reviews">
    <section class="container">
      <h2>Reviews</h2>
      <?php if($session->isLoggedIn()) { ?>
        <p class="detail">LEAVE US YOUR FEEDBACK</p>
      <?php } ?>
      <div id="reviews-list">
        <?php foreach ($reviews as $review) {
          drawReview($review['name'], $review['rating'], $review['comment']);
        } ?>
      </div>
      <?php 
        if($session->isLoggedIn()) {
            drawReviewForm();
        } 
      ?>
    </section>
  </div>
<?php } ?>

<?php function drawClassPage(Session $session, ClassType $classType, array $classes, array $enrolledClasses, array $waitlistedClasses, array $reviews) {
    drawClassIntro($classType, $session);
    if($session->isLoggedIn()) {
        drawClassEnrollmentList($classes, $enrolledClasses, $waitlistedClasses, $session);
    }
    drawReviewSection($session, $reviews);
} ?>