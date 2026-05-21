<?php 
  declare(strict_types=1);

  function drawProfilePicture(int $userId) : string {
    $photoUrl = file_exists('../img/profiles/' . $userId . '.jpg')
      ? '../img/profiles/' . $userId . '.jpg'
      : '../img/profiles/default.jpg';

    return '<img src="' . htmlspecialchars($photoUrl) . '" alt="profile photo" class="profile image">';
  }
  require_once(__DIR__ . '/../database/gymClass.class.php'); 
  // TODO: this is used because of the drawEnrolledClass function (move it to common.php?)
  require_once(__DIR__ . '/class.php');
?>

<?php function drawProfile(int $userId, string $name, string $username) { ?>
  <div class="intro">
    <section id="profile">
      <?= drawProfilePicture($userId); ?>
      <header>
        <h1><?= $name; ?></h1>
        <h2><?= $username; ?></h2>
        <a class="btn medium blue" href="../pages/editProfile.php">Edit Profile</a>
      </header>
    </section>
  </div>
<?php } ?>

<?php function drawEnrolledClass(GymClass $class) { ?>
  <li class="card">
      <?php drawClassCardInfo($class); ?>

      <a class="btn medium blue" href="../pages/class.php?id=<?= $class->classTypeId ?>">Details</a>
      <form action="../actions/action_cancel_enrollment.php" method="post">
          <input type="hidden" name="class_id" value="<?= $class->id ?>" />
          <button class="btn medium warning outline" type="submit">Cancel Enrollment</button>
      </form>
    </li>
<?php } ?>

<?php function drawEnrolledClasses(array $classes) { ?>
  <ul class="classes-enrollment">
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
  <div>
    <section class="container">
      <p class="detail">YOUR CLASSES</p>
      <h2>Enrolled Classes</h2>
      <?php drawEnrolledClasses($classes); ?>
    </section>
  </div>
<?php } ?>

<?php function drawProfilePage(int $userId, string $name, string $username, array $classes) {
  drawProfile($userId, $name, $username);
  drawEnrolledClassesContainer($classes);
} ?>
