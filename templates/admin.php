<?php 
  declare(strict_types=1);
?>

<?php function drawNavBar() { ?>
  <aside>
    <header>
      <h3>Admin Panel</h3>
      <p>Dashboard Management</p>
    </header>

    <nav class="admin-nav">
      <ul>
        <li><a href="#overview" class="active" data-section="overview">Overview</a></li>
        <li><a href="#trainer-creation" data-section="trainer">Create Trainer</a></li>
        <li><a href="#account-management" data-section="accounts">Manage Accounts</a></li>
        <li><a href="#class-catalog" data-section="catalog">Class Catalog</a></li>
        <li><a href="#equipment-management" data-section="equipment">Manage Equipment</a></li>
        <li><a href="#membership-tiers" data-section="tiers">Membership Tiers</a></li>
        <li><a href="#gym-settings" data-section="settings">Gym Settings</a></li>
      </ul>
    </nav>
  </aside>
<?php } ?>


<?php function drawOverview() { ?>
  <article class="dashboard-section active" id="overview">
    <header>
      <div class="section-header">
        <h2>Overview</h2>
        <p>Welcome to the admin dashboard. Here you have a bird's-eye view of your gym.</p>
      </div>
    </header>

    <!-- Example mock cards for Overview -->
    <ul class="stats-grid">
      <li>
        <article class="stat-card">
          <h4>Active Members</h4>

          <!-- In a real implementation, these values would be dynamically updated with AJAX and database data -->
          <p class="stat-number">1,245</p>
          <!-- calculated by comparing with last month, would require new table-->
          <span class="stat-change positive">+5%</span>
          <p>from last month</p>
        </article>
      </li>
      <li>
        <article class="stat-card">
          <h4>Classes Today</h4>
          <p class="stat-number">12</p>
          <span class="stat-change negative">-2%</span>
          <p>from last month</p>
        </article>
      </li>
      <li>
        <article class="stat-card">
          <h4>Equip. Maintenance</h4>
          <p class="stat-number">3</p>
          <span class="stat-change neutral">0%</span>
          <p>from last month</p>
        </article>
      </li>
    </ul>
  </article>
<?php } ?>

<?php function drawTrainerCreation(PDO $db, Session $session) { ?>
  <article class="dashboard-section" id="trainer-creation">
    <header>
      <div class="section-header">
        <h2>Create Trainer Account</h2>
        <p>Use the form below to create new trainer accounts.</p>
      </div>
    </header>
    <form action="../actions/action_create_trainer.php" method="POST">
      <fieldset>
        <label>
          First Name
          <input type="text" name="first-name" placeholder="First Name" required>
        </label>
        <label>
          Last Name
          <input type="text" name="last-name" placeholder="Last Name" required>
        </label>
        <label>
          Username
          <input type="text" name="username" placeholder="Username" required>
        </label>
      </fieldset>
      <fieldset>
        <label>
          Email
          <input type="email" name="email" placeholder="Email" required>
        </label>
        <label>
          Password
          <input type="password" name="password" placeholder="Password" required>
        </label>
        <label>
          Confirm Password
          <input type="password" name="confirm-password" placeholder="Confirm Password" required>
        </label>
      </fieldset>
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
      <button type="submit" class="btn blue medium">Create Trainer</button>
    </form>
  </article>
<?php } ?>

