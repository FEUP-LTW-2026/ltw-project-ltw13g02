<?php
  declare(strict_types = 1);

  require_once (__DIR__ . '/../templates/common.php');

  drawHeader('Home', ['index.css']);
?>
    <div id="hero" class="intro">
      <section class="container">
        <div>
          <h1>TRAIN <b>SMARTER,</b> GET <b>STRONGER.</b></h1>
          <p>
          Build consistency with guided workouts, expert coaching, and a motivating community.
          Join gymRAT and start your next fitness milestone today.
          </p>
          <nav>
            <ul>
              <li><a class="btn large blue" href="#membership">START TODAY</a></li>
              <li><a class="btn large transparent-light" href="classes.html">EXPLORE CLASSES</a></li>
            </ul>
          </nav>
        </div>
        <img src="https://picsum.photos/800/600" alt="gymRAT Hero Image" class="hero-image" >
      </section>
    </div>
    <div id="membership">
      <section class="container">
        <p class="detail">OUR PLANS</p>
        <h2>MEMBERSHIP PLANS</h2>

        <div class="plans">
          <article class="card">
            <h3>Standard</h3>
            <p><span>20$</span>/4 weeks</p>
            <p>What's included:</p>
            <ul>
              <li>Full gym access</li>
              <li>Access to locker rooms</li>
              <li>1 group class per week</li>
              <li>Basic fitness assessment</li>
            </ul>
            <a class="btn large wide blue" href="#">Choose Standard</a>
          </article>

          <article class="card">
            <h3>Premium</h3>
            <p><span>30$</span>/4 weeks</p>
            <p>What's included:</p>
            <ul>
              <li>Everything in Standard</li>
              <li>Unlimited group classes</li>
              <li>Personalized workout plan</li>
              <li>Nutrition guidance</li>
              <li>Progress tracking every 4 weeks</li>
            </ul>
            <a class="btn large wide blue" href="#">Choose Premium</a>
          </article>

          <article class="card">
            <h3>VIP</h3>
            <p><span>40$</span>/4 weeks</p>
            <p>What's included:</p>
            <ul>
              <li>Everything in Premium</li>
              <li>2 personal training sessions per month</li>
              <li>Priority class booking</li>
              <li>Access to premium recovery area</li>
              <li>Exclusive VIP support</li>
            </ul>
            <a class="btn large wide blue" href="#">Choose VIP</a>
          </article>
        </div>
      </section>
    </div>
<?php drawFooter(); ?>
