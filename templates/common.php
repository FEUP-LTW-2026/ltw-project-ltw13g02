<?php
    declare(strict_types=1);
?>

<?php function drawHeader(Session $session, string $title, array $extra_css = []) { ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" >

    <title> <?= $title . ' - gymRAT'?></title>
    
    <link rel="stylesheet" href="../css/normalize.css" >
    <link rel="stylesheet" href="../css/base.css" >
    <link rel="stylesheet" href="../css/layout.css" >
    <link rel="stylesheet" href="../css/components.css">
    <?php foreach ($extra_css as $css) { ?>
        <link rel="stylesheet" href="<?= '../css/page/' . $css ?>">
    <?php } ?>
  </head>
<body>
  <header>
    <nav class="container">
      <a href="index.php">
        <img src="../img/logo_outline.png" alt="gymRAT Logo" class="logo" >
      </a>
      <label class="hamburger">
        <input type="checkbox" class="nav-toggle" >
      </label>
      <ul class="nav-links">
        <li><a href="../pages/index.php">Home</a></li>
        <li><a href="../pages/classes.php">Classes</a></li>
        <li><a href="../pages/trainers.php">Trainers</a></li>
        <?php if ($session->isLoggedin()) { ?>
          <li><a href="../pages/availability.php">Availability</a></li>
        <?php } ?>
      </ul>
      <ul class="auth-links">
        <?php if ($session->isLoggedin()) { ?>
          <li><a class="btn medium transparent-dark" href="../pages/profile.php">Profile</a></li>
          <form action="../actions/action_logout.php" method="post">
            <button class="btn medium transparent-dark" type="submit">Logout</button>
          </form>
        <?php } else { ?>
          <li><a class="btn medium transparent-dark" href="../pages/login.php">Login</a></li>
          <li><a class="btn medium transparent-dark" href="../pages/signup.php">Sign up</a></li>
          <li><a class="btn medium blue" href="../pages/index.php#membership">Join Now</a></li>
        <?php } ?>
      </ul>
    </nav>
  </header>
  <main>
<?php } ?>

<!-- This will be used to display either the user is logged in or not -->
<?php function drawLoginButtons() { ?>
  <ul class="auth-links">
    <li><a class="btn medium transparent-dark" href="../pages/login.php">Login</a></li>
    <li><a class="btn medium transparent-dark" href="../pages/signup.php">Sign up</a></li>
    <li><a class="btn medium blue" href="../pages/index.php#membership">Join Now</a></li>
  </ul>
<?php } ?>

<?php function drawLogoutButtons() { ?>
  <ul class="auth-links">
    <li><a class="btn medium transparent-dark" href="../pages/login.php">Name</a></li>
    <li><a class="btn medium transparent-dark" href="../pages/signup.php">Logout</a></li>
  </ul>
<?php } ?>

<?php function drawIntro(string $detail, string $title, string $description) { ?>
  <div class="intro">
    <section class="container">
      <p class="detail"><?= $detail ?></p>
      <h2><?= $title ?></h2>
      <p><?= $description ?></p>
    </section>
  </div>
<?php } ?>

<?php function drawFooter() { ?>
    </main>
    <footer>
      <div class="container">
        <div class="grid">
          <div id="summary">
            <a href="../pages/index.php">
              <img src="../img/logo_outline_white.png" alt="gymRAT Logo" class="logo" >
            </a>
            <p>Your fitness journey starts here.</p>
          </div>
          <section>
            <h3>Browse</h3>
            <nav>
              <ul>
              <li><a href="../pages/index.php">Home</a></li>
              <li><a href="../pages/classes.php">Classes</a></li>
              <li><a href="../pages/trainers.php">Trainers</a></li>
              </ul>
            </nav>
          </section>
          <section>
            <h3>Legal</h3>
            <nav>
              <ul>
              <li><a href="#">Terms of Service</a></li>
              <li><a href="#">Privacy Policy</a></li>
              <li><a href="#">Cookie Policy</a></li>
              </ul>
            </nav>
          </section>
          <section>
            <h3>Contact</h3>
            <address>
              <ul>
              <li>Email: <a href="mailto:hello@gymrat.example">hello@gymrat.example</a></li>
              <li>Phone: <a href="tel:+1234567890">+1 (234) 567-890</a></li>
              <li>Address: 21 Power Street, Fit City</li>
              </ul>
            </address>
          </section>
        </div>
        <div id="copyright">
            <p>&copy; 2026 gymRAT. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </body>
</html>
<?php } ?>
