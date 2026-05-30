# ltw13g02

## First Delivery

* The three pages implemented were:
    - Home page (`index.html`): Displays the gym's slogan with a brief introduction and its membership plans, along with a navigation menu to access other pages.
    - Classes page (`classes.html`): Lists the available fitness classes, with filtering options, including their schedules, the trainers who lead them, the duration of the class and the number of enrolled members. Each class card includes a link to the trainer's profile page.
    - Trainer page (`trainer.html`): Profile page for a specific gym trainer, showcasing their biography, specializations, certifications, the classes they teach and their reviews from members.
      * This page is accessible from the classes page (`classes.html`) by clicking on the trainer's name in each class card.

## Features - Final Delivery

**All users:**
- [x] Register a new account.
- [x] Log in and out.
- [x] Edit their profile, including name, username, password, and profile photo.

**Members:**
- [x] Browse the schedule of available fitness classes, filtering by type, trainer, day, or time.
- [x] Enroll in and cancel enrollment from upcoming classes, subject to capacity limits.
- [x] View trainer profiles, including their specializations and the classes they teach.
- [x] Check the current availability of equipment in the main training area.
- [x] Leave ratings and reviews for classes they have attended.

**Trainers:**
- [x] Manage their public profile, including bio, specializations, and certifications.
- [x] View the roster of members enrolled in their classes.
- [x] Track and manage their assigned class schedule.

**Admins:**
- [x] Manage members and trainers (create, update, and deactivate accounts).
- [x] Manage the class catalog (create, edit, and remove classes) and assign trainers to them.
- [x] Manage equipment in the main training area (add, update availability status, and remove items).
- [x] Elevate a user to admin status.
- [x] Oversee and ensure the smooth operation of the entire system.

**Extra:**
- [x] Equipment booking: a member can book a certain equipment for chosen timeslot if it is not already booked by another member in that timeslot and if the member has not already booked another equipment for that timeslot.
- [x] Class waitlist: if a member tries to enroll in a class that is already at capacity, they can join a waitlist for that class. If a spot opens up, when another member cancels their enrollment in that class, the first member on the waitlist is automatically enrolled.
- [x] Membership plans: the gym offers different membership plans, that can be managed by admins. Each plan has a number of allowed class enrollments per membership period and a maximum previous time window for class enrollment.
- [x] Gym overview in admin dashboard: a overview of key metrics, such as
    - Monthly revenue by membership tier.
    - Active memberships by membership tier.
    - Equipments in maintenance.
    - Most booked equipment in the current month.
    - Best reviewed classes.

## Running

``` bash
    sudo apt install php-gd # required for profile picture upload
    sh reset.sh # resets the database, cron job timestamp and populates the db
    php -S localhost:9000
```

View website at http://localhost:9000/.


## Credentials

| Role   | Username | Password |
|--------|----------|----------|
| Admin  | sofiacosta | 123456|
| Trainer | mferreira | 123456| 
| Member | msilva | 123456 |
| User | cgomes | 123456 |