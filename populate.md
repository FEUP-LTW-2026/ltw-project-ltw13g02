This file will be used as a guideline to populate the database with initial data.
ALWAYS RESPECT CONSTRAINTS AND RELATIONS WHEN POPULATING THE DATABASE.
Generated data:
- 1 admin
- 5 users
- 5 trainers (each one with two class types taught)
- 5 trainer specializations (according with class types taught)
- 5 trainer certifications (according with class types taught)
- 10 class types (have variety: morning, afternoon, evening)
- 20 classes (past times, future times)
- some reviews
- Past, current and future enrollments with a variety of statuses (create case where allows to show waitlist working)
- Multiple equipment items
- Create some bookings for equipment items, with variety of dates and times
- Create some feasible and coherent timeslots for classes and equipment bookings.
- Deliberately create some cases where the lazy cron job will have to run to update the status of classes and enrollments: (can see that in the lazy_cron_job.class.php file)
- Allow room for we update the database by using the website itself: the database must be suitable for a college presential assessment by our teachers