<?php function drawAccountManagement(array $users, Session $session, RoleManager $roleManager) { ?>
  <article class="dashboard-section" id="account-management">
    <header>
      <div class="section-header">
        <h2>Manage Accounts</h2>
        <p>Create, update, deactivate accounts, or elevate users to admin.</p>
      </div>
      <button class="btn transparent-dark medium">+ Add New User</button>
    </header>
    <!-- Search bar for filtering users, by username -->
    <input type="text" id="search-bar" placeholder="Search users by username...">
    <div class="table-responsive">
      <table class="data-table">
        <thead>
          <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user) { ?>
            <?php if ($user->getId() === $session->getId()) continue;?> <!-- Skip current user - admin -->
              <tr>
                <td><?= htmlspecialchars($user->getUsername()) ?></td>
                <td><?= htmlspecialchars($user->getEmail()) ?></td>
                <td>
                  <div class="role-update-container">
                      <span><?= htmlspecialchars($user->getRole()) ?></span>
                      <form action="../actions/action_changeRole.php" method="POST" id="role-form-<?= (int) $user->getId() ?>" class="role-update-form">
                        <input type="hidden" name="user_id" value="<?= (int) $user->getId() ?>">
                        <input type="hidden" name="user_name" value="<?= htmlspecialchars($user->getUsername()) ?>">
                        <input type="hidden" name="old_role_table" value="<?= htmlspecialchars($user->getRole()) ?>">
                        <select name="new_role_table">
                          <option value="" disabled selected>Change Role</option>
                          <?php 
                            $roles = $roleManager->getAvailableRoles($user->getRole());
                            foreach ($roles as $role) { ?>
                            <option value="<?= htmlspecialchars($role) ?>"><?= htmlspecialchars($role) ?></option>
                          <?php } ?>
                        </select>
                      </form>
                  </div>
                </td>
                <td><span class="status <?= $user->IsActive() ? 'active' : 'inactive' ?>"><?= $user->IsActive() ? 'Active' : 'Inactive' ?></span></td>
                <td class="actions">
                  <button class="btn white" type="submit" form="role-form-<?= (int) $user->getId() ?>">Submit</button>
                  <?php if (!$user->IsActive()) { ?>
                    <form action="../actions/action_toggle_user_status.php" method="POST">
                      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
                      <input type="hidden" name="user_id" value="<?= (int) $user->getId() ?>">
                      <input type="hidden" name="is_active" value="<?= !$user->IsActive() ?>">
                      <button class="btn available">Activate</button>
                    </form>
                  <?php } else { ?>
                    <form action="../actions/action_toggle_user_status.php" method="POST">
                      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
                      <input type="hidden" name="user_id" value="<?= (int) $user->getId() ?>">
                      <input type="hidden" name="is_active" value="<?= !$user->IsActive() ?>">
                      <button class="btn warning">Deactivate</button>
                    </form>
                  <?php } ?>
                </td>
              </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </article>
<?php } ?>

<?php function drawClassCatalog() { ?>
  <article class="dashboard-section" id="class-catalog">
    <header>
      <div class="section-header">
        <h2>Class Catalog</h2>
        <p>Manage the class catalog and assign trainers.</p>
      </div>
      <button class="btn transparent-dark medium">+ Add Class</button>
    </header>

    <div class="table-responsive">
      <div class="schedule-grid">
        <!-- Monday -->
        <section class="schedule-day">
          <h4>Monday</h4>
          <article class="schedule-classes">
            <article class="schedule-class-card">
              <strong>Yoga Basics</strong>
              <span>08:00 - 09:00</span>
              <span>Capacity: 20</span>
              <div class="card-actions">
                <button class="btn blue">Edit</button>
              </div>
            </article>
          </article>
          <article class="schedule-classes">
            <article class="schedule-class-card">
              <strong>Cardio</strong>
              <span>10:00 - 11:00</span>
              <span>Capacity: 25</span>
              <div class="card-actions">
                <button class="btn blue">Edit</button>
              </div>
            </article>
          </article>
        </section>

        <!-- Tuesday -->
        <section class="schedule-day">
          <h4>Tuesday</h4>
          <article class="schedule-classes">
            <article class="schedule-class-card">
              <strong>HIIT Training</strong>
              <span>18:30 - 19:30</span>
              <span>Capacity: 15</span>
              <div class="card-actions">
                <button class="btn blue">Edit</button>
              </div>
            </article>
          </article>
        </section>
        
        <section class="schedule-day"><h4>Wednesday</h4><div class="schedule-classes"></div></section>
        <section class="schedule-day"><h4>Thursday</h4><div class="schedule-classes"></div></section>
        <section class="schedule-day"><h4>Friday</h4><div class="schedule-classes"></div></section>
        <section class="schedule-day"><h4>Saturday</h4><div class="schedule-classes"></div></section>
        <section class="schedule-day"><h4>Sunday</h4><div class="schedule-classes"></div></section>
      </div>
    </div>
  </article>
<?php } ?>

