<?php
declare(strict_types=1);

function drawMembershipTiers(array $tiers, Session $session, MembershipTierPerksMap $perksMap) { ?>
  <article class="dashboard-section" id="membership-tiers">
    <header>
      <div class="section-header">
        <h2>Membership Tiers</h2>
        <p>Manage membership pricing and features (separated by semicolons).</p>
      </div>
      <button class="btn transparent-dark medium add-tier">+ Add Tier</button>
       <!-- Using a template to be able to clone it when adding new tiers with JS  -->
      <template id="tier-template">
        <article class="card tier-edit-card is-active">
          <form action="../actions/action_create_membership_tier.php" method="POST" class="tier-form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
            <input type="hidden" name="tier_id" value="">
            <header class="tier-header">
              <input type="text" name="name" value="" placeholder="Tier Name" required>
              <div class="price-input-group">
                <span class="currency">€</span>
                <input type="number" step="0.01" name="price" value="" class="editable-price" placeholder="Monthly Fee" required>
                <span class="period">/4 weeks</span>
              </div>
              <label for="" class="booking-window-label">Booking Window (days):</label>
              <input type="number" id="" name="booking_window_days" value="" class="editable-booking-window" placeholder="Booking Window in Days" required>
            </header>
            
            <label>
              Description (add each perk separately):
              <div class="perks-container">
                <!-- This name allows submitting multiple values for the same variable
                    Description has multple rows - different values that all are grouped in this same array:-->
                <input type="text" name="perks[]" placeholder="Perk" required>
              </div>
              <div class="controls">
                <button type="button" 
                  class="btn transparent-dark small add" 
                  data-target="perks" 
                  data-label="Perk">
                  + Add</button>
                <button type="button"
                class="btn transparent-dark small remove" 
                data-target="perks">
                - Remove</button>
              </div>
            </label>
            
            <div class="tier-actions">
              <button type="submit" class="btn blue">Save Changes</button>
              <!-- formnovalidate allows submitting the form without filling all required fields -->
              <button type="submit"
                      class="btn available"
                      formaction="../actions/action_enable_membership_tier.php"
                      formnovalidate>
                Enable Tier
              </button>
            </div>
          </form>
        </article>
        </template>
    </header>
    
    <div class="tiers">
      <?php foreach ($tiers as $tier) { ?>
        <article class="card tier-edit-card <?= $tier->isActive() ? 'is-active' : 'is-inactive' ?>">
          <form action="../actions/action_update_tier.php" method="POST" class="tier-form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
            <input type="hidden" name="tier_id" value="<?= (int) $tier->getId() ?>">
            <header class="tier-header">
              <input type="text" name="name" value="<?= htmlspecialchars($tier->getName()) ?>" required>
              <div class="price-input-group">
                <span class="currency">€</span>
                <input type="number" step="0.01" name="price" value="<?= $tier->getMonthlyFee() ?>" class="editable-price" required>
                <span class="period">/4 weeks</span>
              </div>
              <label for="booking_window_days<?= (int) $tier->getId() ?>" class="booking-window-label">Booking Window (days):</label>
              <input type="number" id="booking_window_days<?= (int) $tier->getId() ?>" name="booking_window_days" value="<?= $tier->getBookingWindowDays() ?>" class="editable-booking-window" required>
            </header>
            
            <label>
              Description (add each perk separately):
              <div class="perks-container">
                <?php
                      $perks = $perksMap->getPerksForMembershipTierId($tier->getId());
                      if (empty($perks)) { ?>
                        <input type="text" name="perks[]" placeholder="Perk" required>
                      <?php } else {
                        foreach ($perks as $p) { ?>
                          <input type="text" name="perks[]" value="<?= htmlspecialchars($p->getDescription()) ?>" required>
                        <?php }
                      }
                    ?>
              </div>
              <div class="controls">
                <button type="button" 
                  class="btn transparent-dark small add" 
                  data-target="perks" 
                  data-label="Perk">
                  + Add</button>
                <button type="button"
                 class="btn transparent-dark small remove" 
                 data-target="perks">
                 - Remove</button>
              </div>
            </label>
            
            <div class="tier-actions">
              <button type="submit" class="btn blue" <?= $tier->isActive() ? '' : 'disabled' ?>>Save Changes</button>
              <?php if ($tier->isActive()) { ?>
                <!-- formnovalidate allows submitting the form without filling all required fields -->
                <button type="submit"
                        class="btn warning"
                        formaction="../actions/action_disable_membership_tier.php"
                        formnovalidate>
                  Disable Tier
                </button>
              <?php } else { ?>
                <button type="submit"
                        class="btn available"
                        formaction="../actions/action_enable_membership_tier.php"
                        formnovalidate>
                  Enable Tier
                </button>
                <button type="submit"
                        class="btn warning"
                        formaction="../actions/action_delete_membership_tier.php"
                        formnovalidate>
                  Delete Tier
                </button>
              <?php } ?>
            </div>
          </form>
        </article>
      <?php } ?>
    </div>
  </article>
<?php } ?>