<?php
  declare(strict_types = 1);

  require_once (__DIR__ . '/../templates/common.php');

  drawHeader('Classes', ['classes.css']);
?>
    <div class="intro">
      <section class="container">
        <p class="detail">WHAT WE OFFER</p>
        <h1>Our <b>classes</b></h1>
        <p>40+ weekly sessions across strength, cardio, flexibility and recovery. Something for every level.</p>
      </section>
    </div>
    
    <div class="container">
      <section id="filters">

        <header>
          <h2>Search</h2>
          <p>Choose the day, time, class type and trainer that fit your routine.</p>
        </header>

        <form>
          <label> Day of week
            <select name="week-day">
              <option value="all" selected>All</option>
              <option value="monday">Monday</option>
              <option value="tuesday">Tuesday</option>
              <option value="wednesday">Wednesday</option>
              <option value="thursday">Thursday</option>
              <option value="friday">Friday</option>
              <option value="saturday">Saturday</option>
              <option value="sunday">Sunday</option>
            </select>
          </label>

          <label> Time of day
            <select name="time">
              <option value="all" selected>All</option>
              <option value="morning">Morning</option>
              <option value="afternoon">Afternoon</option>
              <option value="evening">Evening</option>
            </select>
          </label>

          <label> Class type
            <select name="type">
              <option value="all" selected>All</option>
              <option value="weightlifting">Weightlifting</option>
              <option value="yoga">Yoga</option>
              <option value="pilates">Pilates</option>
              <option value="cardio">Cardio</option>
              <option value="boxing">Boxing</option>
              <option value="crossfit">CrossFit</option>
            </select>
          </label>

          <label> Trainer
            <select name="trainer">
              <option value="all" selected>All Trainers</option>
              <option value="john-doe">John Doe</option>
              <option value="jane-smith">Jane Smith</option>
              <option value="michael-brown">Michael Brown</option>
              <option value="emily-jones">Emily Jones</option>
            </select>
          </label>
        </form>
      </section>

      <div class="card-grid class-list">
        <article class="card class-card">
          <header>
            <img src="https://picsum.photos/400/300" alt="Morning Yoga">
          </header>
          <div>
            <h3>Morning Yoga</h3> 
            <p>Monday, Wednesday, Friday</p>
            <p><time datetime="19:00">7:00 PM</time> - <time datetime="20:00">8:00 PM</time></p>
            <p>Trainer: <a class="trainer" href="trainer.php">Alex Rivera</a></p>
          </div>

          <footer>
              <div> 
              <p>
                <span class="material-symbols-outlined">timer</span>
                <time datetime="PT60M">60 min</time>
              </p>

              <p>
                <span class="material-symbols-outlined">person</span>
                <span>10/25</span>
              </p>
            </div>
            <a class="btn medium blue" href="class.html">Details</a>
          </footer>
        </article>

        <article class="card class-card">
          <header>
            <img src="https://picsum.photos/400/300" alt="Cardio Blast">
          </header>
          
          <div>
            <h3>Cardio Blast</h3>
            <p>Tuesday, Thursday</p>
            <p><time datetime="18:00">6:00 PM</time> - <time datetime="19:00">7:00 PM</time></p>
            <p>Trainer: <a class="trainer" href="trainer.php">Alex Rivera</a></p>
          </div>
          
          <footer>
            <div>
              <p>
                <span class="material-symbols-outlined">timer</span>
                <time datetime="PT60M">60 min</time>
              </p>

              <p>
                <span class="material-symbols-outlined">person</span>
                <span>10/25</span>
              </p>
            </div>
            <a class="btn medium blue" href="class.html">Details</a>
          </footer>
        </article>

        <article class="card class-card">
          <header>
            <img src="https://picsum.photos/400/300" alt="Weightlifting">
          </header>
          
          <div>
            <h3>Weightlifting</h3>
            <p>Monday, Wednesday</p>
            <p><time datetime="17:00">5:00 PM</time> - <time datetime="18:00">6:00 PM</time></p>
            <p>Trainer: <a class="trainer" href="trainer.php">John Doe</a></p>
          </div>
          
          <footer>
            <div>
              <p>
                <span class="material-symbols-outlined">timer</span>
                <time datetime="PT60M">60 min</time>
              </p>

              <p>
                <span class="material-symbols-outlined">person</span>
                <span>10/25</span>
              </p>
            </div>
            <a class="btn medium blue" href="class.html">Details</a>
          </footer>
        </article>

        <article class="card class-card">
          <header>
            <img src="https://picsum.photos/400/300" alt="Pilates">
          </header>
          
          <div>
            <h3>Pilates</h3>
            <p>Tuesday, Thursday</p>
            <p><time datetime="19:00">7:00 PM</time> - <time datetime="20:00">8:00 PM</time></p>
            <p>Trainer: <a class="trainer" href="trainer.php">Emily Jones</a></p>
          </div>
          
          <footer>
            <div>
              <p>
                <span class="material-symbols-outlined">timer</span>
                <time datetime="PT60M">60 min</time>
              </p>

              <p>
                <span class="material-symbols-outlined">person</span>
                <span>10/25</span>
              </p>
            </div>
            <a class="btn medium blue" href="class.html">Details</a>
          </footer>
        </article>

        <article class="card class-card">
          <header>
            <img src="https://picsum.photos/400/300" alt="CrossFit">
          </header>
          
          <div>
            <h3>CrossFit</h3>
            <p>Friday, Saturday</p>
            <p><time datetime="10:00">10:00 AM</time> - <time datetime="11:00">11:00 AM</time></p>
            <p>Trainer: <a class="trainer" href="trainer.php">John Doe</a></p>
          </div>
            
          <footer>
            <div>
              <p>
                <span class="material-symbols-outlined">timer</span>
                <time datetime="PT60M">60 min</time>
              </p>

              <p>
                <span class="material-symbols-outlined">person</span>
                <span>10/25</span>
              </p>
            </div>
            <a class="btn medium blue" href="class.html">Details</a>
          </footer>
        </article>

        <article class="card class-card">
          <header>
            <img src="https://picsum.photos/400/300" alt="Boxing">
          </header>
          
          <div>
            <h3>Boxing</h3>
            <p>Wednesday, Friday</p>
            <p><time datetime="18:00">6:00 PM</time> - <time datetime="19:00">7:00 PM</time></p>
            <p>Trainer: <a class="trainer" href="trainer.php">Michael Brown</a></p>
          </div>
          
          <footer>
            <div>
              <p>
                <span class="material-symbols-outlined">timer</span>
                <time datetime="PT60M">60 min</time>
              </p>

              <p>
                <span class="material-symbols-outlined">person</span>
                <span>10/25</span>
              </p>
            </div>
            <a class="btn medium blue" href="class.html">Details</a>
          </footer>
        </article>
      </div>
    </div>

<?php drawFooter(); ?>
