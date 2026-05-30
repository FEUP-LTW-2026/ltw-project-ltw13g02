<?php 
  declare(strict_types=1);
?>

<?php function drawEquipmentTypeAvailability(string $name, int $availableCount, int $totalCount) { ?>
  <?php $zoneId = 'equipment-zone-' . strtolower(trim($name)); ?>
  <button type="button" class="availability-data zone-toggle" data-target="<?= htmlspecialchars($zoneId) ?>"> 
    <span class="zone-title"><?= htmlspecialchars($name) ?></span>
    <span class="count">Available: <?= $availableCount ?> / <?= $totalCount ?></span>
  </button>
<?php } ?>

<?php function drawEquipmentTypesAvailability(array $equipmentTypes, EquipmentTypeMap $equipmentTypeMap) { ?>
  <section>
    <h2>Gym equipment availability</h2>
    <ul class="gym-zones">
      <?php foreach ($equipmentTypes as $type) { ?>
        <li>
          <?php drawEquipmentTypeAvailability($type->getName(), $equipmentTypeMap->getAvailableCount($type->getId()), $equipmentTypeMap->getTotalCount($type->getId())); ?>
        </li>
      <?php } ?>
    </ul>
  </section>
<?php } ?>

<?php function drawEquipments(array $equipments, string $equipmentTypeName) { ?>
  <?php $zoneId = 'equipment-zone-' . strtolower(trim($equipmentTypeName)); ?>
  <section id="<?= htmlspecialchars($zoneId) ?>" class="equipment-zone" hidden>
    <?php if (array_filter($equipments, fn($equipment) => $equipment->getStatus() === 'available')) { ?>
      <h2><?= htmlspecialchars($equipmentTypeName) . ' Zone' ?></h2>
    <?php } ?>
    <ul class="equipment-list">
      <?php foreach ($equipments as $equipment) { ?>
        <?php if ($equipment->getStatus() === 'available') { ?>
          <li>
            <?php drawEquipment($equipment); ?>
          </li>
        <?php } ?>
      <?php } ?>
    </ul>
  </section>
<?php } ?>


<?php function drawReservationForms(array $equipments, array $timeslots, PDO $db, Session $session) { ?>
  <?php foreach ($equipments as $equipment) { ?>
    <?php if ($equipment->getStatus() === 'available') { ?>
      <?php drawReservationForm($equipment, $timeslots, $db, $session); ?>
    <?php } ?>
  <?php } ?>
<?php } ?>

<?php function drawReservationForm(Equipment $equipment, array $timeslots, PDO $db, Session $session) { ?>
  <?php $reservationId = 'reservation-for-equipment-' . (int)$equipment->getId(); ?>
  <section id="<?= htmlspecialchars($reservationId) ?>" class="reservation-form" hidden>
    <h3>Reserve <?= htmlspecialchars($equipment->getName()) ?></h3>          
    <form action="../actions/equipment/action_create_equipment_booking.php" method="POST"> 
      <label>
      Select a date:
      <input type="date" name="date-picker" min="<?= htmlspecialchars(date('Y-m-d')) ?>" max="<?= htmlspecialchars(date('Y-m-d')) ?>" value="<?= htmlspecialchars(date('Y-m-d')) ?>">
    </label>
      <fieldset>
        <?php 
          foreach ($timeslots as $timeslot) {
            $isAvailable = Timeslot::isAvailable($db, $timeslot->getId(), $equipment->getId(), date('Y-m-d'));
            ?>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
            <input type="hidden" name="equipment_id" value="<?= htmlspecialchars((string)$equipment->getId()) ?>">
            <input type="hidden" name="booking_date" value="<?= htmlspecialchars(date('Y-m-d')) ?>">
            <input type="radio" 
              name="time-slot" 
              id="<?= htmlspecialchars((string)$equipment->getId()) . '-' . htmlspecialchars((string)$timeslot->getId()) ?>" 
              value="<?= htmlspecialchars((string)$timeslot->getId()) ?>" 
              <?= $isAvailable ? '' : 'disabled' ?> required>
            <label 
              for="<?= htmlspecialchars((string)$equipment->getId()) . '-' . htmlspecialchars((string)$timeslot->getId()) ?>">
              <?= htmlspecialchars($timeslot->getStartTime()) ?> - <?= htmlspecialchars($timeslot->getEndTime()) ?>
            </label>
            <?php
          }
        ?>
      </fieldset>
      <button type="submit" class="btn blue medium">Reserve</button>
    </form>
  </section>
<?php } ?>

<?php function drawEquipment(Equipment $equipment) { ?>
    <?php $reservationId = 'reservation-for-equipment-' . (int)$equipment->getId(); ?>
    <article class="availability-data">
      <h3 class="treadmill">
        <span class="symbol">
          sprint
        </span>
        <?= htmlspecialchars($equipment->getName()) ?>
    </h3>
    <span class="availability <?= strtolower($equipment->getStatus()) ?>"><?= htmlspecialchars($equipment->getStatus()) ?></span>
    <button type="button" class="btn medium transparent-dark reservation-toggle" data-target="<?= htmlspecialchars($reservationId) ?>">View reservation slots</button>
  </article>
<?php } ?>

<?php function drawAvailabilityContainer(array $equipmentTypes, EquipmentTypeMap $equipmentTypeMap, array $timeslots, PDO $db, Session $session) { ?>
  <div class="container">
    <?php 
      drawEquipmentTypesAvailability($equipmentTypes, $equipmentTypeMap);

      foreach ($equipmentTypes as $type) {
        drawEquipments($equipmentTypeMap->getEquipmentsByTypeId($type->getId()), $type->getName());
        drawReservationForms($equipmentTypeMap->getEquipmentsByTypeId($type->getId()), $timeslots, $db, $session);
      }
    ?>
  </div>
<?php } ?>



<?php function drawAvailabilityPage(array $equipmentTypes, EquipmentTypeMap $equipmentTypeMap, array $timeslots, PDO $db, Session $session) {
  drawAvailabilityContainer($equipmentTypes, $equipmentTypeMap, $timeslots, $db, $session); 
} ?>