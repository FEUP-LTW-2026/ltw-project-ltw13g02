<?php
declare(strict_types=1);

function drawTrainerCreation(Session $session) { ?>
  <article class="dashboard-section" id="trainer-creation">
    <header>
      <div class="section-header">
        <h2>Create Trainer Account</h2>
        <p>Use the form below to create new trainer accounts.</p>
      </div>
    </header>
    <form action="../actions/action_create_trainer.php" method="POST">
      <fieldset>
        <label>
          First Name
          <input type="text" name="first-name" placeholder="First Name" required>
        </label>
        <label>
          Last Name
          <input type="text" name="last-name" placeholder="Last Name" required>
        </label>
        <label>
          Username
          <input type="text" name="username" placeholder="Username" required>
        </label>
      </fieldset>
      <fieldset>
        <label>
          Email
          <input type="email" name="email" placeholder="Email" required>
        </label>
        <label>
          Password
          <input type="password" name="password" placeholder="Password" required>
        </label>
        <label>
          Confirm Password
          <input type="password" name="confirm-password" placeholder="Confirm Password" required>
        </label>
      </fieldset>
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
      <button type="submit" class="btn blue medium">Create Trainer</button>
    </form>
  </article>
<?php } ?>