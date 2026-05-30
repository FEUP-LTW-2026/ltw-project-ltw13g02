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
    <img src="../img/gym.jpg" alt="gymRAT Hero Image" class="hero-image" >
  </section>
  </div>
<?php } ?>

<?php function drawMembershipPlan(int $id, string $planName, float $price, int $bookingWindowDays, int $maxEnrollments, Session $session, MembershipTierPerksMap $perksMap) { ?>
  <article class="card membership-plan">
    <?php
    $perks = $perksMap->getPerksForMembershipTierId($id);
    drawMembershipPerks($planName, $price, $bookingWindowDays, $maxEnrollments, $perks);
    if ($session->isLoggedIn() && (!$session->isTrainer())) { ?>
      <form method="POST" action="../pages/membership_terminal.php">
        <input type="hidden" name="plan_id" value="<?= (int) $id; ?>">
        <button type="submit" class="btn large wide blue">Choose <?= htmlspecialchars($planName) ?></button>
      </form>
    <?php } else if (!$session->isLoggedIn()) { ?>
      <a class="btn large wide blue" href="../pages/signup.php">Choose <?= htmlspecialchars($planName) ?></a>
    <?php } ?>
  </article>
<?php } ?>

<?php function drawMembershipPlans(array $plans, Session $session, MembershipTierPerksMap $perksMap) { ?>
  <div class="plans">
    <?php foreach ($plans as $plan) {
      drawMembershipPlan($plan->getId(), $plan->getName(), $plan->getMonthlyFee(), $plan->getBookingWindowDays(), $plan->getMaxEnrollments(), $session, $perksMap);
    } ?>
  </div>
<?php } ?>

<?php function drawMembershipContainer(array $plans, Session $session, MembershipTierPerksMap $perksMap) { ?>
  <div id="membership">
    <section class="container">
      <p class="detail">OUR PLANS</p>
      <h2>MEMBERSHIP PLANS</h2>
      <?php drawMembershipPlans($plans, $session, $perksMap); ?>
    </section>
  </div>
<?php } ?>

<?php function drawIndexPage(array $plans, Session $session, MembershipTierPerksMap $perksMap) {
  drawHero();
  drawMembershipContainer($plans, $session, $perksMap);
} ?>
