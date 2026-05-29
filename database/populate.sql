PRAGMA foreign_keys = ON;

-- Insert Users (Admins, Trainers, Members)
INSERT INTO User (id, first_name, last_name, username, email, password, is_active, role_change_count) VALUES 
(1, 'Admin', 'Root', 'admin', 'admin@gym.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0),
(2, 'John', 'Doe', 'johndoe', 'johndoe@email.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0),
(3, 'Jane', 'Smith', 'janesmith', 'janesmith@email.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0),
(4, 'Mark', 'Johnson', 'markj', 'markj@email.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0),
(5, 'Emily', 'Davis', 'emilyd', 'emilyd@email.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0),
(6, 'Chris', 'Trainer', 'christ', 'chris@gym.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0),
(7, 'Sarah', 'Trainer', 'saraht', 'sarah@gym.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0);

-- Insert Admin
INSERT INTO Admin (id) VALUES 
(1);

-- Insert Members
INSERT INTO Member (id) VALUES 
(3),
(4),
(5);

-- Insert Trainers
INSERT INTO Trainer (id, bio) VALUES 
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
(3, 6, 'CrossFit WOD', 'Intense Workout of the Day.', 10, '14:00', '15:00', 5),
(4, 7, 'Body Building', 'Strength training for muscle growth.', 2, '18:00', '19:00', 2);

-- Insert Classes
-- Start and end timestamps should follow 'YYYY-MM-DD HH:MM:SS'
INSERT INTO Class (id, class_type_id, start_timestamp, end_timestamp, status) VALUES 
(1, 1, '2026-06-01 08:00:00', '2026-06-01 09:00:00', 'scheduled'),
(2, 2, '2026-06-03 18:00:00', '2026-06-03 19:00:00', 'scheduled'),
(3, 3, '2026-06-05 14:00:00', '2026-06-05 15:00:00', 'scheduled'),
(4, 2, '2026-06-10 18:00:00', '2026-06-10 19:00:00', 'scheduled'),
(5, 4, '2026-06-09 18:00:00', '2026-06-09 19:00:00', 'scheduled');

INSERT INTO EquipmentType (id, name) VALUES 
(1, 'Treadmill'),
(2, 'Exercise Bike');

INSERT INTO Equipment (id, name, status, equipment_type_id) VALUES
(1, 'TM01', 'available', 1),
(2, 'EB01', 'available', 2),
(3, 'TM02', 'maintenance', 1),
(4, 'EB02', 'in use', 2);

INSERT INTO Timeslot (id, start_time, end_time) VALUES
(1, '22:00', '23:00'),
(3, '00:00', '01:00'),
(4, '01:00', '02:00'),
(5, '02:00', '03:00'),
(6, '03:00', '04:00'),
(7, '04:00', '05:00'),
(8, '05:00', '06:00'),
(9, '06:00', '07:00'),
(10, '07:00', '08:00'),
(11, '08:00', '09:00'),
(12, '09:00', '10:00');


INSERT INTO EquipmentBooking (id, equipment_id, member_id, booking_date, timeslot_id) VALUES
(1, 1, 3, '2026-05-27', 12),
(2, 1, 4, '2026-05-27', 6);

INSERT INTO MembershipTier (id, name, monthly_fee, booking_window_days, is_active) VALUES 
(1, 'Basic', 19.99, 1, 1),
(2, 'Pro', 29.99, 2, 1),
(3, 'Elite', 39.99, 3, 1);

INSERT INTO MembershipTierPerk (id, membership_tier_id, description) VALUES
(1, 1, 'Full gym access'),
(2, 1, 'Access to locker rooms'),
(3, 1, '1 group class per week'),
(4, 1, 'Basic fitness assessment'),
(5, 1, '1 day of booking window for classes'),
(6, 2, 'Unlimited group classes'),
(7, 2, 'Personalized workout plan'),
(8, 2, 'Nutrition guidance'),
(9, 2, 'Progress tracking every 4 weeks'),
(10, 2, '2 days of booking window for classes'),
(11, 3, '2 personal training sessions per month'),
(12, 3, 'Priority class booking'),
(13, 3, 'Access to premium recovery area'),
(14, 3, 'Exclusive VIP support'),
(15, 3, '3 days of booking window for classes');