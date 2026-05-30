<?php function drawMembershipTerminal(int $id, string $planName, float $price, int $plan_id, array $perks) { ?>
  <label class="card membership-plan">
    <article>
      <h3><?= htmlspecialchars($planName) ?></h3>
      <p><span><?= $price; ?>€</span>/4 weeks</p>
      <p>What's included:</p>
      <ul>
        <?php foreach ($perks as $perk) { ?>
          <li><?= htmlspecialchars($perk->getDescription()) ?></li>
        <?php } ?>
      </ul>
      <input type="radio" 
             name="tier_id" 
             id="tier-<?= $id; ?>" 
             value="<?= (int) $id; ?>" 
             data-tier-price="<?= $price; ?>" 
             data-tier-name="<?= htmlspecialchars($planName) ?>" 
             required 
             <?php if ($plan_id === $id) 
             echo 'checked'; ?>>
    </article>
  </label>
<?php } ?>

<?php function drawMembershipPlansTerminal(array $plans, int $plan_id, MembershipTierPerksMap $perksMap) { ?>
  <div class="plans">
    <?php foreach ($plans as $plan) {
      drawMembershipTerminal($plan->getId(), $plan->getName(), $plan->getMonthlyFee(), $plan_id, $perksMap->getPerksForMembershipTierId($plan->getId()));
    } ?>
  </div>
<?php } ?>

<?php function drawMembershipTerminalContainer(array $plans, int $plan_id, MembershipTierPerksMap $perksMap) { ?>
  <div id="membership">
    <section class="container">
      <?php drawMembershipPlansTerminal($plans, $plan_id, $perksMap); ?>
    </section>
  </div>
<?php } ?>

<?php function drawMembershipTerminalForm(array $plans, int $plan_id, Session $session, MembershipTierPerksMap $perksMap, PDO $db) { ?>  
  <section id="membership-terminal" class="container">
    <form action="../actions/action_checkout_membership.php" method="POST" id="membership-form">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
      <fieldset class="terminal-step" id="step-tier">
        <legend>
          <h2>1. Select Your Tier</h2>
        </legend>
        <?php drawMembershipTerminalContainer($plans, $plan_id, $perksMap); ?>
      </fieldset>

      <fieldset class="terminal-step" id="step-duration">
        <legend><h2>2. Enrollment Duration</h2></legend>
        <div class="duration-pills">
          <label class="duration-pill" for="dur-1">
            <input type="radio" name="duration" id="dur-1" value="1" required>
            <span class="pill-text">1 Month</span>
          </label>
          <label class="duration-pill" for="dur-3">
            <input type="radio" name="duration" id="dur-3" value="3" required>
            <span class="pill-text">3 Months</span>
          </label>
          <label class="duration-pill" for="dur-6">
            <input type="radio" name="duration" id="dur-6" value="6" required checked>
            <span class="pill-text">6 Months</span>
          </label>
          <label class="duration-pill" for="dur-12">
            <input type="radio" name="duration" id="dur-12" value="12" required>
            <span class="pill-text">12 Months</span>
          </label>
        </div>
      </fieldset>

      <fieldset class="terminal-step" id="step-payment">
          <legend><h2>3. Payment Summary</h2></legend>
          <div class="payment-grid">
            
            <div class="payment-options">
              <article class="card payment-card">
                <label for="pay-monthly" class="pay-content">
                  <input type="radio" name="payment_type" id="pay-monthly" value="monthly" required checked>
                  <header>
                    <?php if (MembershipTier::hasValidMemberships($db, $session->getId())) { ?>
                      <h4>Renewal</h4>
                      <p>Can be cancelled afterwards if needed.</p>
                    <?php } else { ?>
                      <h4>Pay Monthly</h4>
                      <p>First month due today. Can be cancelled afterwards if needed.</p>
                    <?php } ?>
                  </header>
                </label>
              </article>

              <article class="card payment-card highlight-save">
                <label for="pay-upfront" class="pay-content">
                  <input type="radio" name="payment_type" id="pay-upfront" value="upfront" required>
                  <header>
                    <h4>Pay All Now</h4>
                  </header>
                  <p>Save 10% on the total duration cost.</p>
                  <span class="badge-save">Save 10%</span>
                </label>
              </article>
            </div>

            <aside class="summary-box">
              <header>
                <h3>Order Summary</h3>
              </header>
              <div class="summary-row">
                <span>Selected Tier:</span>
                <strong id="summary-tier"></strong>
              </div>
              <div class="summary-row">
                <span>Duration:</span>
                <strong id="summary-duration"></strong>
              </div>
              <hr>
              <div class="summary-row total">
                <span>Due Today:</span>
                <strong id="summary-total"></strong>
              </div>
              <footer>
                <?php if (!$session->isTrainer() && !$session->isAdmin()) { ?>
                  <button type="submit" class="btn blue large wide btn-cta">Continue to Payment</button>
                <?php } ?>
              </footer>
            </aside>
          </div>
        </fieldset>
      </form>
  </section>
<?php } ?>