<?php function getEquipmentTypeName(array $equipmentTypes, int $equipmentTypeId) : string {
  foreach ($equipmentTypes as $equipmentType) {
    if ($equipmentType->getId() === $equipmentTypeId) {
      return $equipmentType->getName();
    }
  }

  return 'Unknown';
} ?>

<?php function getEquipmentItemsByType(array $equipmentItems, int $equipmentTypeId) : array {
  $out = [];
  foreach ($equipmentItems as $item) {
    if ($item->getEquipmentTypeId() === $equipmentTypeId) {
      $out[] = $item;
    }
  }
  return $out;
} ?>

<?php function drawEquipmentTypeInlineForm(Session $session) { ?>
  <form id="equipment-type-form" class="inline-admin-form" action="../actions/action_create_equipment_type.php" method="POST" hidden>
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
    <label>
      Equipment Type Name
      <input type="text" name="name" placeholder="Equipment type name" required>
    </label>
    <button type="submit" class="btn blue small">Save Type</button>
  </form>
<?php } ?>

<?php function drawEquipmentInlineForm(array $equipmentTypes, Session $session) { ?>
  <form id="equipment-form" class="inline-admin-form" action="../actions/action_create_equipment.php" method="POST" hidden>
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
    <label>
      Equipment Type
      <select name="equipment_type_id" required>
        <option value="" disabled selected>Select type</option>
        <?php foreach ($equipmentTypes as $equipmentType) { ?>
          <option value="<?= (int)$equipmentType->getId() ?>"><?= htmlspecialchars($equipmentType->getName()) ?></option>
        <?php } ?>
      </select>
    </label>
    <label>
      Name
      <input type="text" name="name" placeholder="Equipment name" required>
    </label>
    <button type="submit" class="btn blue small">Save Equipment</button>
  </form>
<?php } ?>

<?php function drawTimeslotInlineForm(Session $session) { ?>
  <form id="timeslot-form" class="inline-admin-form" action="../actions/action_create_timeslot.php" method="POST" hidden>
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
    <label>
      Start Time
      <input type="time" name="start_time" required>
    </label>
    <label>
      End Time
      <input type="time" name="end_time" required>
    </label>
    <button type="submit" class="btn blue small">Save Timeslot</button>
  </form>
<?php } ?>

<?php function drawEquipmentStatusSelector(Equipment $equipment, EquipmentStatusManager $statusManager, Session $session) { ?>
  <form class="equipment-status-form" action="../actions/action_change_equipment_status.php" method="POST">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
    <input type="hidden" name="equipment_id" value="<?= (int)$equipment->getId() ?>">
    <input type="hidden" name="old_status" value="<?= htmlspecialchars($equipment->getStatus()) ?>">
    <div class="status-row">
      <span class="status <?= htmlspecialchars(strtolower(str_replace(' ', '-', $equipment->getStatus()))) ?>"><?= htmlspecialchars($equipment->getStatus()) ?></span>
      <select name="new_status" required>
        <option value="" disabled selected>Change status</option>
        <?php foreach ($statusManager->getAvailableStatuses($equipment->getStatus()) as $status) { ?>
          <option value="<?= htmlspecialchars($status) ?>"><?= htmlspecialchars($status) ?></option>
        <?php } ?>
      </select>
      <button type="submit" class="btn white small">Update</button>
    </div>
  </form>
<?php } ?>

