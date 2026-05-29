<?php 
  declare(strict_types=1);

  require_once(__DIR__ . '/../database/classType.class.php');

  require_once(__DIR__ . '/../database/admin-dash-queries.class.php')
?>

<?php function drawNavBar() { ?>
  <aside>
    <header>
      <h3>Admin Panel</h3>
      <p>Dashboard Management</p>
    </header>

    <nav class="admin-nav">
      <ul>
        <li><a href="#overview" class="active" data-section="overview">Monthly Overview</a></li>
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


<?php function drawOverview(PDO $db) { ?>
  <article class="dashboard-section active" id="overview">
    <header>
      <div class="section-header">
        <h2>Monthly Overview</h2>
        <p>Welcome to the admin dashboard. Here you have a bird's-eye view of your gym.</p>
      </div>
    </header>

    <!-- Example mock cards for Overview -->
    <ul class="stats-grid">
      <li>
        <article class="stat-card">
          <h4>Monthly Revenue By Tier</h4>
          <?php foreach (QueriesForAdminDashboard::getMonthlyRevenueByTier($db) as $row) { ?>
            <p><?= htmlspecialchars($row['tier']) ?>: <span class="stat-number"><?= number_format($row['monthly_revenue'], 2) ?>€</span></p>
          <?php } ?>
          <p>Total Monthly Revenue: <span class="stat-number"><?= number_format(QueriesForAdminDashboard::getTotalMonthlyRevenue($db), 2) ?>€</span></p>
        </article>
      </li>
      <li>
        <article class="stat-card">
          <h4>Active Memberships By Tier</h4>
          <?php foreach (QueriesForAdminDashboard::getActiveMembershipsByTier($db) as $row) { ?>
            <p><?= htmlspecialchars($row['tier']) ?>: <span class="stat-number"><?= (int)$row['active_memberships'] ?></span></p>
          <?php } ?>
        </article>
      </li>
      <li>
        <article class="stat-card">
          <h4>Equipments in Maintenance</h4>
          <?php foreach (QueriesForAdminDashboard::equipmentsInMaintenance($db) as $row) { ?>
            <p><span class="stat-number"><?= htmlspecialchars($row['equipment_type_name']) ?></span> - <?= htmlspecialchars($row['equipment_name']) ?></p>
          <?php } ?>
        </article>
      </li>
      <li>
        <article class="stat-card">
          <h4>Most Booked Equipment This Month</h4>
          <?php foreach (QueriesForAdminDashboard::getMostBookedEquipmentThisMonth($db) as $row) { ?>
            <p><span class="stat-number"><?= htmlspecialchars($row['equipment_type_name']) ?></span> <?= htmlspecialchars($row['equipment_name']) ?> - <?= (int)$row['bookings'] ?> bookings</p>
          <?php } ?>
        </article>
      </li>
      <li>
        <article class="stat-card">
          <h4>Most Attended Classes This Month</h4>
          <?php foreach (QueriesForAdminDashboard::getMostAttendedClassesThisMonth($db) as $row) { ?>
            <p><span class="stat-number"><?= htmlspecialchars($row['class_name']) ?></span> - <?= $row['trainer_name'] ?> - <?= (int)$row['enrollments'] ?> attended</p>
          <?php } ?>
        </article>
      </li>
      <li>
        <article>
          <h4>Best Reviewed Classes</h4>
          <?php foreach (QueriesForAdminDashboard::getBestReviewedClasses($db) as $row) { ?>
            <p>
              <span class="stat-number"><?= htmlspecialchars($row['class_name']) ?></span>
               - 
               <?= number_format($row['average_rating'], 2) ?> stars
               (<?= (int)$row['review_count'] === 1 ? (int)$row['review_count'] . ' review' : (int)$row['review_count'] . ' reviews' ?>)
            </p>
          <?php } ?>
        </article>
      </li>
    </ul>
  </article>
<?php } ?>

<?php function drawTrainerCreation(Session $session) { ?>
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
      <button class="btn transparent-dark medium" id="add-user-btn">+ Add New User</button>
    </header>
    <!-- Search bar for filtering users, by username -->
    <input type="text" id="search-bar" placeholder="Search users by username...">
    <div class="table-container">
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
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
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
    <dialog id="dialog-add-user">
      <div class="dialog-content">
        <h3>Add New User</h3>
        <form action="../actions/action_create_user.php" method="POST">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">

          <label>First Name
            <input type="text" name="first-name" placeholder="First Name" required>
          </label>

          <label>Last Name
            <input type="text" name="last-name" placeholder="Last Name" required>
          </label>

          <label>Username
            <input type="text" name="username" placeholder="Username" required>
          </label>

          <label>Email
            <input type="email" name="email" placeholder="Email" required>
          </label>

          <label>Password
            <input type="password" name="password" placeholder="Password" required>
          </label>

          <label>Confirm Password
            <input type="password" name="confirm-password" placeholder="Confirm Password" required>
          </label>

          <div class="dialog-actions">
            <button type="submit" class="btn medium blue">Create User</button>
            <button type="button" class="btn medium warning btn-close-dialog">Cancel</button>
          </div>
        </form>
      </div>
    </dialog>
  </article>
<?php } ?>

<?php function drawCreateClassForm(Session $session, array $trainers) { ?>
  <dialog id="dialog-create-class">
    <div class="dialog-content">
      <h3>Add New Class</h3>
      <form action="../actions/action_create_class.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">

        <label>Class Name
          <input type="text" name="name" required>
        </label>

        <label>Description
          <input type="text" name="description" required>
        </label>

        <label>Trainer
          <select name="trainer_id" required>
            <option value="" disabled selected>Select a trainer</option>
            
            <?php foreach ($trainers as $trainer) { ?> 
              <option value="<?= (int) $trainer->getId() ?>"><?= htmlspecialchars($trainer->getFullName()) ?></option>
            <?php } ?>
          </select>
        </label>

        <label>Capacity
          <input type="number" name="capacity" min="1" required>
        </label>

        <label>Weekday
          <select name="weekday" required>
            <option value="" disabled selected>Select a day</option>
            <option value="1">Monday</option>
            <option value="2">Tuesday</option>
            <option value="3">Wednesday</option>
            <option value="4">Thursday</option>
            <option value="5">Friday</option>
            <option value="6">Saturday</option>
            <option value="0">Sunday</option>
          </select>
        </label>

        <label>Start Time
          <input type="time" name="start_time" required>
        </label>

        <label>End Time
          <input type="time" name="end_time" required>
        </label>

        <div class="dialog-actions">
          <button type="submit" class="btn medium blue">Save</button>
          <button type="button" class="btn medium warning btn-close-dialog">Cancel</button>
        </div>
      </form>
    </div>
  </dialog>
<?php } ?>

<?php function drawEditClassForm(Session $session, array $trainers) { ?>
  <dialog id="dialog-edit-class">
    <section class="dialog-content">
      <h3>Edit Class</h3>
      <form action="../actions/action_edit_class.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
        <input type="hidden" name="class_id" id="edit-class-id">
        
        <label>Class Name
          <input type="text" name="name" id="edit-class-name" required>
        </label>

        <label>Description
          <input type="text" name="description" id="edit-class-description" required>
        </label>

        <label>Trainer
          <select name="trainer_id" id="edit-class-trainer" required>
            <option value="" disabled selected>Select a trainer</option>
            
            <?php foreach ($trainers as $trainer) { ?> 
              <option value="<?= (int) $trainer->getId() ?>"><?= htmlspecialchars($trainer->getFullName()) ?></option>
            <?php } ?>
          </select>
        </label>

        <label>Capacity
          <input type="number" name="capacity" id="edit-class-capacity" min="1" required>
        </label>

        <label>Weekday
          <select name="weekday" id="edit-class-weekday" required>
            <option value="" disabled selected>Select a day</option>
            <option value="Monday">Monday</option>
            <option value="Tuesday">Tuesday</option>
            <option value="Wednesday">Wednesday</option>
            <option value="Thursday">Thursday</option>
            <option value="Friday">Friday</option>
            <option value="Saturday">Saturday</option>
            <option value="Sunday">Sunday</option>
          </select>
        </label>

        <label>Start Time
          <input type="time" name="start_time" id="edit-class-start-time" required>
        </label>

        <label>End Time
          <input type="time" name="end_time" id="edit-class-end-time" required>
        </label>
        
        <div class="dialog-actions">
          <button type="submit" class="btn medium blue">Update</button>
          <button type="button" class="btn medium warning btn-close-dialog">Cancel</button>
        </div>
      </form>
    </section>
  </dialog>
<?php } ?>

<?php function drawClassScheduleCard(string $weekDay, array $classes, Session $session) { ?>
  <section class="schedule-day">
    <h4><?= htmlspecialchars($weekDay) ?></h4>
    <article class="schedule-classes">

      <?php foreach ($classes as $weekdayClass) { ?>
        <article class="schedule-class-card">
          <strong><?= htmlspecialchars($weekdayClass->name) ?></strong>

          <span><?= htmlspecialchars($weekdayClass->startTime) ?> - <?= htmlspecialchars($weekdayClass->endTime) ?></span>
          <?php drawClassTypeCapacity($weekdayClass->capacity) ?>
          <div class="card-actions">
            <button class="btn blue btn-edit-class" 
              data-id="<?= htmlspecialchars((string) ($weekdayClass->id ?? '')) ?>"
              data-name="<?= htmlspecialchars($weekdayClass->name ?? '') ?>"
              data-description="<?= htmlspecialchars($weekdayClass->description ?? '') ?>"
              data-trainer-id="<?= htmlspecialchars((string) ($weekdayClass->trainerId ?? '')) ?>"
              data-capacity="<?= htmlspecialchars((string) ($weekdayClass->capacity ?? '')) ?>"
              data-weekday="<?= htmlspecialchars((string) ($weekdayClass->weekday ?? '')) ?>"
              data-start-time="<?= htmlspecialchars($weekdayClass->startTime ?? '') ?>"
              data-end-time="<?= htmlspecialchars($weekdayClass->endTime ?? '') ?>">
              Edit
            </button>

            <form action="../actions/action_delete_class.php" method="POST">
              <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
              <input type="hidden" name="class_id" value="<?= htmlspecialchars((string) ($weekdayClass->id ?? '')) ?>">
              <button type="submit" class="btn warning">Delete</button>
            </form>
            
          </div>
        </article>
      <?php } ?>

    </article>
  </section>
<?php } ?>

<?php function drawClassCatalog(array $classesByWeekday, array $trainers, Session $session) { ?>
  <article class="dashboard-section" id="class-catalog">
    <header>
      <div class="section-header">
        <h2>Class Catalog</h2>
        <p>Manage the class catalog and assign trainers.</p>
      </div>
      <button id="add-class-btn" class="btn transparent-dark medium">+ Add Class</button>
    </header>

    <div class="table-container">
      <div class="schedule-grid">
        <?php foreach ($classesByWeekday as $index => $classes) { 
          $weekDay = ClassType::$weekdays[$index];
          drawClassScheduleCard($weekDay, $classes, $session);
        } ?>
      </div>
    </div>

    <?php drawCreateClassForm($session, $trainers); ?>
    <?php drawEditClassForm($session, $trainers); ?>
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

<?php function drawMembershipTiers(array $tiers, Session $session, MembershipTierPerksMap $perksMap) { ?>
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


<?php function drawAdminPanel(array $users, PDO $db, Session $session, array $tiers, RoleManager $roleManager
, array $equipmentTypes, array $equipmentByType, array $timeslots, EquipmentStatusManager $statusManager, array $classesByWeekday, array $trainers, MembershipTierPerksMap $perksMap) { ?>
<div class="admin-dashboard container">
  <?php drawNavBar(); ?>    
    <section class="dashboard-content">
      <?php drawOverview($db); ?>
      <?php drawTrainerCreation($session); ?>
      <?php drawAccountManagement($users, $session, $roleManager); ?>
      <?php drawClassCatalog($classesByWeekday, $trainers, $session); ?>
      <?php drawEquipmentManagement($equipmentTypes, $equipmentByType, $timeslots, $session, $statusManager); ?>
      <?php drawGymSettings(); ?>
      <?php drawMembershipTiers($tiers, $session, $perksMap); ?>
    </section>
  </div>
<?php } ?>