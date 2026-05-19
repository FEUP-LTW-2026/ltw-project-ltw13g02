<?php 
  declare(strict_types=1);
?>

<?php function drawTrainer(Trainer $trainer) { ?>
  <a href="trainer.php?id=<?= $trainer->getId() ?>">
    <article class="card trainer-card" style="background-image: url('https://picsum.photos/400/300')">
      <h2><?= $trainer->getFirstName() . ' ' . $trainer->getLastName(); ?></h2>
      <!-- Have here a specialization -->
    </article>
  </a>
<?php } ?>

<?php function drawTrainers(array $trainers) { ?>
  <div class="card-grid">
    <?php foreach ($trainers as $trainer) {
      drawTrainer($trainer);
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