<?php function drawEquipmentCard(Equipment $equipment, array $equipmentTypes, EquipmentStatusManager $statusManager, Session $session) { ?>
  <article class="card compact-card equipment-card">
    <form action="../actions/action_update_equipment.php" method="POST" class="equipment-edit-form">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
      <input type="hidden" name="equipment_id" value="<?= (int)$equipment->getId() ?>">
      <label>
        Name
        <input type="text" name="name" value="<?= htmlspecialchars($equipment->getName()) ?>" required>
      </label>
      <label>
        Type
        <select name="equipment_type_id" required>
          <?php foreach ($equipmentTypes as $equipmentType) { ?>
            <option value="<?= (int)$equipmentType->getId() ?>" <?= $equipmentType->getId() === $equipment->getEquipmentTypeId() ? 'selected' : '' ?>>
              <?= htmlspecialchars($equipmentType->getName()) ?>
            </option>
          <?php } ?>
        </select>
      </label>
      <div class="equipment-card-actions">
        <button type="submit" class="btn blue small">Save</button>
        <button type="submit" class="btn warning small" formaction="../actions/action_delete_equipment.php" formnovalidate>Delete</button>
      </div>
    </form>
    <?php drawEquipmentStatusSelector($equipment, $statusManager, $session); ?>
  </article>
<?php } ?>

<?php function drawEquipmentTypeCard(EquipmentType $equipmentType, array $equipmentItems, Session $session) { ?>
  <article class="card compact-card">
    <header class="section-header compact-header">
      <div>
        <h3><?= htmlspecialchars($equipmentType->getName()) ?></h3>
        <p><?= count($equipmentItems) ?> items</p>
      </div>
      <form action="../actions/action_delete_equipment_type.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
        <input type="hidden" name="equipment_type_id" value="<?= (int)$equipmentType->getId() ?>">
        <button type="submit" class="btn warning small" formnovalidate>Delete Type</button>
      </form>
    </header>
  </article>
<?php } ?>

<?php function drawTimeslotCard(Timeslot $timeslot, Session $session) { ?>
  <article class="card compact-card">
    <div class="section-header compact-header">
      <div>
        <h3><?= htmlspecialchars(substr($timeslot->getStartTime(), 0, 5) . ' - ' . substr($timeslot->getEndTime(), 0, 5)) ?></h3>
      </div>
      <form action="../actions/action_delete_timeslot.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
        <input type="hidden" name="timeslot_id" value="<?= (int)$timeslot->getId() ?>">
        <button type="submit" class="btn warning small" formnovalidate>Delete</button>
      </form>
    </div>
  </article>
<?php } ?>

<?php function drawEquipmentManagement(array $equipmentTypes, array $equipmentItems, array $timeslots, Session $session, EquipmentStatusManager $statusManager) { ?>
  <article class="dashboard-section" id="equipment-management">
    <header>
      <div class="section-header">
        <h2>Manage Equipment</h2>
        <p>Add, update status, and remove equipment, equipment types, and timeslots.</p>
      </div>
    </header>

    
    <section class="equipment-management-grid">
      <article>
          <h3>Equipment Types</h3>
          <button class="btn transparent-dark medium toggle-form">Toggle Form</button>
          <?php drawEquipmentTypeInlineForm($session); ?>
          <div class="equipment-card-grid">
            <?php foreach ($equipmentTypes as $equipmentType) { ?>
              <?php drawEquipmentTypeCard($equipmentType, getEquipmentItemsByType($equipmentItems, $equipmentType->getId()), $session); ?>
            <?php } ?>
          </div>
      </article>

      <article>
        <h3>Equipment</h3>
        <button class="btn transparent-dark medium toggle-form">Toggle Form</button>
        <?php drawEquipmentInlineForm($equipmentTypes, $session); ?>
        <div class="equipment-card-grid">
          <?php foreach ($equipmentItems as $equipment) { ?>
            <?php drawEquipmentCard($equipment, $equipmentTypes, $statusManager, $session); ?>
          <?php } ?>
        </div>
      </article>

      <article>
        <h3>Timeslots</h3>
        <button class="btn transparent-dark medium toggle-form">Toggle Form</button>
        <?php drawTimeslotInlineForm($session); ?>
        <div class="equipment-card-grid">
          <?php foreach ($timeslots as $timeslot) { ?>
            <?php drawTimeslotCard($timeslot, $session); ?>
          <?php } ?>
        </div>
      </article>
    </section>
  </article>
<?php } ?>

