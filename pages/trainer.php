<?php
  declare(strict_types = 1);

  require_once (__DIR__ . '/../templates/common.php');
  require_once (__DIR__ . '/../templates/trainer.php');

  // Dummy data for trainer
  $firstname = 'Alex';
  $lastname = 'Rivera';
  $bio = 'Hi, I&#39;m Alex Rivera, a certified personal trainer with over 8 years of experience helping clients reach their fitness goals. I specialize in strength training and functional fitness, focusing on building practical, sustainable habits that lead to long-term success.';
  $specialties = [
    ['name' => 'Strength Training', 'icon' => 'exercise', 'description' => 'Focused on building muscle, improving strength, and enhancing overall performance through structured and progressive training programs.'],
    ['name' => 'Weight Loss', 'icon' => 'monitor_weight', 'description' => 'Combining effective workouts with sustainable habits to help you lose weight, stay consistent, and maintain long-term results.'],
    ['name' => 'Injury Rehab & Prevention', 'icon' => 'personal_injury', 'description' => 'Designed to support recovery and reduce the risk of injury by improving mobility, stability, and proper movement patterns.']
  ];
  $certifications = ['NASM Certified Personal Trainer', 'CrossFit Level 1 Trainer'];
  $classes = [
    ['name' => 'Strength Training', 'schedule' => 'Monday, Wednesday, Friday - 7:00 PM', 'trainer' => 'Alex Rivera'],
    ['name' => 'Weight Loss Bootcamp', 'schedule' => 'Tuesday, Thursday - 6:00 PM', 'trainer' => 'Alex Rivera'],
    ['name' => 'Injury Rehab & Prevention', 'schedule' => 'Saturday - 10:00 AM', 'trainer' => 'Alex Rivera']
  ];


  drawHeader('Trainer Name', ['trainer.css']);

  drawTrainerPage($firstname, $lastname, $bio, $specialties, $certifications, $classes);
?>
      <!-- This should be in the class.php page -->
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
      
<?php 
  drawFooter(); 
?>
