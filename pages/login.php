<?php 
declare(strict_types = 1);

require_once (__DIR__ . '/../templates/common.php');

drawHeader('Login');
?>
    <div class="intro">
      <section id="auth" class="container">
        <header>
          <h1><b>Login</b></h1>
          <h2>Welcome back, RAT.</h2>
        </header>
        
        <form class="form-login" method="get">
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
      </section>
    </div>
  
<?php drawFooter(); ?>
