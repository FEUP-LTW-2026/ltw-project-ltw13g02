<?php
    declare(strict_types=1);
?>

<?php function drawHeader(string $title, array $extra_css = []) { ?>
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
    <link rel="stylesheet" href="../css/auth.css">
    <?php foreach ($extra_css as $css) { ?>
        <link rel="stylesheet" href="<?= '../css/page/' . $css ?>">
    <?php } ?>
  </head>
<body>
  <header>
    <nav class="container">
      <a href="../pages/index.php">
        <img src="../img/logo_outline.png" alt="gymRAT Logo" class="logo" >
      </a>
      <label class="hamburger">
        <input type="checkbox" class="nav-toggle" >
      </label>
      <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="classes.php">Classes</a></li>
        <li><a href="trainers.php">Trainers</a></li>
        <li><a href="availability.php">Availability</a></li>
      </ul>
      <ul class="auth-links">
        <li><a class="btn medium transparent-dark" href="login.php">Login</a></li>
        <li><a class="btn medium transparent-dark" href="signup.php">Sign up</a></li>
        <li><a class="btn medium blue" href="index.php#membership">Join Now</a></li>
      </ul>
    </nav>
  </header>
  <main>
<?php } ?>

<!-- This will be used to display either the user is logged in or not -->
<?php function drawLoginButtons() { ?>
    <ul class="auth-links">
      <li><a class="btn medium transparent-dark" href="login.html">Login</a></li>
      <li><a class="btn medium transparent-dark" href="signup.html">Sign up</a></li>
      <li><a class="btn medium blue" href="index.php#membership">Join Now</a></li>
    </ul>
<?php } ?>

<?php function drawLogoutButtons() { ?>
    <ul class="auth-links">
      <li><a class="btn medium transparent-dark" href="login.html">Name</a></li>
      <li><a class="btn medium transparent-dark" href="signup.html">Logout</a></li>
    </ul>
<?php } ?>

<?php function drawFooter() { ?>
    </main>
    <footer>
      <div class="container">
        <div class="grid">
          <div id="summary">
            <a href="index.php">
              <img src="../img/logo_outline_white.png" alt="gymRAT Logo" class="logo" >
            </a>
            <p>Your fitness journey starts here.</p>
          </div>
          <section>
            <h3>Browse</h3>
            <nav>
              <ul>
              <li><a href="index.php">Home</a></li>
              <li><a href="classes.php">Classes</a></li>
              <li><a href="trainers.php">Trainers</a></li>
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