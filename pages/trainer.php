<?php
  declare(strict_types = 1);

  require_once (__DIR__ . '/../templates/common.php');

  drawHeader('Trainer Name', ['trainer.css']);
?>
      <div id="trainer-profile" class="intro">
        <section class="container">
          <img src="https://picsum.photos/500/700" alt="Trainer">
          <div>
            <h1>Alex <b>Rivera</b></h1>
            <p>⭐⭐⭐⭐⭐ 4.9 / 5 (127 reviews)</p>
            <h2>Biography</h2>
            <p>Hi, I&#39;m Alex Rivera, a certified personal trainer with over 8 years of experience helping clients reach their fitness goals. I specialize in strength training and functional fitness, focusing on building practical, sustainable habits that lead to long-term success.</p>
            <a class="btn medium blue" href="book.html">BOOK A SESSION WITH ALEX</a>
          </div>
        </section>
      </div>

      <div id="trainer-info">
        <div class="container">
          <article>
            <h2>Specialties</h2>
            <ul>
              <li class="card">
                <header>
                  <span class="material-symbols-outlined">exercise</span>
                  <h3>Strength Training</h3>
                </header>
                <p>Focused on building muscle, improving strength, and enhancing overall performance through structured and progressive training programs.</p>
              </li>
              <li class="card">
                <header>
                  <span class="material-symbols-outlined">monitor_weight</span>
                  <h3>Weight Loss</h3>
                </header>
                <p>Combining effective workouts with sustainable habits to help you lose weight, stay consistent, and maintain long-term results.</p>
              </li>
              <li class="card">
                <header>
                  <span class="material-symbols-outlined">personal_injury</span>
                  <h3>Injury Rehab & Prevention</h3>
                </header>
                <p>Designed to support recovery and reduce the risk of injury by improving mobility, stability, and proper movement patterns.</p>
              </li>
            </ul>
          </article>

          <article>
            <h2>Certifications</h2>
            <ul>
              <li class="card"><h3>NASM Certified Personal Trainer</h3></li>
              <li class="card"><h3>CrossFit Level 1 Trainer</h3></li>
            </ul>
          </article>
        </div>
      </div>

      <div>
        <section class="container">
          <p class="detail">WHERE CAN YOU FIND ME?</p>
          <h2>Classes with Alex</h2>

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
          </div>
        </section>
      </div>

      <div id="reviews">
        <section class="container">
          <h2>Reviews</h2>
          <p class="detail">LEAVE US YOUR FEEDBACK</p>
          <div id="reviews-list">
            <article class="card review">
              <h3>Maria Silva</h3>
              <p>Rating</p>
              <p>
                  Amazing trainer! Alex helped me lose 10kg and feel more confident than ever.
              </p>
            </article>
            <article class="card review">
              <h3>Joao Cunha</h3>
              <p>Rating</p>
              <p>
                Amazing trainer! Alex helped me lose 10kg and feel more confident than ever.
              </p>
            </article>
          </div>

          <form class="comment-form" action="#" method="post">
            <h3>Leave a comment</h3>
            
            <label>
              Name
              <input type="text" name="name" placeholder="Your name" required>
            </label>

            <label>
              Rating
              <select name="rating" required>
                <option value="" disabled selected>Select a rating</option>
                <option value="5">5 - Excellent</option>
                <option value="4">4 - Very Good</option>
                <option value="3">3 - Good</option>
                <option value="2">2 - Fair</option>
                <option value="1">1 - Poor</option>
              </select>
            </label>

            <label>
              Comment
              <textarea name="message" rows="4" placeholder="Share your experience" required></textarea>
            </label>
            
            <button class="btn medium wide white" type="submit">Submit Comment</button>
          </form>

        </section>
      </div>
      
<?php drawFooter(); ?>
