<?php 
  declare(strict_types=1);

  function drawProfilePicture(int $userId) : string {
    $photoUrl = file_exists('../img/profiles/' . $userId . '.jpg')
      ? '../img/profiles/' . $userId . '.jpg'
      : '../img/profiles/default.jpg';

    return '<img src="' . htmlspecialchars($photoUrl) . '" alt="profile photo" class="profile image">';
  }
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

<?php function drawEnrolledClassesContainer(string $name, array $classes) { ?>
  <div>
    <section class="container">
      <p class="detail"><?= $name ?></p>
      <h2>Is enrolled in</h2>
      <?php drawClasses($classes); ?>
    </section>
  </div>
<?php } ?>

<?php function drawProfilePage(int $userId, string $name, string $username, array $classes) {
  drawProfile($userId, $name, $username);
  drawEnrolledClassesContainer($name, $classes);
} ?>
