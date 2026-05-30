<?php 
  declare(strict_types=1);

  require_once(__DIR__ . '/../database/gymClass.class.php'); 
  require_once(__DIR__ . '/common.php');
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
  <div class="card-info">
    <h3><?= htmlspecialchars($class->getName()); ?></h3>
    <?php drawDate($class->getStartTimestamp()); ?>
    <?php drawTime($class->getStartTimestamp(), $class->getEndTimestamp()); ?>
    <?php drawClassCapacity($class->getCurrentEnrollment(), $class->getCapacity()); ?>
  </div>
<?php } ?>

<?php function drawEnrolledClass(GymClass $class, Session $session) { ?>
  <li class="card">
      <?php drawClassCardInfo($class); ?>

      <a class="btn medium blue" href="../pages/class.php?id=<?= (int) $class->getClassTypeId() ?>">Details</a>
      <form data-action="cancel">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
          <input type="hidden" name="class_id" value="<?= (int) $class->getId() ?>" />
          <button class="btn medium warning outline" type="submit">Cancel Enrollment</button>
      </form>
    </li>
<?php } ?>

<?php function drawEnrolledClasses(array $classes, Session $session) { ?>
  <ul class="card-list js-enrollment">
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
    
    <a class="btn medium blue" href="../pages/class.php?id=<?= (int) $class->getClassTypeId() ?>">Details</a>
    <form data-action="leave_waitlist">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
      <input type="hidden" name="class_id" value="<?= (int) $class->getId() ?>" />
      <button class="btn medium warning outline" type="submit">Leave Waitlist</button>
    </form>
  </li>
<?php } ?>

<?php function drawWaitlistedClasses(array $classes, Session $session) { ?>
  <ul class="card-list js-enrollment">
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

<?php function drawAttendedClasses(int $numAttendedClasses, Session $session) { ?>
  <section class="container">
    <h2>Attended Classes</h2>
    <?php if ($numAttendedClasses === 0) { ?>
      <p>You haven't attended any classes yet.</p>
      <a class="btn medium blue" href="../pages/classes.php">Enroll in a class</a>
    <?php } else { ?>
      <div class="table-container">
        <table class="data-table attended-classes-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Date</th>
              <th>Time</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody 
            id="attended-classes-table-body"
            data-page="1"
            data-attended-classes-per-page="5"
            data-num-attended-classes="<?= $numAttendedClasses ?>"
            data-csrf-token="<?= htmlspecialchars($session->getCSRFToken()) ?>"
          >
          </tbody>
        </table>
      </div>
      <nav class="pagination">
        <button class="btn medium transparent-dark" id="prev-attended-classes-page">Previous</button>
        <button class="btn medium transparent-dark" id="next-attended-classes-page">Next</button>
      </nav>

    </section>
  <?php } ?>
<?php } ?>

<?php function drawEquipmentReservationsContainer(array $equipmentBooked, Session $session) { ?>
  <section class="container">
    <h2>My Equipment Reservations</h2>
    <ul class="card-list">
      <?php foreach ($equipmentBooked as $booking) { ?>
        <li class="card">
          <section class="card-info">
            <h3><?= htmlspecialchars($booking->getEquipmentName()); ?></h3>
            <?php drawDate($booking->getBookingStartTime()); ?>
            <?php drawTime($booking->getBookingStartTime(), $booking->getBookingEndTime()); ?>
          </section>

          <form action="../actions/equipment/action_cancel_equipment_booking.php" method="post">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
            <input type="hidden" name="booking_id" value="<?= (int) $booking->getId() ?>" />
            <button class="btn medium warning outline" type="submit">Cancel Booking</button>
          </form>
        </li>
      <?php } ?>
    </ul>
  </section>
<?php } ?>
  

<?php function drawProfilePage(int $userId, string $name, string $username, array $classes, array $waitlistedClasses, array $equipmentBooked, int $numMemberships, Session $session, int $numAttendedClasses) { 
  drawProfile($userId, $name, $username); ?>
  <?php if ($session->isMember()) { ?>
    <div class="container">
      <p class="detail">YOUR CLASSES</p>
      <?php drawEnrolledClassesContainer($classes, $session); ?>
    </div>


    <?php if (!empty($waitlistedClasses)) {
        drawWaitlistedClassesContainer($waitlistedClasses, $session);
      }

      if (!empty($equipmentBooked)) {
        drawEquipmentReservationsContainer($equipmentBooked, $session);
      }

      drawMemberships($numMemberships, $session); 
      drawAttendedClasses($numAttendedClasses, $session);
      ?>
    </section>
  
  <?php } ?>
<?php } ?>
