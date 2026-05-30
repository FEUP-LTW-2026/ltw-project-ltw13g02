<?php
  declare(strict_types = 1);
?>

<?php function drawLoginForm() { ?>
  <form class="form-login" action="../actions/auth/action_login.php" method="post">
    <label>
      Email or username
      <input type="text" name="email-username" placeholder="Enter email or username" required>
    </label>
    <label>
      Password
      <input type="password" name="password" placeholder="Enter password" required>
    </label>
    
    <button class="btn large blue" type="submit">Login</button>
    <p>Don't have an account? Sign up <a href="../pages/signup.php">here</a>.</p>
  </form>
<?php } ?>

<?php function drawSignupForm() { ?>
  <form class="form-signup" action="../actions/auth/action_signup.php" method="post">
    <label>
      First name
      <input type="text" name="first-name" placeholder="Enter first name" required>
    </label>

    <label>
      Last name
      <input type="text" name="last-name" placeholder="Enter last name" required>
    </label>

    <label>
      Email
      <input type="text" name="email" placeholder="Enter email" required>
    </label>

    <label>
      Username
      <input type="text" name="username" placeholder="Enter username" required>
    </label>

    <label>
      Password
      <input type="password" id="password-input" name="password" placeholder="Enter password" required>
    </label>

    <ul class="password-requirements" aria-label="Password requirements">
      <li id="req-length">At least 8 characters</li>
      <li id="req-uppercase">One uppercase letter</li>
      <li id="req-lowercase">One lowercase letter</li>
      <li id="req-digit">One number</li>
      <li id="req-special">One special character (!@#$%^&*)</li>
    </ul>

    <label>
      Confirm password
      <input type="password" name="confirm-password" placeholder="Enter password" required>
    </label>

    <button class="btn large blue" type="submit">Register</button>

    <p>Already have an account? Login <a href="../pages/login.php">here</a>.</p>
  </form>
  <script src="../javascript/passwordRequirements.js" defer></script>
<?php } ?>

<?php function drawLoginPage() { ?>
  <div class="intro">
    <section id="auth" class="container">
      <header>
        <h1><b>Login</b></h1>
        <h2>Welcome back, RAT.</h2>
      </header>
      <?php drawLoginForm(); ?>
    </section>
  </div>
<?php } ?>

<?php function drawSignupPage() { ?>
  <div class="intro">
    <section id="auth" class="container">
      <header>
        <h1><b>Sign Up</b></h1>
        <h2>Become a RAT.</h2>
      </header>
      <?php drawSignupForm(); ?>
    </section>
  </div>
<?php } ?>