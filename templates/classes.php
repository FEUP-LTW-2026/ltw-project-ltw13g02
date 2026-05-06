<?php
	declare(strict_types=1);
?>

<!-- Como tratar as images? -->
<?php function drawClass(string $className, string $schedule, string $trainer) { ?>
	<article class="card class-card">
		<header>
			<img src="https://picsum.photos/400/300" alt="Pilates">
		</header>
				
		<div>
			<h3><?= $className; ?></h3>
			<p><?= $schedule; ?></p>
			<p><time datetime="19:00">7:00 PM</time> - <time datetime="20:00">8:00 PM</time></p>
			<p>Trainer: <a class="trainer" href="../pages/trainer.php"><?= $trainer; ?></a></p>
		</div>
		
		<footer>
			<div>
				<p>
					<span class="material-symbols-outlined">timer</span>
					<time datetime="PT60M">60 min</time>
				</p>

				<p>
					<span class="material-symbols-outlined">person</span>
					<span>10/25</span>
				</p>
			</div>
			<a class="btn medium blue" href="../pages/class.php">Details</a>
		</footer>
	</article>
<?php } ?>

<?php function drawClasses(array $classes) { ?>
	<div class="card-grid class-list">
		<?php foreach ($classes as $class) { ?>
			<?php drawClass($class['name'], $class['schedule'], $class['trainer']); ?>
		<?php } ?>
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
        <select name="week-day">
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
        <select name="time">
          <option value="all" selected>All</option>
          <option value="morning">Morning</option>
          <option value="afternoon">Afternoon</option>
          <option value="evening">Evening</option>
        </select>
      </label>

      <label> Class type
        <select name="type">
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
        <select name="trainer">
          <option value="all" selected>All Trainers</option>
          <option value="john-doe">John Doe</option>
          <option value="jane-smith">Jane Smith</option>
          <option value="michael-brown">Michael Brown</option>
          <option value="emily-jones">Emily Jones</option>
        </select>
      </label>
    </form>
  </section>
<?php } ?>

<?php function drawClassesContainer(array $classes) { ?>
  <div class="container">
    <?php drawFilters(); ?>
    <?php drawClasses($classes); ?>
  </div>
<?php } ?>

<?php function drawClassesPage(array $classes) { ?>
  <?php drawClassesContainer($classes); ?>
<?php } ?>