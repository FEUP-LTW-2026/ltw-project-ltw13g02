<?php 
  declare(strict_types=1);
?>

<?php function drawEquipment(string $name, string $status, string $count) { ?>
  <a class="availability-data" href=<?=htmlspecialchars(strtolower($name).'-zone')?>> 
    <span class="zone-title"><?= htmlspecialchars($name) ?></span>
    <span class="availability <?= strtolower($status) ?>"><?= htmlspecialchars($status) ?></span>
    <span class="count"><?= htmlspecialchars((string)$count) ?></span>
  </a>
<?php } ?>

<?php function drawEquipmentAvailability(array $equipment) { ?>
  <section>
    <h2>Gym equipment availability</h2>
    <ul class="gym-zones">
      <?php foreach ($equipment as $item) { ?>
        <li>
          <?php drawEquipment($item['name'], $item['status'], $item['count']); ?>
        </li>
      <?php } ?>
    </ul>
  </section>
<?php } ?>

<?php function drawTreadMill(string $id, string $status) { ?>
  <article class="availability-data">
    <h3 class="treadmill">
      <span class="symbol">
        sprint
      </span>
      <?= 'TM' . htmlspecialchars((string)$id) ?>
    </h3>
    <span class="availability <?= strtolower($status) ?>"><?= htmlspecialchars($status) ?></span>
    <a href="#tm<?= htmlspecialchars((string)$id) ?>-reservation" class="btn medium transparent-dark">View reservation slots</a>
  </article>
<?php } ?>

<?php function drawTreadmillZone(array $treadmills) { ?>
  <section id="treadmill-zone">
    <h2>Treadmill zone</h2>
    <ul class="equipment-list">
      <?php foreach ($treadmills as $treadmill) { ?>
        <li>
          <?php drawTreadMill($treadmill['id'], $treadmill['status']); ?>
        </li>
      <?php } ?>
    </ul>
  </section>
<?php } ?>

<?php function drawReservationForm() { ?>
  <section id="tm01-reservation" class="reservation-form">
    <h3>Reserve Treadmill 01</h3>
    <label>
      Select a date:
      <input type="date" id="date-picker" name="date-picker">
    </label>          
    <form>
      <fieldset>
        <legend>Morning</legend>
        <input type="radio" name="time-slot" id="s1" required>
        <label for="s1">8:00</label>

        <input type="radio" name="time-slot" id="s2" required>
        <label for="s2">8:30</label>

        <input type="radio" name="time-slot" id="s3" disabled>
        <label for="s3">9:00</label>
      </fieldset>
      <fieldset>
        <legend>Afternoon</legend>
        <input type="radio" name="time-slot" id="s4" disabled>
        <label for="s4">12:00</label>

        <input type="radio" name="time-slot" id="s5" required>
        <label for="s5">12:30</label>

        <input type="radio" name="time-slot" id="s6" disabled>
        <label for="s6">13:00</label>
      </fieldset>
      <fieldset>
        <legend>Evening</legend>
        <input type="radio" name="time-slot" id="s7" required>
        <label for="s7">18:00</label>

        <input type="radio" name="time-slot" id="s8" disabled>
        <label for="s8">18:30</label>

        <input type="radio" name="time-slot" id="s9" disabled>
        <label for="s9">19:00</label>
      </fieldset>
      <button type="submit" class="btn blue medium">Reserve</button>
    </form>
  </section>
<?php } ?>

<?php function drawAvailabilityContainer(array $equipement, array $treadmills) { ?>
  <div class="container">
    <?php 
      drawEquipmentAvailability($equipement);
      drawTreadmillZone($treadmills);
      drawReservationForm();
    ?>
  </div>
<?php } ?>

<?php function drawAvailabilityPage(array $equipement, array $treadmills) {
  drawAvailabilityContainer($equipement, $treadmills);
} ?>