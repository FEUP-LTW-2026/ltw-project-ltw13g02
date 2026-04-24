# ltw13g02

## First Delivery

* The three pages implemented were:
    - Home page (`index.html`): Displays the gym's slogan with a brief introduction and its membership plans, along with a navigation menu to access other pages.
    - Classes page (`classes.html`): Lists the available fitness classes, with filtering options, including their schedules, the trainers who lead them, the duration of the class and the number of enrolled members. Each class card includes a link to the trainer's profile page.
    - Trainer page (`trainer.html`): Profile page for a specific gym trainer, showcasing their biography, specializations, certifications, the classes they teach and their reviews from members.
      * This page is accessible from the classes page (`classes.html`) by clicking on the trainer's name in each class card.

## Features

**All users:**
- [ ] Register a new account.
- [ ] Log in and out.
- [ ] Edit their profile, including name, username, password, and profile photo.

**Members:**
- [ ] Browse the schedule of available fitness classes, filtering by type, trainer, day, or time.
- [ ] Enroll in and cancel enrollment from upcoming classes, subject to capacity limits.
- [ ] View trainer profiles, including their specializations and the classes they teach.
- [ ] Check the current availability of equipment in the main training area.
- [ ] Leave ratings and reviews for classes they have attended.

**Trainers:**
- [ ] Manage their public profile, including bio, specializations, and certifications.
- [ ] View the roster of members enrolled in their classes.
- [ ] Track and manage their assigned class schedule.

**Admins:**
- [ ] Manage members and trainers (create, update, and deactivate accounts).
- [ ] Manage the class catalog (create, edit, and remove classes) and assign trainers to them.
- [ ] Manage equipment in the main training area (add, update availability status, and remove items).
- [ ] Elevate a user to admin status.
- [ ] Oversee and ensure the smooth operation of the entire system.

**Extra:**
- [ ] Something extra (e.g., personal training bookings, membership plans, waitlist, ...).

## Running

* Not yet working.

``` bash
    sqlite3 database/database.db < database/database.sql
    php -S localhost:9000
```

## Credentials

* Not existent yet.
