<?php
  declare(strict_types = 1);

  require_once (__DIR__ . '/../templates/common.php');
  
  drawHeader('Trainers', ['trainers.css']);
?>
    <div class="intro">
        <section class="container">
            <p class="detail">REAL EXPERTS. REAL RESULTS</p>
            <h1>Meet our <b>Trainers</b></h1>
            <p>Meet our certified coaches and find the right trainer for your goals.</p>
        </section>
    </div>
    
    <div class="container">
        <div class="card-grid">
            <a href="trainer.php">
                <article class="card trainer-card" style="background-image: url('https://picsum.photos/400/300')">
                    <h2>Alex Rivera</h2>
                    <p>Cardio and Yoga</p>
                </article>
            </a>

            <a href="trainer.php">
                <article class="card trainer-card" style="background-image: url('https://picsum.photos/400/300')">
                    <h2>John Doe</h2>
                    <p>Strength Training</p>
                </article>
            </a>

            <a href="trainer.php">
                <article class="card trainer-card" style="background-image: url('https://picsum.photos/400/300')">
                    <h2>Emily Jones</h2>
                    <p>Emily Jones</p>
                </article>
            </a>
            
            <a href="trainer.php">
                <article class="card trainer-card" style="background-image: url('https://picsum.photos/400/300')">
                    <h2>Michael Brown</h2>
                    <p>Boxing Coach</p>
                </article>
            </a>
        </div>
    </div>

<?php drawFooter(); ?>