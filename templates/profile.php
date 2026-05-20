<?php 
  declare(strict_types=1);

  function drawProfilePicture(int $userId) : string {
    $photoUrl = file_exists('../img/profiles/' . $userId . '.jpg')
      ? '../img/profiles/' . $userId . '.jpg'
      : '../img/profiles/default.jpg';

    return '<img src="' . htmlspecialchars($photoUrl) . '" alt="profile photo" class="profile image">';
  }
  require_once(__DIR__ . '/../database/gymClass.class.php');
  require_once(__DIR__ . '/classes.php');
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

<?php function drawEnrolledClasses(array $classes) { ?>
  <div class="card-grid class-list">
    <?php if (empty($classes)) { ?>
      <p>You are not enrolled in any classes yet.</p>
    <?php } else {
      foreach ($classes as $class) {
        drawClass($class);
      }
    } ?>
  </div>
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
