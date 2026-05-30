<?php
declare(strict_types=1);

function drawCreateClassForm(Session $session, array $trainers) { ?>
  <dialog id="dialog-create-class">
    <div class="dialog-content">
      <h3>Add New Class</h3>
      <form action="../actions/action_create_class.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">

        <label>Class Name
          <input type="text" name="name" required>
        </label>

        <label>Description
          <input type="text" name="description" required>
        </label>

        <label>Trainer
          <select name="trainer_id" required>
            <option value="" disabled selected>Select a trainer</option>
            
            <?php foreach ($trainers as $trainer) { ?> 
              <option value="<?= (int) $trainer->getId() ?>"><?= htmlspecialchars($trainer->getFullName()) ?></option>
            <?php } ?>
          </select>
        </label>

        <label>Capacity
          <input type="number" name="capacity" min="1" required>
        </label>

        <label>Weekday
          <select name="weekday" required>
            <option value="" disabled selected>Select a day</option>
            <option value="1">Monday</option>
            <option value="2">Tuesday</option>
            <option value="3">Wednesday</option>
            <option value="4">Thursday</option>
            <option value="5">Friday</option>
            <option value="6">Saturday</option>
            <option value="0">Sunday</option>
          </select>
        </label>

        <label>Start Time
          <input type="time" name="start_time" required>
        </label>

        <label>End Time
          <input type="time" name="end_time" required>
        </label>

        <div class="image-upload-container">
          <img id="create-class-img-preview" src="../../img/classes/default.jpg" alt="Class image preview">
          <input type="file" name="class-image" id="create-class-image-input" accept="image/*">
          <button type="button" class="btn medium blue" id="create-class-upload-btn">Choose Image</button>
        </div>

        <div class="dialog-actions">
          <button type="submit" class="btn medium blue">Save</button>
          <button type="button" class="btn medium warning btn-close-dialog">Cancel</button>
        </div>
      </form>
    </div>
  </dialog>
<?php } ?>

<?php function drawEditClassForm(Session $session, array $trainers) { ?>
  <dialog id="dialog-edit-class">
    <section class="dialog-content">
      <h3>Edit Class</h3>
      <form action="../actions/action_edit_class.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
        <input type="hidden" name="class_id" id="edit-class-id">
        
        <label>Class Name
          <input type="text" name="name" id="edit-class-name" required>
        </label>

        <label>Description
          <input type="text" name="description" id="edit-class-description" required>
        </label>

        <label>Trainer
          <select name="trainer_id" id="edit-class-trainer" required>
            <option value="" disabled selected>Select a trainer</option>
            
            <?php foreach ($trainers as $trainer) { ?> 
              <option value="<?= (int) $trainer->getId() ?>"><?= htmlspecialchars($trainer->getFullName()) ?></option>
            <?php } ?>
          </select>
        </label>

        <label>Capacity
          <input type="number" name="capacity" id="edit-class-capacity" min="1" required>
        </label>

        <label>Weekday
          <select name="weekday" id="edit-class-weekday" required>
            <option value="" disabled selected>Select a day</option>
            <option value="Monday">Monday</option>
            <option value="Tuesday">Tuesday</option>
            <option value="Wednesday">Wednesday</option>
            <option value="Thursday">Thursday</option>
            <option value="Friday">Friday</option>
            <option value="Saturday">Saturday</option>
            <option value="Sunday">Sunday</option>
          </select>
        </label>

        <label>Start Time
          <input type="time" name="start_time" id="edit-class-start-time" required>
        </label>

        <label>End Time
          <input type="time" name="end_time" id="edit-class-end-time" required>
        </label>

        <div class="image-upload-container">
          <img id="edit-class-img-preview" src="../../img/classes/default.jpg" alt="Class image preview">
          <input type="file" name="class-image" id="edit-class-image-input" accept="image/*">
          <button type="button" class="btn medium blue" id="edit-class-upload-btn">Change Image</button>
        </div>
        
        <div class="dialog-actions">
          <button type="submit" class="btn medium blue">Update</button>
          <button type="button" class="btn medium warning btn-close-dialog">Cancel</button>
        </div>
      </form>
    </section>
  </dialog>
<?php } ?>

<?php function drawClassScheduleCard(string $weekDay, array $classes, Session $session) { ?>
  <section class="schedule-day">
    <h4><?= htmlspecialchars($weekDay) ?></h4>
    <article class="schedule-classes">

      <?php foreach ($classes as $weekdayClass) { ?>
        <article class="schedule-class-card">
          <strong><?= htmlspecialchars($weekdayClass->name) ?></strong>

          <span><?= htmlspecialchars($weekdayClass->startTime) ?> - <?= htmlspecialchars($weekdayClass->endTime) ?></span>
          <?php drawClassTypeCapacity($weekdayClass->capacity) ?>
          <div class="card-actions">
            <button class="btn blue btn-edit-class" 
              data-id="<?= htmlspecialchars((string) ($weekdayClass->id ?? '')) ?>"
              data-name="<?= htmlspecialchars($weekdayClass->name ?? '') ?>"
              data-description="<?= htmlspecialchars($weekdayClass->description ?? '') ?>"
              data-trainer-id="<?= htmlspecialchars((string) ($weekdayClass->trainerId ?? '')) ?>"
              data-capacity="<?= htmlspecialchars((string) ($weekdayClass->capacity ?? '')) ?>"
              data-weekday="<?= htmlspecialchars((string) ($weekdayClass->weekday ?? '')) ?>"
              data-start-time="<?= htmlspecialchars($weekdayClass->startTime ?? '') ?>"
              data-end-time="<?= htmlspecialchars($weekdayClass->endTime ?? '') ?>">
              Edit
            </button>

            <form action="../actions/action_delete_class.php" method="POST">
              <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
              <input type="hidden" name="class_id" value="<?= htmlspecialchars((string) ($weekdayClass->id ?? '')) ?>">
              <button type="submit" class="btn warning">Delete</button>
            </form>
            
          </div>
        </article>
      <?php } ?>

    </article>
  </section>
<?php } ?>

<?php function drawClassCatalog(array $classesByWeekday, array $trainers, Session $session) { ?>
  <article class="dashboard-section" id="class-catalog">
    <header>
      <div class="section-header">
        <h2>Class Catalog</h2>
        <p>Manage the class catalog and assign trainers.</p>
      </div>
      <button id="add-class-btn" class="btn transparent-dark medium">+ Add Class</button>
    </header>

    <div class="table-container">
      <div class="schedule-grid">
        <?php foreach ($classesByWeekday as $index => $classes) { 
          $weekDay = ClassType::$weekdays[$index];
          drawClassScheduleCard($weekDay, $classes, $session);
        } ?>
      </div>
    </div>

    <?php drawCreateClassForm($session, $trainers); ?>
    <?php drawEditClassForm($session, $trainers); ?>
  </article>
<?php } ?>