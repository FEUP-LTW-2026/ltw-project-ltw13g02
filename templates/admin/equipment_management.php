<?php
declare(strict_types=1);

function getEquipmentTypeName(array $equipmentTypes, int $equipmentTypeId) : string {
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
  <form id="equipment-type-form" class="inline-admin-form" action="../actions/equipment/action_create_equipment_type.php" method="POST" hidden>
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
    <label>
      Equipment Type Name
      <input type="text" name="name" placeholder="Equipment type name" required>
    </label>
    <button type="submit" class="btn blue small">Save Type</button>
  </form>
<?php } ?>

<?php function drawEquipmentInlineForm(array $equipmentTypes, Session $session) { ?>
  <form id="equipment-form" class="inline-admin-form" action="../actions/equipment/action_create_equipment.php" method="POST" hidden>
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
  <form id="timeslot-form" class="inline-admin-form" action="../actions/equipment/action_create_timeslot.php" method="POST" hidden>
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
  <form class="equipment-status-form" action="../actions/equipment/action_change_equipment_status.php" method="POST">
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
    <form action="../actions/equipment/action_update_equipment.php" method="POST" class="equipment-edit-form">
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
        <button type="submit" class="btn warning small" formaction="../actions/equipment/action_delete_equipment.php" formnovalidate>Delete</button>
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
      <form action="../actions/equipment/action_delete_equipment_type.php" method="POST">
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
      <form action="../actions/equipment/action_delete_timeslot.php" method="POST">
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