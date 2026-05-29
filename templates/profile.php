<?php 
  declare(strict_types=1);

  require_once(__DIR__ . '/../database/gymClass.class.php'); 
  require_once(__DIR__ . '/common.php');  
  // TODO: this is used because of the drawClassDate, drawClassTime, and drawClassCapacity functions (move them to common.php?)
  require_once(__DIR__ . '/class.php');
?>

<?php function drawProfile(int $userId, string $name, string $username) { ?>
  <div class="intro">
    <section id="profile">
      <?php drawPicture($userId, 'profiles'); ?>
      <header>
        <h1><?= htmlspecialchars($name); ?></h1>
        <h2><?= htmlspecialchars($username); ?></h2>
        <a class="btn medium blue" href="../pages/editProfile.php">Edit Profile</a>
      </header>
    </section>
  </div>
<?php } ?>

<?php function drawClassCardInfo(GymClass $class) { ?>
  <div class="class-info">
    <h3><?= htmlspecialchars($class->name); ?></h3>
    <?php drawClassDate($class->startTimestamp); ?>
    <?php drawClassTime($class->startTimestamp, $class->endTimestamp); ?>
    <?php drawClassCapacity($class->currentEnrollment, $class->capacity); ?>
  </div>
<?php } ?>

<?php function drawEnrolledClass(GymClass $class, Session $session) { ?>
  <li class="card">
      <?php drawClassCardInfo($class); ?>

      <a class="btn medium blue" href="../pages/class.php?id=<?= (int) $class->classTypeId ?>">Details</a>
      <form data-action="cancel">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
          <input type="hidden" name="class_id" value="<?= (int) $class->id ?>" />
          <button class="btn medium warning outline" type="submit">Cancel Enrollment</button>
      </form>
    </li>
<?php } ?>

<?php function drawEnrolledClasses(array $classes, Session $session) { ?>
  <ul class="scheduled-classes">
    <?php if (empty($classes)) { ?>
      <li><p>You are not enrolled in any classes yet.</p></li>
    <?php } else {
      foreach ($classes as $class) {
        drawEnrolledClass($class, $session);
      }
    } ?>
  </ul>
<?php } ?>

<?php function drawEnrolledClassesContainer(array $classes, Session $session) { ?>
  <section>
    <h2>Enrolled Classes</h2>
    <?php drawEnrolledClasses($classes, $session); ?>
  </section>
<?php } ?>

<?php function drawWaitlistedClass(GymClass $class, Session $session) { ?>
  <li class="card">
    <?php drawClassCardInfo($class); ?>
    
    <a class="btn medium blue" href="../pages/class.php?id=<?= (int) $class->classTypeId ?>">Details</a>
    <form data-action="leave_waitlist">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
      <input type="hidden" name="class_id" value="<?= (int) $class->id ?>" />
      <button class="btn medium warning outline" type="submit">Leave Waitlist</button>
    </form>
  </li>
<?php } ?>

<?php function drawWaitlistedClasses(array $classes, Session $session) { ?>
  <ul class="scheduled-classes">
    <?php
      foreach ($classes as $class) {
        drawWaitlistedClass($class, $session);
      }
    ?>
  </ul>
<?php } ?>

<?php function drawWaitlistedClassesContainer(array $classes, Session $session) { ?>
  <section class="container" id="waitlisted-classes">
    <h2>Waitlisted Classes</h2>
    <?php drawWaitlistedClasses($classes, $session); ?>
  </section>
<?php } ?>

<?php function drawMemberships(int $numMemberships, Session $session) { ?>
  <section class="container">
    <h2>My Memberships</h2>
    <?php if ($numMemberships === 0) { ?>
      <p>You don't have any memberships yet.</p>
      <a class="btn medium blue" href="../pages/index.php#membership">Purchase Membership</a>
    <?php } else { ?>
      <div class="table-container">
        <table class="data-table memberships-table">
          <thead>
            <tr>
              <th>Tier</th>
              <th>Fee</th>
              <th>Status</th>
              <th>Valid From</th>
              <th>Valid Until</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody
            id="membership-table-body"
            data-page="1"
            data-memberships-per-page="5"
            data-num-memberships="<?= $numMemberships ?>"
            data-csrf-token="<?= htmlspecialchars($session->getCSRFToken()) ?>"
          >
          </tbody>
        </table>
      </div>

      <nav class="pagination">
        <button class="btn medium transparent-dark" id="prev-membership-page">Previous</button>
        <button class="btn medium transparent-dark" id="next-membership-page">Next</button>
      </nav>
      
  </section>
  <?php }
} ?>

<?php function drawProfilePage(int $userId, string $name, string $username, array $classes, array $waitlistedClasses, int $numMemberships, Session $session) {
  drawProfile($userId, $name, $username); ?>
  <?php if ($session->isMember()) { ?>
    <section class="container">
      <h2 class="detail">YOUR CLASSES</h2> <!-- TODO: consider details being headings instead of paragraphs -->
      <?php drawEnrolledClassesContainer($classes, $session); ?>
    </section>


    <?php if (!empty($waitlistedClasses)) {
        drawWaitlistedClassesContainer($waitlistedClasses, $session);
      }
      drawMemberships($numMemberships, $session); ?>
    </section>
  <?php } ?>
<?php } ?>
