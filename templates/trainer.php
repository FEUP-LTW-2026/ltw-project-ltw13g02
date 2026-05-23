<?php 
  declare(strict_types=1);

  require_once(__DIR__ . '/../database/trainer.class.php');
  require_once(__DIR__ . '/../database/authenticated_user.class.php');
?>

<?php function drawTrainerIntro(Trainer $trainer, Session $session) { ?>
  <div id="trainer-profile" class="intro">
    <section class="container">
      <img src="https://picsum.photos/500/700" alt="Trainer">
      <div>
        <h1><?= $trainer->getFirstName() ?> <b><?= $trainer->getLastName() ?></b></h1>
        <h2>Biography</h2>
        <p><?= $trainer->bio ?></p>
        <?php if ($trainer->getId() == $session->getId()) { ?>
          <a class="btn medium blue" href="../pages/editProfile.php">Edit Profile</a>
        <?php } else if ($session->isMember()){ ?>
          <a class="btn medium blue" href="#">Book A Session With <?= $trainer->getFullName() ?></a>
        <?php } ?>
      </div>
    </section>
  </div>
<?php } ?>

<?php
  function remainingSpecializations(array $allSpecializations, array $trainerSpecializations): array {
    $trainerSpecIds = array_map(fn($spec) => $spec['id'], $trainerSpecializations);
    return array_filter($allSpecializations, fn($spec) => !in_array($spec['id'], $trainerSpecIds));
  }

  function remainingCertifications(array $allCertifications, array $trainerCertifications): array {
    $trainerCertIds = array_map(fn($cert) => $cert['id'], $trainerCertifications);
    return array_filter($allCertifications, fn($cert) => !in_array($cert['id'], $trainerCertIds));
  }
?>

<?php function drawSpecializations(int $trainerId, array $specializations, array $allSpecializations, Session $session) { ?>
  <ul>
    <?php foreach ($specializations as $specialization) { ?>
      <li class="card">
        <header>
          <span class="material-symbols-outlined"><?= $specialization['logo'] ?? 'exercise' ?></span>
          <h3><?= $specialization['name'] ?></h3>

          <?php if ($session->getId() == $trainerId) { ?>
            <form method="post" action="../actions/action_remove_specialization.php">
              <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
              <input type="hidden" name="specialization_id" value="<?= $specialization['id'] ?>">
              <button type="submit" class="remove-btn" title="Remove Specialization">X</button>
            </form>
          <?php } ?>
        </header>
        <p><?= $specialization['description'] ?></p>
      </li>
    <?php } ?>
  </ul>

  <?php if ($session->getId() == $trainerId) { ?>
    <form method="post" action="../actions/action_add_specialization.php">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">

      <select name="specialization_id">
        <option value="" disabled selected>Add a specialization...</option>

        <?php foreach (remainingSpecializations($allSpecializations, $specializations) as $spec) { ?>
          <option value="<?= $spec['id'] ?>"><?= htmlspecialchars($spec['name']) ?></option>
        <?php } ?>

      </select>
      <button type="submit" class="btn medium blue">Add</button>
    </form>
<?php }
} ?>

<?php function drawCertifications(int $trainerId, array $certifications, array $allCertifications, Session $session) { ?>
  <ul>
    <?php foreach ($certifications as $certification) { ?>
      <li class="card">
        <h3><?= $certification['name'] ?></h3>
        
        <?php if ($session->getId() == $trainerId) { ?>
          <form method="post" action="../actions/action_remove_certification.php">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
            <input type="hidden" name="certification_id" value="<?= $certification['id'] ?>">
            <button type="submit" class="remove-btn" title="Remove Certification">X</button>
          </form>
        <?php } ?>
      </li>
    <?php } ?>
  </ul>

  <?php if ($session->getId() == $trainerId) { ?>
      <form method="post" action="../actions/action_add_certification.php">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">

        <select name="certification_id">
          <option value="" disabled selected>Add a certification...</option>

          <?php foreach (remainingCertifications($allCertifications, $certifications) as $cert) { ?>
            <option value="<?= $cert['id'] ?>"><?= htmlspecialchars($cert['name']) ?></option>
          <?php } ?>

        </select>
        <button type="submit" class="btn medium blue">Add</button>
      </form>
  <?php } 
}?>

<?php function drawTrainerInfoContainer(int $trainerId, array $specializations, array $certifications, array $allSpecializations, array $allCertifications, Session $session) { ?>
  <div id="trainer-info">
    <div class="container">
      <article>
        <h2>Specializations</h2>
        <?php drawSpecializations($trainerId, $specializations, $allSpecializations, $session) ?>
      </article>

      <article>
        <h2>Certifications</h2>
        <?php drawCertifications($trainerId, $certifications, $allCertifications, $session) ?>
      </article>
    </div>
  </div>
<?php } ?>

<?php function drawTrainerClassesContainer(string $firstname, array $classes) { ?>
  <div>
    <section class="container">
      <p class="detail">WHERE CAN YOU FIND ME?</p>
      <h2>Classes with <?= $firstname ?></h2>
      <?php drawClasses($classes) // TODO: place this function (currently in classes.php) inside common.php? ?> 
    </section>
  </div>
<?php } ?>

<?php function drawTrainerPage(Trainer $trainer, array $allSpecializations, array $allCertifications, Session $session) {
  drawTrainerIntro($trainer, $session);
  drawTrainerInfoContainer($trainer->getId(), $trainer->specialitizations, $trainer->certifications, $allSpecializations, $allCertifications, $session);
  drawTrainerClassesContainer($trainer->getFirstName(), $trainer->classes);
} ?>