<?php
	declare(strict_types=1);

  require_once (__DIR__ . '/../database/classType.class.php');
  require_once (__DIR__ . '/../database/gymClass.class.php');
  require_once (__DIR__ . '/../database/trainer.class.php');
  require_once (__DIR__ . '/../database/connection.db.php');

  // Helper function to calculate class duration in minutes
  function calculateDurationMinutes(string $startTime, string $endTime) : int {
    $start = new DateTime($startTime);
    $end = new DateTime($endTime);
    $interval = $start->diff($end);
    return (int)$interval->format('%i') + ((int)$interval->format('%h') * 60);
  }

?>

<?php function drawClass($class) { ?>
	<article class="card class-card">
		<header>
			<img src="https://picsum.photos/400/300" alt="<?= $class->name ?>">
		</header>
				
		<div>
			<h3><?= $class->name; ?></h3>
      <?php if ($class instanceof ClassType) { ?>
        <p><?= $class->weekday; ?></p>
        <p><?= $class->startTime . ' - ' . $class->endTime; ?></p>
        <?php $capacity = $class->capacity; $enrollment = $class->capacity; $classId = $class->id; ?>
      <?php } else { // GymClass ?>
        <p><?= $class->startTimestamp; ?></p>
        <?php $capacity = 0; $enrollment = $class->currentEnrollment; $classId = $class->classTypeId; ?>
      <?php } ?>
			<p>Trainer: <a class="trainer" href="../pages/trainer.php?id=<?= $class->trainerId; ?>"><?= $class->trainerName; ?></a></p>
		</div>
		
		<footer>
			<div>
				<p>
					<span class="material-symbols-outlined">timer</span>
					<time><?= calculateDurationMinutes($class->startTime ?? $class->startTimestamp, $class->endTime ?? $class->endTimestamp) ?> min</time>
				</p>

				<p>
					<span class="material-symbols-outlined">person</span>
					<span><?= $enrollment; ?></span>
				</p>
			</div>
			<a class="btn medium blue" href="../pages/class.php?id=<?= $classId; ?>">Details</a>
		</footer>
	</article>
<?php } ?>

<?php function drawClasses(array $classes) { ?>
	<div class="card-grid class-list">
		<?php foreach ($classes as $class) {
			drawClass($class);
		} ?>
	</div>
<?php } ?>

<?php function drawFilters() { ?>
 <section id="filters">
    <header>
      <h2>Search</h2>
      <p>Choose the day, time, class type and trainer that fit your routine.</p>
    </header>

    <form>
      <label> Day of week
        <select name="week-day" id="week-day">
          <option value="all" selected>All</option>
          <option value="monday">Monday</option>
          <option value="tuesday">Tuesday</option>
          <option value="wednesday">Wednesday</option>
          <option value="thursday">Thursday</option>
          <option value="friday">Friday</option>
          <option value="saturday">Saturday</option>
          <option value="sunday">Sunday</option>
        </select>
      </label>

      <label> Time of day
        <select name="time" id="time">
          <option value="all" selected>All</option>
          <option value="morning">Morning</option>
          <option value="afternoon">Afternoon</option>
          <option value="evening">Evening</option>
        </select>
      </label>

      <label> Class type
        <select name="type" id="type">
          <option value="all" selected>All</option>
          <option value="weightlifting">Weightlifting</option>
          <option value="yoga">Yoga</option>
          <option value="pilates">Pilates</option>
          <option value="cardio">Cardio</option>
          <option value="boxing">Boxing</option>
          <option value="crossfit">CrossFit</option>
        </select>
      </label>

      <label> Trainer
        <select name="trainer" id="trainer">
          <option value="all" selected>All Trainers</option>
          <?php  
            $trainers = Trainer::getAllTrainers(getDatabaseConnection());
            foreach ($trainers as $trainer) { ?>
              <option value="<?= strtolower(str_replace(" ", "-", $trainer->getFirstName() . " " . $trainer->getLastName())) ?>"><?= $trainer->getFirstName() . ' ' . $trainer->getLastName() ?></option>
            <?php }
          ?>
        </select>
      </label>
    </form>
  </section>
<?php } ?>

<?php function drawClassesContainer(array $classes) { ?>
  <div class="container">
    <?php
      drawFilters();
      drawClasses($classes);
    ?>
  </div>
<?php } ?>

<?php function drawClassesPage(array $classes) {
  drawClassesContainer($classes);
} ?>