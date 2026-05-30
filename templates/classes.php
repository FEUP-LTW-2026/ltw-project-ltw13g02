<?php
	declare(strict_types=1);

    require_once (__DIR__ . '/../database/classType.class.php');
    require_once (__DIR__ . '/../database/gymClass.class.php');
    require_once (__DIR__ . '/../database/trainer.class.php');
    require_once (__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/common.php');
    require_once(__DIR__ . '/class.php');
?>

<?php function drawClassType(ClassType $class) { ?>
	<article class="card class-card">
		<header>
			<?php drawPicture($class->getId(), 'classes') ?>
		</header>

		<div>
			<h3><?= htmlspecialchars($class->getName()) ?></h3>
      <p>
        <span class="symbol">calendar_today</span>
        <?= htmlspecialchars($class->getWeekday()) ?>
      </p>
      <?php
        drawTime($class->getStartTime(), $class->getEndTime());
        drawClassTrainer($class->getTrainerId(), $class->getTrainerName());
      ?>
		</div>

		<footer>
			<div>
				<?php
            drawClassDuration($class->getStartTime(), $class->getEndTime());
            drawClassTypeCapacity($class->getCapacity());
          ?>
			</div>
			<a class="btn medium blue" href="../pages/class.php?id=<?= htmlspecialchars((string)$class->getId()) ?>">Details</a>
		</footer>
	</article>
<?php } ?>

<?php function drawClasses(array $classes) { ?>
	<div class="card-grid class-list">
		<?php foreach ($classes as $class) {
			drawClassType($class);
		} ?>
	</div>
<?php } ?>

<?php function drawFilters(array $classes) { ?>
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
            <?php
                foreach ($classes as $classType) { ?>
                    <option value="<?= htmlspecialchars(strtolower(str_replace(" ", "-", $classType->getName()))) ?>"><?= htmlspecialchars($classType->getName()) ?></option>
                <?php }
            ?>

        </select>
      </label>

      <label> Trainer
        <select name="trainer" id="trainer">
          <option value="all" selected>All Trainers</option>
          <?php  
            $trainers = Trainer::getAllTrainers(getDatabaseConnection());
            foreach ($trainers as $trainer) { ?>
              <option value="<?= htmlspecialchars(strtolower(str_replace(" ", "-", $trainer->getFirstName() . " " . $trainer->getLastName()))) ?>"><?= htmlspecialchars($trainer->getFirstName() . ' ' . $trainer->getLastName()) ?></option>
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
      drawFilters($classes);
      drawClasses($classes);
    ?>
  </div>
<?php } ?>

<?php function drawClassesPage(array $classes) {
  drawClassesContainer($classes);
} ?>