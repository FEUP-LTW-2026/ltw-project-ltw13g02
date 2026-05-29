PRAGMA foreign_keys = ON;

-- Realistic gym seed data for June 2026

INSERT INTO User (id, first_name, last_name, username, email, password, is_active, role_change_count) VALUES
(1, 'Sofia', 'Costa', 'sofiacosta', 'sofia@northpeakgym.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0),
(2, 'Miguel', 'Ferreira', 'mferreira', 'miguel@northpeakgym.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0),
(3, 'Ines', 'Cardoso', 'icardoso', 'ines@northpeakgym.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0),
(4, 'Bruno', 'Almeida', 'balmeida', 'bruno@northpeakgym.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0),
(5, 'Laura', 'Ribeiro', 'lribeiro', 'laura@northpeakgym.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0),
(6, 'Rafael', 'Santos', 'rsantos', 'rafael@email.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0),
(7, 'Marta', 'Silva', 'msilva', 'marta@email.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0),
(8, 'Tiago', 'Lopes', 'tlopes', 'tiago@email.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0),
(9, 'Ana', 'Costa', 'acosta', 'ana@email.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0),
(10, 'Joana', 'Pereira', 'jpereira', 'joana@email.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0),
(11, 'Beatriz', 'Moreira', 'bmoreira', 'beatriz@email.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0),
(12, 'Diogo', 'Martins', 'dmartins', 'diogo@email.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0),
(13, 'Catarina', 'Sousa', 'csousa', 'catarina@email.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0),
(14, 'Ricardo', 'Gomes', 'rgomes', 'ricardo@email.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0),
(15, 'Rita', 'Fernandes', 'rfernandes', 'rita@email.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0),
(16, 'Hugo', 'Nunes', 'hnunes', 'hugo@email.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0);

INSERT INTO Admin (id) VALUES
(1);

INSERT INTO Member (id) VALUES
(6),
(7),
(8),
(9),
(10),
(11),
(12),
(13),
(14),
(15),
(16);

INSERT INTO Trainer (id, bio) VALUES
(2, 'Miguel is a strength and conditioning coach focused on high-energy sessions, progressive overload, and sustainable performance for busy members.'),
(3, 'Ines teaches yoga and pilates with an emphasis on posture, breath control, mobility, and recovery for all experience levels.'),
(4, 'Bruno combines cycling, HIIT, and endurance training to build cardio capacity without sacrificing technique.'),
(5, 'Laura leads functional training and recovery work, helping members move better, lift safely, and avoid burnout.');

INSERT INTO Certification (id, name) VALUES
(1, 'NASM Certified Personal Trainer'),
(2, 'CrossFit Level 1'),
(3, 'Yoga Alliance RYT-200'),
(4, 'Pilates Mat Instructor'),
(5, 'Spinning Instructor'),
(6, 'Precision Nutrition Level 1'),
(7, 'TRX Suspension Training'),
(8, 'Mobility Specialist');

INSERT INTO Specialization (id, name, description, logo) VALUES
(1, 'Strength Training', 'Progressive resistance work for building force, muscle, and confidence in the main lifting areas.', 'fitness_center'),
(2, 'HIIT', 'Short, intense intervals designed to improve conditioning and calorie burn.', 'bolt'),
(3, 'Yoga', 'Breath-led flow sessions focused on flexibility, balance, and control.', 'self_improvement'),
(4, 'Pilates', 'Controlled core work that builds stability, coordination, and posture.', 'accessibility_new'),
(5, 'Cycling', 'Indoor bike workouts for endurance, cadence, and lower-body power.', 'directions_bike'),
(6, 'Functional Fitness', 'Practical movement patterns that carry over to everyday strength and mobility.', 'exercise'),
(7, 'Recovery & Mobility', 'Mobility drills, stretching, and tissue care for faster recovery.', 'healing'),
(8, 'Weight Management', 'Cardio and nutrition-aware training to support healthy body composition goals.', 'monitor_weight');

INSERT INTO TrainerCertification (trainer_id, certification_id) VALUES
(2, 1),
(2, 2),
(2, 6),
(3, 3),
(3, 4),
(3, 8),
(4, 2),
(4, 5),
(4, 6),
(5, 1),
(5, 7),
(5, 8);

INSERT INTO TrainerSpecialization (trainer_id, specialization_id) VALUES
(2, 1),
(2, 2),
(2, 6),
(3, 3),
(3, 4),
(3, 7),
(4, 2),
(4, 5),
(4, 8),
(5, 1),
(5, 6),
(5, 7);

INSERT INTO ClassType (id, trainer_id, name, description, capacity, start_time, end_time, weekday) VALUES
(1, 2, 'Early Burn HIIT', 'Fast-paced interval work to kick off the week with cardio and bodyweight drills.', 18, '07:00', '08:00', 1),
(2, 2, 'Strength Lab', 'Barbell and dumbbell strength work with coached progression.', 14, '18:00', '19:15', 4),
(3, 3, 'Sunrise Pilates', 'Mat pilates focused on core control, posture, and breathing.', 10, '07:30', '08:30', 2),
(4, 3, 'Flow Yoga', 'A balanced vinyasa class for flexibility, calm, and balance.', 16, '19:00', '20:00', 5),
(5, 4, 'Ride & Burn', 'A high-intensity spin class with interval blocks and sprint finishers.', 20, '18:30', '19:30', 1),
(6, 4, 'Endurance Spin', 'Cadence-based cycling for aerobic capacity and lower-body stamina.', 18, '07:00', '08:00', 3),
(7, 5, 'Functional Core', 'Functional movement and core stability for everyday performance.', 14, '18:00', '19:00', 2),
(8, 5, 'Recovery Reset', 'Mobility, stretching, and recovery work to finish the week well.', 12, '10:00', '11:00', 6);

INSERT INTO Class (id, class_type_id, start_timestamp, end_timestamp, status) VALUES
(1, 1, '2026-04-01 07:00:00', '2026-04-01 08:00:00', 'scheduled'),
(2, 5, '2026-04-01 18:30:00', '2026-04-01 19:30:00', 'scheduled'),
(3, 3, '2026-06-02 07:30:00', '2026-06-02 08:30:00', 'scheduled'),
(4, 7, '2026-06-02 18:00:00', '2026-06-02 19:00:00', 'scheduled'),
(5, 6, '2026-06-03 07:00:00', '2026-06-03 08:00:00', 'scheduled'),
(6, 2, '2026-06-04 18:00:00', '2026-06-04 19:15:00', 'scheduled'),
(7, 4, '2026-06-05 19:00:00', '2026-06-05 20:00:00', 'scheduled'),
(8, 8, '2026-06-06 10:00:00', '2026-06-06 11:00:00', 'scheduled'),
(9, 1, '2026-06-08 07:00:00', '2026-06-08 08:00:00', 'scheduled'),
(10, 5, '2026-06-08 18:30:00', '2026-06-08 19:30:00', 'scheduled'),
(11, 3, '2026-06-09 07:30:00', '2026-06-09 08:30:00', 'scheduled'),
(12, 7, '2026-06-09 18:00:00', '2026-06-09 19:00:00', 'scheduled'),
(13, 6, '2026-06-10 07:00:00', '2026-06-10 08:00:00', 'scheduled'),
(14, 2, '2026-06-11 18:00:00', '2026-06-11 19:15:00', 'scheduled'),
(15, 4, '2026-06-12 19:00:00', '2026-06-12 20:00:00', 'scheduled'),
(16, 8, '2026-06-13 10:00:00', '2026-06-13 11:00:00', 'scheduled'),
(17, 1, '2026-06-15 07:00:00', '2026-06-15 08:00:00', 'scheduled'),
(18, 5, '2026-06-15 18:30:00', '2026-06-15 19:30:00', 'scheduled'),
(19, 3, '2026-06-16 07:30:00', '2026-06-16 08:30:00', 'scheduled'),
(20, 7, '2026-06-16 18:00:00', '2026-06-16 19:00:00', 'scheduled'),
(21, 6, '2026-06-17 07:00:00', '2026-06-17 08:00:00', 'scheduled'),
(22, 2, '2026-06-18 18:00:00', '2026-06-18 19:15:00', 'scheduled'),
(23, 4, '2026-06-19 19:00:00', '2026-06-19 20:00:00', 'scheduled'),
(24, 8, '2026-06-20 10:00:00', '2026-06-20 11:00:00', 'scheduled'),
(25, 1, '2026-06-22 07:00:00', '2026-06-22 08:00:00', 'scheduled'),
(26, 5, '2026-06-22 18:30:00', '2026-06-22 19:30:00', 'scheduled'),
(27, 3, '2026-06-23 07:30:00', '2026-06-23 08:30:00', 'scheduled'),
(28, 7, '2026-06-23 18:00:00', '2026-06-23 19:00:00', 'scheduled'),
(29, 6, '2026-06-24 07:00:00', '2026-06-24 08:00:00', 'scheduled'),
(30, 2, '2026-06-25 18:00:00', '2026-06-25 19:15:00', 'scheduled'),
(31, 4, '2026-06-26 19:00:00', '2026-06-26 20:00:00', 'scheduled'),
(32, 8, '2026-06-27 10:00:00', '2026-06-27 11:00:00', 'scheduled'),
(33, 1, '2026-06-29 07:00:00', '2026-06-29 08:00:00', 'scheduled'),
(34, 5, '2026-06-29 18:30:00', '2026-06-29 19:30:00', 'scheduled'),
(35, 3, '2026-06-30 07:30:00', '2026-06-30 08:30:00', 'scheduled'),
(36, 7, '2026-06-30 18:00:00', '2026-06-30 19:00:00', 'scheduled');

INSERT INTO EquipmentType (id, name) VALUES
(1, 'Treadmill'),
(2, 'Exercise Bike'),
(3, 'Rowing Machine'),
(4, 'Cable Machine'),
(5, 'Dumbbell Set'),
(6, 'Yoga Mat'),
(7, 'Foam Roller'),
(8, 'Kettlebell');

INSERT INTO Equipment (id, name, status, equipment_type_id) VALUES
(1, 'TM-01', 'available', 1),
(2, 'TM-02', 'maintenance', 1),
(3, 'TM-03', 'available', 1),
(4, 'BI-01', 'available', 2),
(5, 'BI-02', 'available', 2),
(6, 'RW-01', 'available', 3),
(7, 'CM-01', 'available', 4),
(8, 'CM-02', 'in use', 4),
(9, 'DB-01', 'available', 5),
(10, 'DB-02', 'available', 5),
(11, 'YM-01', 'available', 6),
(12, 'FR-01', 'available', 7),
(13, 'KB-01', 'available', 8),
(14, 'KB-02', 'available', 8);

INSERT INTO Timeslot (id, start_time, end_time) VALUES
(1, '06:00', '07:00'),
(2, '07:00', '08:00'),
(3, '08:00', '09:00'),
(4, '09:00', '10:00'),
(5, '10:00', '11:00'),
(6, '11:00', '12:00'),
(7, '12:00', '13:00'),
(8, '13:00', '14:00'),
(9, '14:00', '15:00'),
(10, '15:00', '16:00'),
(11, '16:00', '17:00'),
(12, '17:00', '18:00'),
(13, '18:00', '19:00'),
(14, '19:00', '20:00'),
(15, '20:00', '21:00'),
(16, '21:00', '22:00'),
(17, '22:00', '23:00');

INSERT INTO EquipmentBooking (id, member_id, equipment_id, timeslot_id, booking_date) VALUES
(1, 6, 1, 2, '2026-06-01'),
(2, 7, 6, 3, '2026-06-01'),
(3, 8, 9, 4, '2026-06-03'),
(4, 9, 4, 5, '2026-06-04'),
(5, 10, 7, 6, '2026-06-05'),
(6, 11, 10, 2, '2026-06-08'),
(7, 12, 11, 3, '2026-06-09'),
(8, 13, 12, 4, '2026-06-10'),
(9, 14, 13, 5, '2026-06-11'),
(10, 15, 3, 6, '2026-06-12'),
(11, 16, 5, 2, '2026-06-15'),
(12, 6, 6, 3, '2026-06-16'),
(13, 7, 9, 4, '2026-06-17'),
(14, 8, 10, 5, '2026-06-18'),
(15, 9, 11, 6, '2026-06-19'),
(16, 10, 1, 2, '2026-06-22'),
(17, 11, 4, 3, '2026-06-23'),
(18, 12, 6, 4, '2026-06-24'),
(19, 13, 7, 5, '2026-06-25'),
(20, 14, 13, 6, '2026-06-26'),
(21, 15, 14, 2, '2026-06-29'),
(22, 16, 3, 3, '2026-06-30');

INSERT INTO MembershipTier (id, name, monthly_fee, booking_window_days, is_active) VALUES
(1, 'Starter', 24.90, 1, 1),
(2, 'Plus', 34.90, 3, 1),
(3, 'Premium', 49.90, 7, 1);

INSERT INTO MembershipTierPerk (id, membership_tier_id, description) VALUES
(1, 1, 'Full access to the main gym floor'),
(2, 1, 'Locker room and shower access'),
(3, 1, 'One group class per week'),
(4, 1, 'Standard booking window for classes'),
(5, 2, 'Unlimited group class access'),
(6, 2, 'Extended class booking window'),
(7, 2, 'Weekly progress check-in'),
(8, 2, 'Towel service'),
(9, 3, 'Priority class booking'),
(10, 3, 'Recovery area access'),
(11, 3, 'Monthly nutrition check-in'),
(12, 3, 'Guest pass each month');

INSERT INTO Membership (id, member_id, start_timestamp, end_timestamp, status, membership_tier_id, payment_due_timestamp, payment_amount, payment_status) VALUES
(3, 7, '2026-06-01 00:00:00', '2026-07-01 00:00:00', 'planned', 1, '2026-06-01 00:00:00', 24.90, 'paid'),
(4, 8, '2026-05-28 00:00:00', '2026-06-28 00:00:00', 'active', 3, '2026-05-28 00:00:00', 49.90, 'paid'),
(5, 9, '2026-06-03 00:00:00', '2026-07-03 00:00:00', 'planned', 1, '2026-06-03 00:00:00', 24.90, 'paid'),
(6, 10, '2026-05-20 00:00:00', '2026-06-20 00:00:00', 'active', 2, '2026-05-20 00:00:00', 34.90, 'paid'),
(7, 11, '2026-06-10 00:00:00', '2026-07-10 00:00:00', 'planned', 2, '2026-06-10 00:00:00', 34.90, 'paid'),
(8, 12, '2026-05-28 00:00:00', '2026-06-28 00:00:00', 'active', 1, '2026-05-28 00:00:00', 24.90, 'paid'),
(9, 13, '2026-06-15 00:00:00', '2026-07-15 00:00:00', 'planned', 3, '2026-06-15 00:00:00', 49.90, 'paid'),
(10, 14, '2026-05-28 00:00:00', '2026-06-28 00:00:00', 'active', 1, '2026-05-28 00:00:00', 24.90, 'paid'),
(11, 15, '2026-05-28 00:00:00', '2026-06-28 00:00:00', 'active', 2, '2026-05-28 00:00:00', 34.90, 'paid'),
(12, 16, '2026-05-28 00:00:00', '2026-06-28 00:00:00', 'active', 1, '2026-05-28 00:00:00', 24.90, 'paid');

INSERT INTO Enrollment (id, member_id, class_id, status, status_update_timestamp) VALUES
(1, 6, 1, 'attended', '2026-06-01 08:10:00'),
(2, 7, 1, 'attended', '2026-06-01 08:10:00'),
(3, 8, 1, 'enrolled', '2026-05-29 09:00:00'),
(4, 9, 1, 'waitlisted', '2026-05-29 09:15:00'),
(5, 10, 2, 'attended', '2026-06-01 19:35:00'),
(6, 11, 2, 'enrolled', '2026-05-29 09:30:00'),
(7, 12, 2, 'waitlisted', '2026-05-29 09:45:00'),
(8, 8, 3, 'attended', '2026-06-02 08:40:00'),
(9, 13, 3, 'enrolled', '2026-05-30 09:00:00'),
(10, 14, 3, 'enrolled', '2026-05-30 09:10:00'),
(11, 7, 4, 'attended', '2026-06-02 19:05:00'),
(12, 15, 4, 'enrolled', '2026-05-30 09:15:00'),
(13, 16, 4, 'waitlisted', '2026-05-30 09:30:00'),
(14, 6, 6, 'enrolled', '2026-06-01 18:00:00'),
(15, 9, 6, 'enrolled', '2026-06-01 18:05:00'),
(16, 10, 6, 'waitlisted', '2026-06-01 18:10:00'),
(17, 7, 7, 'enrolled', '2026-06-01 18:15:00'),
(18, 8, 7, 'enrolled', '2026-06-01 18:20:00'),
(19, 12, 8, 'enrolled', '2026-06-03 10:15:00'),
(20, 13, 8, 'enrolled', '2026-06-03 10:20:00'),
(21, 6, 9, 'enrolled', '2026-06-08 08:05:00'),
(22, 11, 9, 'enrolled', '2026-06-08 08:10:00'),
(23, 14, 10, 'enrolled', '2026-06-08 19:35:00'),
(24, 15, 10, 'waitlisted', '2026-06-08 19:40:00'),
(25, 7, 11, 'enrolled', '2026-06-09 08:10:00'),
(26, 8, 11, 'enrolled', '2026-06-09 08:15:00'),
(27, 10, 12, 'enrolled', '2026-06-09 19:10:00'),
(28, 16, 12, 'enrolled', '2026-06-09 19:15:00'),
(29, 6, 13, 'enrolled', '2026-06-10 08:05:00'),
(30, 9, 13, 'enrolled', '2026-06-10 08:10:00'),
(31, 11, 14, 'enrolled', '2026-06-11 19:10:00'),
(32, 12, 14, 'enrolled', '2026-06-11 19:15:00'),
(33, 13, 15, 'enrolled', '2026-06-12 20:05:00'),
(34, 14, 15, 'enrolled', '2026-06-12 20:10:00'),
(35, 15, 16, 'enrolled', '2026-06-13 11:10:00'),
(36, 16, 16, 'enrolled', '2026-06-13 11:15:00'),
(37, 6, 17, 'enrolled', '2026-06-15 08:00:00'),
(38, 7, 17, 'enrolled', '2026-06-15 08:05:00'),
(39, 8, 18, 'enrolled', '2026-06-15 19:40:00'),
(40, 9, 18, 'waitlisted', '2026-06-15 19:45:00'),
(41, 10, 19, 'attended', '2026-05-29 08:00:00'),
(42, 11, 19, 'attended', '2026-05-29 08:05:00'),
(43, 12, 20, 'attended', '2026-05-29 18:00:00'),
(44, 13, 20, 'attended', '2026-05-29 18:05:00'),
(45, 14, 21, 'attended', '2026-05-29 08:00:00'),
(46, 15, 21, 'attended', '2026-05-29 08:05:00'),
(47, 16, 22, 'attended', '2026-05-29 18:00:00'),
(48, 6, 22, 'attended', '2026-05-29 18:05:00');

INSERT INTO Review (id, review_text, rating, review_timestamp, class_type_id, member_id) VALUES
(1, 'The HIIT format is intense but well paced. I left tired in the best way.', 5, '2026-05-24 18:20:00', 1, 6),
(2, 'Strength Lab gave me clear coaching and a good mix of technique and effort.', 5, '2026-05-25 20:10:00', 2, 10),
(3, 'Sunrise Pilates helped with core control and posture without feeling repetitive.', 4, '2026-05-26 08:45:00', 3, 8),
(4, 'Flow Yoga was calm, focused, and easy to follow even after a long day.', 5, '2026-05-26 20:15:00', 4, 12),
(5, 'Ride & Burn had a strong playlist and the interval blocks flew by.', 5, '2026-05-27 19:40:00', 5, 14),
(6, 'Endurance Spin kept a steady pace and was great for building stamina.', 4, '2026-05-27 08:15:00', 6, 15),
(7, 'Functional Core balanced core work with useful movement patterns.', 5, '2026-05-28 19:05:00', 7, 7),
(8, 'Recovery Reset was exactly what I needed after a heavy training week.', 5, '2026-05-28 11:30:00', 8, 16);
