<?php
  declare(strict_types=1);

  require_once (__DIR__ . '/../templates/common.php');

  drawHeader('Availability', ['availability.css','availability.css.']);
?>
    <div class="intro">
      <section class="container">
        <p class="detail">LIVE UPDATES</p>
        <h1>Check <b>availability</b></h1>
        <p>Find out which equipment is free or already reserved</p>
      </section>
    </div>
    <div class="container">
      <section>
        <h2>Gym equipment availability</h2>
        <ul class="gym-zones">
          <li>
            <a class="availability-data" href="#treadmill-zone">
              <span class="zone-title">Treadmills</span>
              <span class="availability available">Available</span>
              <span class="count">4/6 free</span>
            </a>
          </li>
          <li>
            <a class="availability-data" href="#bikes-zone">
              <span class="zone-title">Bikes</span>
              <span class="availability unavailable">Unavailable</span>
              <span class="count">0/4 free</span>
            </a>
          </li>
          <li>
            <a class="availability-data" href="#benches-zone">
              <span class="zone-title">Benches</span>
              <span class="availability limited">Limited Availability</span>
              <span class="count">2/6 free</span>
            </a>
          </li>
          <li>  
            <a class="availability-data" href="#functional-zone">
              <span class="zone-title">Functional Zone</span>
              <span class="availability available">Available</span>
              <span class="count">4/6 free</span>
            </a>
          </li>
        </ul>
      </section>
      <section id="treadmill-zone">
        <h2>Treadmill zone</h2>
        <ul class="equipment-list">
          <li>
            <article class="availability-data">
              <h3 class="treadmill">
                <span class="material-symbols-outlined">
                  sprint
                </span>
                TM01
              </h3>
              <span class="availability available">Available</span>
              <a href="#tm01-reservation" class="btn medium transparent-dark">View reservation slots</a>
            </article>
          </li>
          <li>
            <article class="availability-data">
              <h3 class="treadmill">
                <span class="material-symbols-outlined">
                  sprint
                </span>
                TM02
              </h3>
              <span class="availability available">Available</span>
              <a href="#tm01-reservation" class="btn medium transparent-dark">View reservation slots</a>
            </article>
          </li>
          <li>
            <article class="availability-data">
              <h3 class="treadmill">
                <span class="material-symbols-outlined">
                  sprint
                </span>
                TM03
              </h3>
              <span class="availability available">Available</span>
              <a href="#tm01-reservation" class="btn medium transparent-dark">View reservation slots</a>
            </article>
          </li>
        </ul>
        <section id="tm01-reservation" class="reservation-form">
          <h3>Reserve Treadmill 01</h3>
          <label>
            Select a date:
            <input type="date" id="date-picker" name="date-picker">
          </label>          
          <form>
            <fieldset>
              <legend>Morning</legend>
              <input type="radio" name="time-slot" id="s1" required>
              <label for="s1">8:00</label>

              <input type="radio" name="time-slot" id="s2" required>
              <label for="s2">8:30</label>

              <input type="radio" name="time-slot" id="s3" disabled>
              <label for="s3">9:00</label>
            </fieldset>
            <fieldset>
              <legend>Afternoon</legend>
              <input type="radio" name="time-slot" id="s4" disabled>
              <label for="s4">12:00</label>

              <input type="radio" name="time-slot" id="s5" required>
              <label for="s5">12:30</label>

              <input type="radio" name="time-slot" id="s6" disabled>
              <label for="s6">13:00</label>
            </fieldset>
            <fieldset>
              <legend>Evening</legend>
              <input type="radio" name="time-slot" id="s7" required>
              <label for="s7">18:00</label>

              <input type="radio" name="time-slot" id="s8" disabled>
              <label for="s8">18:30</label>

              <input type="radio" name="time-slot" id="s9" disabled>
              <label for="s9">19:00</label>
            </fieldset>
            <button type="submit" class="btn blue medium">Reserve</button>
          </form>
        </section>
      </section>
    </div>
<?php drawFooter(); ?>
