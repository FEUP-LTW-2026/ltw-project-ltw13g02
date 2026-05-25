<?php
  declare(strict_types = 1);
?>

<?php function drawHero() { ?>
 <div id="hero" class="intro">
  <section class="container">
    <div>
      <h1>TRAIN <b>SMARTER,</b> GET <b>STRONGER.</b></h1>
      <p>
      Build consistency with guided workouts, expert coaching, and a motivating community.
      Join gymRAT and start your next fitness milestone today.
      </p>
      <nav>
        <ul>
          <li><a class="btn large blue" href="#membership">START TODAY</a></li>
          <li><a class="btn large transparent-light" href="classes.php">EXPLORE CLASSES</a></li>
        </ul>
      </nav>
    </div>
    <img src="https://picsum.photos/800/600" alt="gymRAT Hero Image" class="hero-image" >
  </section>
  </div>
<?php } ?>

<?php function drawMembershipPlan(int $id, string $planName, float $price, array $features, Session $session) { ?>
  <article class="card membership-plan">
    <h3><?= htmlspecialchars($planName) ?></h3>
    <p><span><?= $price; ?>€</span>/4 weeks</p>
    <p>What's included:</p>
    <ul>
      <?php foreach ($features as $feature) { ?>
        <li><?= htmlspecialchars($feature) ?></li>
      <?php } ?>
    </ul>
    <?php if ($session->isLoggedIn() && (!$session->isTrainer())) { ?>
      <form method="POST" action="../pages/membership_terminal.php">
        <input type="hidden" name="plan_id" value="<?= (int) $id; ?>">
        <button type="submit" class="btn large wide blue">Choose <?= htmlspecialchars($planName) ?></button>
      </form>
    <?php } else if (!$session->isLoggedIn()) { ?>
      <a class="btn large wide blue" href="../pages/signup.php">Choose <?= htmlspecialchars($planName) ?></a>
    <?php } ?>
  </article>
<?php } ?>

<?php function drawMembershipPlans(array $plans, Session $session) { ?>
  <div class="plans">
    <?php foreach ($plans as $plan) {
      // Parse the description into individual features (considering semicolon as a separator)
      $features = explode(';', $plan->getDescription());
      drawMembershipPlan($plan->getId(), $plan->getName(), $plan->getMonthlyFee(), $features, $session);
    } ?>
  </div>
<?php } ?>

<?php function drawMembershipContainer(array $plans, Session $session) { ?>
  <div id="membership">
    <section class="container">
      <p class="detail">OUR PLANS</p>
      <h2>MEMBERSHIP PLANS</h2>
      <?php drawMembershipPlans($plans, $session); ?>
    </section>
  </div>
<?php } ?>

<?php function drawIndexPage(array $plans, Session $session) {
  drawHero();
  drawMembershipContainer($plans, $session);
} ?>
