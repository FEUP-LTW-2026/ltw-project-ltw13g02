<?php
  declare(strict_types = 1);
?>

<?php function drawLoginForm() { ?>
  <form class="form-login" action="../actions/action_login.php" method="post">
    <label>
      Email
      <input type="text" name="email" placeholder="Enter email" required>
    </label>
    <label>
      Password
      <input type="password" name="password" placeholder="Enter password" required>
    </label>
    
    <input class="btn large blue" type="submit" value="Login">
    <p>Don't have an account? Sign up <a href="../pages/signup.php">here</a>.</p>
  </form>
<?php } ?>

<?php function drawSignupForm() { ?>
  <form class="form-signup" method="post">
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
      Password
      <input type="password" name="password" placeholder="Enter password" required>
    </label>

    <label>
      Confirm password
      <input type="password" name="confirm-password" placeholder="Enter password" required>
    </label>
    
    <input class="btn large blue" type="submit" value="Register">
  
    <p>Already have an account? Login <a href="../pages/login.php">here</a>.</p>
  </form>
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