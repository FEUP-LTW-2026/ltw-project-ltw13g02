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
      <?php drawProfilePicture($userId); ?>
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

<?php function drawEnrolledClass(GymClass $class) { ?>
  <li class="card">
      <?php drawClassCardInfo($class); ?>

      <a class="btn medium blue" href="../pages/class.php?id=<?= (int) $class->classTypeId ?>">Details</a>
      <form action="../actions/action_cancel_enrollment.php" method="post">
          <input type="hidden" name="class_id" value="<?= (int) $class->id ?>" />
          <button class="btn medium warning outline" type="submit">Cancel Enrollment</button>
      </form>
    </li>
<?php } ?>

<?php function drawEnrolledClasses(array $classes) { ?>
  <ul class="scheduled-classes">
    <?php if (empty($classes)) { ?>
      <li><p>You are not enrolled in any classes yet.</p></li>
    <?php } else {
      foreach ($classes as $class) {
        drawEnrolledClass($class);
      }
    } ?>
  </ul>
<?php } ?>

<?php function drawEnrolledClassesContainer(array $classes) { ?>
  <section>
    <h2>Enrolled Classes</h2>
    <?php drawEnrolledClasses($classes); ?>
  </section>
<?php } ?>

<?php function drawWaitlistedClass(GymClass $class, Session $session) { ?>
  <li class="card">
    <?php drawClassCardInfo($class); ?>
    
    <a class="btn medium blue" href="../pages/class.php?id=<?= (int) $class->classTypeId ?>">Details</a>
    <form action="../actions/action_leave_waitlist.php" method="post">
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
  <section id="waitlisted-classes">
    <h2>Waitlisted Classes</h2>
    <?php drawWaitlistedClasses($classes, $session); ?>
  </section>
<?php } ?>

<?php function drawMembershipDetailsModal(array $activeMemberships, array $otherStatusMemberships) { ?>
  <dialog id="membership-details-modal">
    <section id="membership-details-content">
      <h3>Membership Details</h3>

      <label>Membership Tier
        <input type="text" id="membership-tier" readonly>
      </label>

      <label>Description
        <input type="text" id="membership-description" readonly>
      </label>

      <label>Membership Fee
        <input type="text" id="membership-fee" readonly>
      </label>

      <label>Status
        <input type="text" id="membership-status" readonly>
      </label>

      <label>Valid from
        <input type="text" id="membership-start" readonly>
      </label>

      <label>Valid until
        <input type="text" id="membership-end" readonly>
      </label>

      <div class="dialog-actions">
        <button type="button" class="btn medium blue btn-close-dialog">Close</button>
      </div>
    </section>
  </dialog>
<?php } ?>

<?php function drawMemberships(array $activeMemberships, array $otherStatusMemberships, Session $session) { ?>
  <section>
    <h2>Memberships</h2>
    <?php if (empty($activeMemberships)) { ?>
      <p>You don't have any active memberships.</p>
      <a class="btn medium blue" href="../pages/index.php#membership">Purchase Membership</a>
    <?php } else { ?>
      <ul class="memberships">
        <?php foreach ($activeMemberships as $membership) { ?>
          <li class="membership-card">
            <h3><?= htmlspecialchars($membership->tierName); ?></h3>
            <p><?= htmlspecialchars($membership->status); ?></p>
            <p>Valid from: <?= htmlspecialchars($membership->startTimestamp); ?></p>
            <p>Valid until: <?= htmlspecialchars($membership->endTimestamp); ?></p>

            <button class="btn medium blue see-membership-details-btn"
            data-membership-id="<?= $membership->id ?>"
            data-membership-tier="<?= htmlspecialchars($membership->tierName) ?>"
            data-membership-description="<?= htmlspecialchars($membership->tierDescription) ?>"
            data-membership-status="<?= htmlspecialchars($membership->status) ?>"
            data-membership-start="<?= htmlspecialchars($membership->startTimestamp) ?>"
            data-membership-end="<?= htmlspecialchars($membership->endTimestamp) ?>"
            data-membership-fee="<?= $membership->monthlyFee ?>"
            >See Details</button>

            <form action="../actions/action_cancel_membership.php" method="post">
              <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
              <input type="hidden" name="membership_id" value="<?= $membership->id ?>" />
              <button type="submit" class="btn medium warning outline">Cancel Membership</button>
            </form>
          </li>
        <?php } ?>
      </ul>
    <?php } ?>
  </section>
<?php } ?>

<?php function drawProfilePage(int $userId, string $name, string $username, array $classes, array $waitlistedClasses, array $activeMemberships, array $otherStatusMemberships, Session $session) {
  drawProfile($userId, $name, $username); ?>
  <div>
    <section class="container">
      <h2 class="detail">YOUR CLASSES</h2> <!-- TODO: consider details being headings instead of paragraphs -->

      <?php drawEnrolledClassesContainer($classes);
      if (!empty($waitlistedClasses)) {
        drawWaitlistedClassesContainer($waitlistedClasses, $session);
      }
      drawMemberships($activeMemberships, $otherStatusMemberships, $session);
      drawMembershipDetailsModal($activeMemberships, $otherStatusMemberships); ?>
    </section>
  </div>
<?php } ?>
