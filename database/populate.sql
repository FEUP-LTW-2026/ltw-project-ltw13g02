PRAGMA foreign_keys = ON;

-- Insert Users (Admins, Trainers, Members)
INSERT INTO User (id, first_name, last_name, username, email, password, is_active) VALUES 
(1, 'Admin', 'Root', 'admin', 'admin@gym.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1),
(2, 'John', 'Doe', 'johndoe', 'johndoe@email.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1),
(3, 'Jane', 'Smith', 'janesmith', 'janesmith@email.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1),
(4, 'Mark', 'Johnson', 'markj', 'markj@email.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1),
(5, 'Emily', 'Davis', 'emilyd', 'emilyd@email.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1),
(6, 'Chris', 'Trainer', 'christ', 'chris@gym.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1),
(7, 'Sarah', 'Trainer', 'saraht', 'sarah@gym.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1);

-- Insert Admin
INSERT INTO Admin (admin_id) VALUES (1);

-- Insert Members
INSERT INTO Member (id) VALUES 
(3),
(4),
(5);

-- Insert Trainers
INSERT INTO Trainer (trainer_id, bio) VALUES 
(6, 'Hi, I''m Chris Trainer, a certified personal trainer with over 8 years of experience helping clients reach their fitness goals. I specialize in strength training and functional fitness, focusing on building practical, sustainable habits that lead to long-term success.'),
(7, 'Hello, I''m Sarah Trainer, a passionate yoga instructor with a decade of experience in teaching various styles of yoga. My classes are designed to promote mindfulness, flexibility, and overall well-being, catering to practitioners of all levels. I believe in creating a supportive and inclusive environment where everyone can thrive on their fitness journey.');

-- Insert Certifications
INSERT INTO Certification (id, name) VALUES 
(1, 'Certified Personal Trainer'),
(2, 'Yoga Instructor Certification'),
(3, 'CrossFit Level 1');

-- Insert Specializations
INSERT INTO Specialization (id, name, description, logo) VALUES 
(1, 'Weight Loss', 'Focuses on high-intensity fat burning exercising.', 'monitor_weight'),
(2, 'Strength Training', 'Focuses on increasing muscular physical strength.', 'exercise'),
(3, 'Flexibility & Mobility', 'Focuses on increasing range of motion.', 'sports_gymnastics');

-- Connecting Trainers to Certifications
INSERT INTO TrainerCertification (trainer_id, certification_id) VALUES 
(6, 1),
(6, 3),
(7, 2);

-- Connecting Trainers to Specializations
INSERT INTO TrainerSpecialization (trainer_id, specialization_id) VALUES 
(6, 2),
(7, 3),
(7, 1);

-- Insert Class Types
INSERT INTO ClassType (id, trainer_id, name, description, capacity, start_time, end_time, weekday) VALUES 
(1, 6, 'Morning HIIT', 'High intensity interval training to start the day.', 20, '08:00', '09:00', 1),
(2, 7, 'Evening Yoga', 'Relaxing yoga session.', 15, '18:00', '19:00', 3),
(3, 6, 'CrossFit WOD', 'Intense Workout of the Day.', 10, '14:00', '15:00', 5);

-- Insert Classes
-- Start and end timestamps should follow 'YYYY-MM-DD HH:MM:SS'
INSERT INTO Class (id, trainer_id, class_type_id, start_timestamp, end_timestamp, status) VALUES 
(1, 6, 1, '2026-06-01 08:00:00', '2026-06-01 09:00:00', 'scheduled'),
(2, 7, 2, '2026-06-03 18:00:00', '2026-06-03 19:00:00', 'scheduled'),
(3, 6, 3, '2026-06-05 14:00:00', '2026-06-05 15:00:00', 'scheduled'),
(4, 7, 2, '2026-06-10 18:00:00', '2026-06-10 19:00:00', 'scheduled');

-- Insert Equipment
INSERT INTO Equipment (id, name, description, status) VALUES 
(1, 'Treadmill Pro', 'Cardio running machine.', 'available'),
(2, 'Treadmill Advanced', 'Cardio running machine.', 'available'),
(3, 'Bench Press Station', 'For chest workouts.', 'in use'),
(4, 'Dumbbell Set 10kg-30kg', 'Free weights.', 'available'),
(5, 'Yoga Mats Set', 'Comfortable mats for floor exercises.', 'available');

-- Insert Membership Tiers
INSERT INTO MembershipTier (id, name, description, monthly_fee, booking_window_days) VALUES 
(1, 'Basic', 'Full gym access; Access to locker rooms; 1 group class per week; Basic fitness assessment;1 day of booking window for classes', 19.99, 1),
(2, 'Pro', 'Everything in Standard; Unlimited group classes; Personalized workout plan; Nutrition guidance;Progress tracking every 4 weeks; 2 days of booking window for classes', 29.99, 2),
(3, 'Elite', 'Everything in Premium; 2 personal training sessions per month; Priority class booking; Access to premium recovery area; Exclusive VIP support;3 days of booking window for classes', 39.99, 3);

-- Insert Reviews
INSERT INTO Review (id, review_text, rating, review_timestamp) VALUES 
(1, 'Great and very relaxing class!', 5, '2026-05-11 10:00:00');

-- Insert Enrollments
INSERT INTO Enrollment (id, member_id, class_id, status, status_update_timestamp, review_id) VALUES 
(2, 3, 2, 'enrolled', '2026-05-21 11:00:00', NULL),
(3, 3, 3, 'enrolled', '2026-05-22 12:00:00', NULL);
