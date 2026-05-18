<?php 
    declare(strict_types = 1);

    require_once(__DIR__ . '/../database/class.class.php');
?>

<?php function drawClassIntro(GymClass $class) { ?>
  <div class="intro">
    <section class="container">
      <div id="class-hero">
        <p class="detail"><?= $class->weekday ?></p>
        <h1><?= $class->name ?></h1>
        <p class="rating">⭐⭐⭐⭐⭐ 4.9 / 5 (127 reviews)</p>
        <p class="time">
            <time datetime="19:00">7:00 PM</time> -
            <time datetime="20:00">8:00 PM</time>
        </p>
        <p class="trainer-info">
          Trainer:
          <a class="trainer" href="../pages/trainer.php"><?= $class->trainerId ?></a>
        </p>
        <div class="stats">
          <p>
            <span class="material-symbols-outlined">timer</span>
            <time datetime="PT60M">60 min</time>
          </p>

          <p>
            <span class="material-symbols-outlined">person</span>
            <span><?= count($class->enrolled) . '/' . $class->capacity ?></span>
          </p>
        </div>
        <a class="btn large blue" href="#enrollment">Join Class</a>
      </div>
      <img src="https://picsum.photos/800/600" alt="class image" class="hero-image" >
    </section>
  </div>
<?php } ?>

<?php function drawClassEnrollmentForm() { ?>
  <div id="enrollment">
    <section class="container">
      <h2>Enrollment</h2>
      <form>
        <label>
          Select a Date:
          <input type="date" name="date" required />
        </label>
      </form>
    </section>
  </div>
<?php } ?>

<?php function drawReview($name, $rating, $comment) { ?>
  <article class="card review">
    <h3><?= $name ?></h3>
    <p><?= $rating .  "/5" ?></p>
    <p><?= $comment ?></p>
  </article>
<?php } ?>

<?php function drawReviewForm() { ?>
  <form class="comment-form" action="#" method="post">
    <h3>Leave a comment</h3>
    <label>
      Name
      <input type="text" name="name" placeholder="Your name" required />
    </label>

    <label>
      Rating
      <select name="rating" required>
        <option value="" disabled selected>
            Select a rating
        </option>
        <option value="5">5 - Excellent</option>
        <option value="4">4 - Very Good</option>
        <option value="3">3 - Good</option>
        <option value="2">2 - Fair</option>
        <option value="1">1 - Poor</option>
      </select>
    </label>

    <label>
      Comment
      <textarea name="comment" placeholder="Your feedback" required></textarea>
    </label>

    <button class="btn medium blue" type="submit">Submit</button>
  </form>
<?php } ?>

<?php function drawReviewSection(array $reviews) { ?>
  <div id="reviews">
    <section class="container">
      <h2>Reviews</h2>
      <p class="detail">LEAVE US YOUR FEEDBACK</p>
      <div id="reviews-list">
        <?php foreach ($reviews as $review) {
          drawReview($review['name'], $review['rating'], $review['comment']);
        } ?>
      </div>
      <?php drawReviewForm(); ?>
    </section>
  </div>
<?php } ?>

<?php function drawClassPage(GymClass $class, array $reviews) {
    drawClassIntro($class);
    drawClassEnrollmentForm();
    drawReviewSection($reviews);
} ?>