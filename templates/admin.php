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


<?php function drawAccountManagement(array $users, PDO $db, Session $session, RoleManager $roleManager) { ?>
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
                <td><?= $user->getUsername() ?></td>
                <td><?= $user->getEmail() ?></td>
                <td>
                  <div class="role-update-container">
                      <span><?php echo $user->getRole(); ?></span>
                      <form action="../actions/action_changeRole.php" method="POST" id="role-form-<?= $user->getId() ?>" class="role-update-form">
                        <input type="hidden" name="user_id" value="<?= $user->getId() ?>">
                        <input type="hidden" name="user_name" value="<?= $user->getUsername() ?>">
                        <input type="hidden" name="old_role_table" value="<?= $user->getRole() ?>">
                        <select name="new_role_table">
                          <option value="" disabled selected>Change Role</option>
                          <?php 
                            $roles = $roleManager->getAvailableRoles($user->getRole());
                            foreach ($roles as $role) { ?>
                            <!-- only show roles that the user can transition to -->
                            <option value="<?= $role ?>"><?= $role ?></option>
                          <?php } ?>
                        </select>
                      </form>
                  </div>
                </td>
                <td><span class="status <?= $user->IsActive() ? 'active' : 'inactive' ?>"><?= $user->IsActive() ? 'Active' : 'Inactive' ?></span></td>
                <td class="actions">
                  <button class="btn white" type="submit" form="role-form-<?= $user->getId() ?>">Submit</button>
                  <?php if (!$user->IsActive()) { ?>
                    <form action="../actions/action_toggle_user_status.php" method="POST">
                      <input type="hidden" name="user_id" value="<?= $user->getId() ?>">
                      <input type="hidden" name="is_active" value="<?= !$user->IsActive() ?>">
                      <button class="btn available">Activate</button>
                    </form>
                  <?php } else { ?>
                    <form action="../actions/action_toggle_user_status.php" method="POST">
                      <input type="hidden" name="user_id" value="<?= $user->getId() ?>">
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

<?php function drawEquipmentManagement() { ?>
  <article class="dashboard-section" id="equipment-management">
    <header>
      <div class="section-header">
        <h2>Manage Equipment</h2>
        <p>Add, update availability status, and remove items.</p> 
      </div>
      <button class="btn transparent-dark medium">+ Add Equipment</button>
    </header>
    <div class="table-responsive">
      <table class="data-table">
        <thead>
          <tr>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Treadmill</td>
            <td>5</td>
            <td><span class="status available">Available</span></td>
            <td class="actions">
              <button class="btn white">Update</button>
              <button class="btn warning">Remove</button>
            </td>
          </tr>
          <tr>
            <td>Dumbbell Set</td>
            <td>10</td>
            <td><span class="status maintenance">Maintenance</span></td>
            <td class="actions">
              <button class="btn white">Update</button>
              <button class="btn warning">Remove</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
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

<?php function drawMembershipTiers(array $tiers) { ?>
  <article class="dashboard-section" id="membership-tiers">
    <header>
      <div class="section-header">
        <h2>Membership Tiers</h2>
        <p>Manage membership pricing and features (separated by semicolons).</p>
      </div>
      <button class="btn transparent-dark medium">+ Add Tier</button>
    </header>
    
    <div class="tiers">
      <?php foreach ($tiers as $tier) { ?>
        <article class="card tier-edit-card">
          <form action="../actions/action_update_tier.php" method="POST" class="tier-form">
            <input type="hidden" name="tier_id" value="<?= $tier->getId() ?>">
            
            <header class="tier-header">
              <input type="text" name="name" value="<?= $tier->getName() ?>" required>
              <div class="price-input-group">
                <span class="currency">€</span>
                <input type="number" step="0.01" name="price" value="<?= $tier->getMonthlyFee() ?>" class="editable-price" required>
                <span class="period">/4 weeks</span>
              </div>
              <label for="booking_window_<?= $tier->getId() ?>" class="booking-window-label">Booking Window (days):</label>
              <input type="number" name="booking_window_days" value="<?= $tier->getBookingWindowDays() ?>" class="editable-booking-window" required>
            </header>
            
            <div class="tier-features-edit">
              <label for="description_<?= $tier->getId() ?>">Description (use ';' to separate):</label>
              <textarea id="description_<?= $tier->getId() ?>" name="description" rows="4" class="editable-features" required><?= $tier->getDescription() ?></textarea>
            </div>
            
            <div class="tier-actions">
              <button type="submit" class="btn blue">Save Changes</button>
              <button  type="button" class="btn warning">Delete</button>
            </div>
          </form>
        </article>
      <?php } ?>
    </div>
  </article>
<?php } ?>


<?php function drawAdminPanel(array $users, PDO $db, Session $session, array $tiers, RoleManager $roleManager) { ?>
<div class="admin-dashboard container">
  <?php drawNavBar(); ?>    
    <section class="dashboard-content">
      <?php drawOverview(); ?>
      <?php drawAccountManagement($users, $db, $session, $roleManager); ?>
      <?php drawClassCatalog(); ?>
      <?php drawEquipmentManagement(); ?>
      <?php drawGymSettings(); ?>
      <?php drawMembershipTiers($tiers); ?>
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