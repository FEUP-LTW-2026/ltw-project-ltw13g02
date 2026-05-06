<?php 
  declare(strict_types=1);
?>

<?php function drawTrainerIntro(string $firstname, string $lastname, string $bio) { ?>
  <div id="trainer-profile" class="intro">
    <section class="container">
      <img src="https://picsum.photos/500/700" alt="Trainer">
      <div>
        <h1><?= $firstname ?> <b><?= $lastname ?></b></h1>
        <h2>Biography</h2>
        <p><?= $bio ?></p>
        <a class="btn medium blue" href="book.html">BOOK A SESSION WITH <?= $firstname ?> <?= $lastname ?></a>
      </div>
    </section>
  </div>
<?php } ?>

<?php function drawSpecialties(array $specialties) { ?>
  <ul>
    <?php foreach ($specialties as $specialty) { ?>
      <li class="card">
        <header>
          <span class="material-symbols-outlined"><?= $specialty['icon'] ?></span>
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
      <li class="card"><h3><?= $certification ?></h3></li>
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

<?php function drawTrainerPage(string $firstname, string $lastname, string $bio, array $specialties, array $certifications, array $classes) { ?>
  <?php drawTrainerIntro($firstname, $lastname, $bio); ?>
  <?php drawTrainerInfoContainer($specialties, $certifications); ?>
  <?php drawTrainerClassesContainer($firstname, $classes); ?>
<?php } ?>