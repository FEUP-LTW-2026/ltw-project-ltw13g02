<?php 
  declare(strict_types=1);
?>

<?php function drawTrainer($name, $specialty) { ?>
  <a href="trainer.php">
    <article class="card trainer-card" style="background-image: url('https://picsum.photos/400/300')">
      <h2><?= $name; ?></h2>
      <p><?= $specialty; ?></p>
    </article>
  </a>
<?php } ?>

<?php function drawTrainers(array $trainers) { ?>
  <div class="card-grid">
    <?php foreach ($trainers as $trainer) {
      drawTrainer($trainer['name'], $trainer['specialty']);
    } ?>
  </div>
<?php } ?>

<?php function drawTrainersContainer(array $trainers) { ?>
  <div class="container">
    <?php drawTrainers($trainers); ?>
  </div>
<?php } ?>

<?php function drawTrainersPage(array $trainers) {
  drawTrainersContainer($trainers);
} ?>