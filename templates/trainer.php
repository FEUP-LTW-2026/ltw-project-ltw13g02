<?php 
  declare(strict_types=1);

  require_once(__DIR__ . '/../database/trainer.class.php');
  require_once(__DIR__ . '/../database/authenticated_user.class.php');
?>

<?php function drawTrainerIntro(Trainer $trainer) { ?>
  <div id="trainer-profile" class="intro">
    <section class="container">
      <img src="https://picsum.photos/500/700" alt="Trainer">
      <div>
        <h1><?= $trainer->getFirstName() ?> <b><?= $trainer->getLastName() ?></b></h1>
        <h2>Biography</h2>
        <p><?= $trainer->bio ?></p>
        <a class="btn medium blue" href="#">BOOK A SESSION WITH <?= $trainer->getFirstName() ?> <?= $trainer->getLastName() ?></a>
      </div>
    </section>
  </div>
<?php } ?>

<?php function drawSpecialties(array $specialties) { ?>
  <ul>
    <?php foreach ($specialties as $specialty) { ?>
      <li class="card">
        <header>
          <span class="material-symbols-outlined"><?= $specialty['logo'] ?? 'exercise' ?></span>
          <h3><?= $specialty['name'] ?></h3>
        </header>
        <p><?= $specialty['description'] ?></p>
      </li>
    <?php } ?>
  </ul>
<?php } ?>

<?php function drawCertifications(array $certifications) { ?>
  <ul>
    <?php foreach ($certifications as $certification) { ?>
      <li class="card">
        <h3><?= $certification['name'] ?></h3>
      </li>
    <?php } ?>
  </ul>
<?php } ?>

<?php function drawTrainerInfoContainer(array $specialties, array $certifications) { ?>
  <div id="trainer-info">
    <div class="container">
      <article>
        <h2>Specialties</h2>
        <?php drawSpecialties($specialties) ?>
      </article>

      <article>
        <h2>Certifications</h2>
        <?php drawCertifications($certifications) ?>
      </article>
    </div>
  </div>
<?php } ?>

<?php function drawTrainerClassesContainer(string $firstname, array $classes) { ?>
  <div>
    <section class="container">
      <p class="detail">WHERE CAN YOU FIND ME?</p>
      <h2>Classes with <?= $firstname ?></h2>
      <?php drawClasses($classes) ?>
    </section>
  </div>
<?php } ?>

<?php function drawTrainerPage(Trainer $trainer) {
  drawTrainerIntro($trainer);
  drawTrainerInfoContainer($trainer->specialitizations, $trainer->certifications);
  drawTrainerClassesContainer($trainer->getFirstName(), $trainer->classes);
} ?>