<?php function drawGymSettings() { ?>
  <article class="dashboard-section" id="gym-settings">
    <header>
      <div class="section-header">
        <h2>Gym Settings</h2>
        <p>Global gym configurations, operational hours, and membership pricing.</p>
      </div>
    </header>
    <form class="settings-form" action="#" method="POST">
      <label>
        <h4>Opening Time</h4>
        <input type="time" value="06:00">
      </label>
      <label>
        <h4>Closing Time</h4>
        <input type="time" value="23:00">
      </label>
      <label>
        <h4>Gym Capacity</h4>
        <input type="number" value="200">
      </label>
      <div>
        <button type="submit" class="btn blue">Save Settings</button>
      </div>
    </form>
  </article>
<?php } ?>

<?php function drawMembershipTiers(array $tiers, Session $session) { ?>
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
          <form action="../actions/create_membership_tier.php" method="POST" class="tier-form">
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
              <div class="description-container">
                <!-- This name allows submitting multiple values for the same variable
                    Description has multple rows - different values that all are grouped in this same array:-->
                <input type="text" name="description[]" placeholder="Perk" required>
              </div>
              <div class="controls">
                <button type="button" 
                  class="btn transparent-dark small add" 
                  data-target="description" 
                  data-label="Perk">
                  + Add</button>
                <button type="button"
                class="btn transparent-dark small remove" 
                data-target="description">
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
              <div class="description-container">
                <?php
                  $parts = array_filter(array_map('trim', explode(';', $tier->getDescription() ?? '')));
                  if (empty($parts)) { ?>
                    <!-- This name allows submitting multiple values for the same variable
                     Description has multple rows - different values that all are grouped in this same array:-->
                    <input type="text" name="description[]" placeholder="Perk" required>
                <?php } else {
                    foreach ($parts as $p) { ?>
                      <input type="text" name="description[]" value="<?= htmlspecialchars($p) ?>" required>
                <?php }
                  }
                ?>
              </div>
              <div class="controls">
                <button type="button" 
                  class="btn transparent-dark small add" 
                  data-target="description" 
                  data-label="Perk">
                  + Add</button>
                <button type="button"
                 class="btn transparent-dark small remove" 
                 data-target="description">
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


<?php function drawAdminPanel(array $users, PDO $db, Session $session, array $tiers, RoleManager $roleManager, array $equipmentTypes, array $equipmentByType, array $timeslots, EquipmentStatusManager $statusManager) { ?>
<div class="admin-dashboard container">
  <?php drawNavBar(); ?>    
    <section class="dashboard-content">
      <?php drawOverview(); ?>
      <?php drawTrainerCreation($db, $session); ?>
      <?php drawAccountManagement($users, $session, $roleManager); ?>
      <?php drawClassCatalog(); ?>
      <?php drawEquipmentManagement($equipmentTypes, $equipmentByType, $timeslots, $session, $statusManager); ?>
      <?php drawGymSettings(); ?>
      <?php drawMembershipTiers($tiers, $session); ?>
    </section>
  </div>
<?php } ?>



<script>
  document.addEventListener('DOMContentLoaded', () => {
    const navLinks = document.querySelectorAll('.admin-nav a');
    const sections = document.querySelectorAll('.dashboard-section');

    // Ensure only the active section is visible on page load
    sections.forEach(section => {
      if (section.classList.contains('active')) {
        section.style.display = 'block';
      } else {
        section.style.display = 'none';
      }
    });

    navLinks.forEach(link => {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        
        // Update active link styling
        navLinks.forEach(l => l.classList.remove('active'));
        link.classList.add('active');

        // Hide all sections and show the targeted one
        const targetId = link.getAttribute('href').substring(1);
        sections.forEach(sec => {
          if (sec.id === targetId) {
            sec.style.display = 'block';
            sec.classList.add('active');
          } else {
            sec.style.display = 'none';
            sec.classList.remove('active');
          }
        });
      });
    });
  });
</script>