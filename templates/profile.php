<?php 
  declare(strict_types=1);
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

<?php function drawEnrolledClassesContainer(string $name, array $classes) { ?>
  <div>
    <section class="container">
      <p class="detail"><?= $name ?></p>
      <h2>Is enrolled in</h2>
      <?php drawClasses($classes); ?>
    </section>
  </div>
<?php } ?>

<?php function drawProfilePage(string $name, string $username, array $classes) {
  drawProfile($name, $username);
  drawEnrolledClassesContainer($name, $classes);
} ?>
