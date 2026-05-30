<?php
  declare(strict_types=1);
  date_default_timezone_set('Europe/Lisbon');
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
    <script src="../javascript/executeLazyCronJob.js" defer></script>
    <script src="../javascript/global.js" defer></script>
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
        <?php if ($session->isMember()) { ?>
          <li><a href="../pages/availability.php">Availability</a></li>
        <?php } ?>
        <?php if ($session->isAdmin()) { ?>
          <li><a href="../pages/admin.php">Admin</a></li>
        <?php } ?>
      </ul>
      <ul class="auth-links">
        <?php if ($session->isLoggedin()) { 

          if ($session->isTrainer()) { ?>
            <li><a class="btn medium transparent-dark" href="../pages/trainer.php?id=<?= $session->getId() ?>">Profile</a></li>
          <?php } else { ?>
            <li><a class="btn medium transparent-dark" href="../pages/profile.php">Profile</a></li>
          <?php } ?>

          <li>
            <form action="../actions/auth/action_logout.php" method="post">
              <button class="btn medium transparent-dark" type="submit">Logout</button>
            </form>
          </li>
        <?php } else { ?>
          <li><a class="btn medium transparent-dark" href="../pages/login.php">Login</a></li>
          <li><a class="btn medium transparent-dark" href="../pages/signup.php">Sign up</a></li>
          <li><a class="btn medium blue" href="../pages/index.php#membership">Join Now</a></li>
        <?php } ?>
      </ul>
    </nav>
  </header>
  <main>
    <?php displayMessages($session); ?>
<?php } ?>

<?php function displayMessages(Session $session) { 
  $messages = $session->getMessages();
  if (!empty($messages)) { ?>
    <div class="container messages">
      <?php foreach ($messages as $message) { ?>
        <article class="message <?= $message['type'] ?>">
          <?= $message['text'] ?>
        </article>
      <?php } ?>
    </div>
  <?php } 
} ?>

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


<?php function drawPicture(int $userId, string $path) : void {
   $photoUrl = file_exists('../img/'.$path.'/' . $userId . '.jpg')
      ? '../img/'.$path.'/' . $userId . '.jpg'
      : '../img/'.$path.'/default.jpg'; ?>

    <img src="<?= htmlspecialchars($photoUrl) ?>" alt="profile photo">
<?php } ?>

<?php function drawScheduledClassInfo(GymClass $class, Session $session) { ?>
   <div class="card-info">
      <h3><?= htmlspecialchars($class->getName()) ?></h3>
      <?php drawDate(htmlspecialchars($class->getStartTimestamp())); ?>
      <?php drawTime(htmlspecialchars($class->getStartTimestamp()), htmlspecialchars($class->getEndTimestamp())); ?>

      <?php if ($session->isMember() || $session->isTrainer() || $session->isAdmin()) { ?>
        <?php drawClassCapacity($class->getCurrentEnrollment(), $class->getCapacity()); ?>
      <?php } ?>

    </div>
<?php } ?>

<?php function drawClassTypeCapacity(int $capacity) { ?>
  <p>
    <span class="symbol">person</span>
    Max: <?= (int) $capacity ?>
  </p>
<?php } ?>

<?php function drawClassCapacity(int $currentEnrollment, int $capacity) { ?>
  <p>
    <span class="symbol">person</span>
    <?= htmlspecialchars((string)$currentEnrollment) . '/' . htmlspecialchars((string)$capacity) ?> enrolled
  </p>
<?php } ?>

<?php function drawDate(string $startTimestamp) { ?>
  <p>
    <span class="symbol">calendar_today</span>
    <?= date('M j, Y', strtotime($startTimestamp)) . ',' ?>
    <span class="date-weekday"><?= date('l', strtotime($startTimestamp)) ?></span>
  </p>
<?php } ?>

<?php function drawTime(string $startTimestamp, string $endTimestamp) { ?>
  <p>
    <span class="symbol">schedule</span>
    <?= date('g:i A', strtotime($startTimestamp)) ?> - <?= date('g:i A', strtotime($endTimestamp)) ?>
  </p>
<?php } ?>