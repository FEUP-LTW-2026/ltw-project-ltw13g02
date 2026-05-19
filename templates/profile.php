<?php 
  declare(strict_types=1);

  require_once(__DIR__ . '/../database/gymClass.class.php');
  require_once(__DIR__ . '/classes.php');
?>

<?php function drawProfile(string $name, string $username) { ?>
  <div class="intro">
    <section id="profile">
      <img src="https://picsum.photos/250/340" alt="profile photo" class="profile image">
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

<?php function drawProfilePage(string $name, string $username, array $classes) {
  drawProfile($name, $username);
  drawEnrolledClassesContainer($classes);
} ?>
