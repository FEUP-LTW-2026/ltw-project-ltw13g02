<?php 
  declare(strict_types=1);
  require_once(__DIR__ . '/../utils/helpers.php');
?>

<?php function drawTrainer(Trainer $trainer) { ?>
  <a href="trainer.php?id=<?= $trainer->getId() ?>">
    <article class="card trainer-card" style="background-image:url(<?= getProfileImageUrl($trainer->getId()) ?>)">
      <h2><?= $trainer->getFirstName() . ' ' . $trainer->getLastName(); ?></h2>
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