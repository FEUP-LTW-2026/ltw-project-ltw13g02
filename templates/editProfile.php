<?php 
  declare(strict_types=1);

  require_once(__DIR__ . '/trainer.php');
  require_once(__DIR__ . '/../utils/helpers.php');
?>

<?php function drawEditTrainerInfo(Trainer $trainer, array $allSpecializations, array $allCertifications, Session $session) { ?>
  <div class="trainer-info" id="trainer-info">
    <p class="detail">Trainer Info</p>
    <form method="post" action="../actions/action_editTrainerProfile.php">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">

      <label>
        Biography
        <textarea name="biography" placeholder="New biography"><?= htmlspecialchars($trainer->bio) ?></textarea>
      </label>
      
      <div class="specializations">
        <article>
          <h2>Specializations</h2>

          <ul id="specializations-list">
            <?php foreach ($trainer->specialitizations as $spec) { ?>
              <li class="card">
                <header>
                  <button type="button" class="remove-btn" title="Remove">X</button>
                  <span class="symbol"><?= htmlspecialchars($spec['logo'] ?? 'exercise') ?></span>
                  <h3><?= htmlspecialchars($spec['name']) ?></h3>
                </header>
                <p><?= htmlspecialchars($spec['description']) ?></p>
                <input type="hidden" name="specializations[]" value="<?= (int) $spec['id'] ?>">
              </li>
            <?php } ?>
          </ul>
          <div class="add-section">
            <select id="add-specialization-select">
              <option value="" disabled selected>Select a specialization...</option>
              <?php foreach ($allSpecializations as $spec) { ?>
                <option value="<?= (int) $spec['id'] ?>" data-name="<?= htmlspecialchars($spec['name']) ?>" data-logo="<?= htmlspecialchars($spec['logo']) ?>" data-desc="<?= htmlspecialchars($spec['description']) ?>"><?= htmlspecialchars($spec['name']) ?></option>
              <?php } ?>
            </select>
            <button type="button" id="add-specialization-btn" class="btn medium blue">Add</button>
          </div>
        </article>

        <article>
          <h2>Certifications</h2>
          <ul id="certifications-list">
            <?php foreach ($trainer->certifications as $cert) { ?>
              <li class="card">
                <button type="button" class="remove-btn" title="Remove">X</button>
                <h3><?= htmlspecialchars($cert['name']) ?></h3>
                <input type="hidden" name="certifications[]" value="<?= (int) $cert['id'] ?>">
              </li>
            <?php } ?>
          </ul>
          <div class="add-section">
            <select id="add-certification-select">
              <option value="" disabled selected>Select a certification...</option>
              <?php foreach ($allCertifications as $cert) { ?>
                <option value="<?= (int) $cert['id'] ?>" data-name="<?= htmlspecialchars($cert['name']) ?>"><?= htmlspecialchars($cert['name']) ?></option>
              <?php } ?>
            </select>
            <button type="button" id="add-certification-btn" class="btn medium blue">Add</button>
          </div>
        </article>
      </div>
      
      <button class="btn medium blue save-trainer-btn" type="submit">Save Trainer Info</button>
    </form>
  </div>
<?php } ?>

<?php function drawProfileEditForm(int $userId, string $firstName, string $lastName, string $username, ?string $biography, Session $session) { 
  $photoUrl = getProfileImageUrl($userId);
  ?>
  <div class="intro">
    <div id="profile">
      
      <div class="profile-picture">
        <img src="<?= htmlspecialchars($photoUrl) ?>" alt="profile photo">

        <form method="post" action="../actions/action_uploadProfilePicture.php" enctype="multipart/form-data" id="upload-form">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
          <input type="file" name="profile-picture" id="image-input">
          <button type="button" class="btn medium blue" id="upload-btn">Change Profile Picture</button>
        </form>
      </div>

      <form method="post" action="../actions/action_editProfile.php">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
        <label>
          First Name
          <input type="text" name="first-name" value="<?= htmlspecialchars($firstName) ?>" placeholder="New first name">
        </label>

        <label>
          Last Name
          <input type="text" name="last-name" value="<?= htmlspecialchars($lastName) ?>" placeholder="New last name">
        </label>

        <label>
          Username
          <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" placeholder="New username">
        </label>

        <?php if ($session->isTrainer() && $biography !== null) { ?>
          <label>
            Biography
            <textarea name="biography" placeholder="New biography"><?= htmlspecialchars($biography) ?></textarea>
          </label>
        <?php } ?>

        <button class="btn medium blue" type="submit">Save changes</button>
      </form>
    
  
      <div class="change-password">
        <p class="detail">Change password</p>

        <form method="post" action="../actions/action_changePassword.php">
          <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
          <label>
            Current password
            <input type="password" name="current-password" placeholder="Enter current password" required>
          </label>

          <label>
            New password
            <input type="password" name="new-password" placeholder="Enter new password" required>
          </label>

          <label>
            Confirm new password
            <input type="password" name="confirm-password" placeholder="Confirm new password" required>
          </label>
          <button class="btn medium blue" type="submit">Change Password</button>
        </form>
      </div>
    </div>
  </div>

  <script src="../javascript/editProfile.js"></script>
<?php } ?>

<?php function drawEditProfilePage(int $userId, string $firstName, string $lastName, string $username, ?string $biography, Session $session, ) {
  drawProfileEditForm($userId, $firstName, $lastName, $username, $biography, $session);
} ?>
