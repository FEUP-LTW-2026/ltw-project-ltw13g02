<?php 
  declare(strict_types=1);

  require_once(__DIR__ . '/../database/trainer.class.php');
  require_once(__DIR__ . '/../database/authenticated_user.class.php');
  require_once(__DIR__ . '/../utils/helpers.php');
?>

<?php function drawTrainerIntro(Trainer $trainer, Session $session) { 
  $photoUrl = getProfileImageUrl($trainer->getId());
  ?>

  <div id="trainer-profile" class="intro">
    <section class="container">
      <img src="<?= htmlspecialchars($photoUrl) ?>" alt="trainer photo">
      <div>
        <h1><?= $trainer->getFirstName() ?> <b><?= $trainer->getLastName() ?></b></h1>
        <h2>Biography</h2>
        <p><?= $trainer->bio ?></p>
        <?php if ($trainer->getId() === $session->getId()) { ?>
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
          <span class="symbol"><?= $specialization['logo'] ?? 'exercise' ?></span>
          <h3><?= $specialization['name'] ?></h3>

          <?php if ($session->getId() === $trainerId) { ?>
            <form method="post" action="../actions/action_remove_specialization.php">
              <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
              <input type="hidden" name="specialization_id" value="<?= $specialization['id'] ?>">
              <button type="submit" class="btn warning remove-btn" title="Remove Specialization">
                <span class="symbol close">close</span>
              </button>
            </form>
          <?php } ?>
        </header>
        <p><?= $specialization['description'] ?></p>
      </li>
    <?php } ?>
  </ul>

  <?php if ($session->getId() === $trainerId) { ?>
    <form id="add-specialization" method="post" action="../actions/action_add_specialization.php">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">

      <select name="specialization_id">
        <option value="" disabled selected>Select a specialization...</option>

        <?php foreach (remainingSpecializations($allSpecializations, $specializations) as $spec) { ?>
          <option value="<?= $spec['id'] ?>"><?= htmlspecialchars($spec['name']) ?></option>
        <?php } ?>

      </select>
      <button type="submit" class="btn medium blue">Add specialization</button>
    </form>
<?php }
} ?>

<?php function drawCertifications(int $trainerId, array $certifications, array $allCertifications, Session $session) { ?>
  <ul>
    <?php foreach ($certifications as $certification) { ?>
      <li class="card">
        <h3><?= $certification['name'] ?></h3>
        
        <?php if ($session->getId() === $trainerId) { ?>
          <form method="post" action="../actions/action_remove_certification.php">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
            <input type="hidden" name="certification_id" value="<?= $certification['id'] ?>">
            <button type="submit" class="btn warning remove-btn" title="Remove Certification">
              <span class="symbol close">close</span>
            </button>
          </form>
        <?php } ?>
      </li>
    <?php } ?>
  </ul>

  <?php if ($session->getId() === $trainerId) { ?>
      <form id="add-certification" method="post" action="../actions/action_add_certification.php">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">

        <select name="certification_id">
          <option value="" disabled selected>Select a certification...</option>

          <?php foreach (remainingCertifications($allCertifications, $certifications) as $cert) { ?>
            <option value="<?= $cert['id'] ?>"><?= htmlspecialchars($cert['name']) ?></option>
          <?php } ?>

        </select>
        <button type="submit" class="btn medium blue">Add certification</button>
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


<?php function drawScheduledClasses(int $trainerId, array $scheduledClasses, Session $session) { ?>
  <ul class="scheduled-classes">
    <?php foreach ($scheduledClasses as $class) { ?>
      <li class="card">
        <?php drawScheduledClassInfo($class, $session);
        
        if ($session->getId() === $trainerId) { ?>
          <button class="btn medium blue see-enrolled" data-class-id="<?= $class->id ?>">See Enrolled Members</button>
        <?php } ?>
      </li>
    <?php } ?>
  </ul>
<?php } ?>

<?php function drawTrainerScheduledClassesContainer(int $trainerId, array $scheduledClasses, Session $session) { ?>
  <div>
    <section class="container">
      <h2>Scheduled Classes</h2>
      <?php drawScheduledClasses($trainerId, $scheduledClasses, $session) ?> 

      <section class="enrolled-members-container floating">
        <h3>Enrolled Members</h3>
          <div id="enrolled-members-list">
            <!-- Enrolled members will be loaded here via JS -->
          </div>
      </section>
    </section>
  </div>
<?php } ?>

<?php function drawTrainerPage(Trainer $trainer, array $allSpecializations, array $allCertifications, Session $session) {
  drawTrainerIntro($trainer, $session);
  drawTrainerInfoContainer($trainer->getId(), $trainer->specialitizations, $trainer->certifications, $allSpecializations, $allCertifications, $session);
  drawTrainerClassesContainer($trainer->getFirstName(), $trainer->classes);
  drawTrainerScheduledClassesContainer($trainer->getId(), $trainer->scheduledClasses, $session);
} ?>