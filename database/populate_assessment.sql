PRAGMA foreign_keys = ON;

-- Assessment-focused gym seed data for late May / June 2026.
-- The data is designed to satisfy populate.md while staying coherent with the schema and triggers.

INSERT INTO User (id, first_name, last_name, username, email, password, is_active, role_change_count) VALUES
(1, 'Sofia', 'Costa', 'sofiacosta', 'sofia@northpeakgym.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0),
(2, 'Miguel', 'Ferreira', 'mferreira', 'miguel@northpeakgym.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0),
(3, 'Ines', 'Cardoso', 'icardoso', 'ines@northpeakgym.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0),
(4, 'Bruno', 'Almeida', 'balmeida', 'bruno@northpeakgym.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0),
(5, 'Laura', 'Ribeiro', 'lribeiro', 'laura@northpeakgym.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0),
(6, 'Tiago', 'Santos', 'tsantos', 'tiago@northpeakgym.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0),
(7, 'Rafael', 'Santos', 'rsantos', 'rafael@email.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0),
(8, 'Marta', 'Silva', 'msilva', 'marta@email.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0),
(9, 'Ana', 'Costa', 'acosta', 'ana@email.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0),
(10, 'Joana', 'Pereira', 'jpereira', 'joana@email.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0),
(11, 'Beatriz', 'Moreira', 'bmoreira', 'beatriz@email.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0),
(12, 'Carlos', 'Gomes', 'cgomes', 'carlosg@gmail.com', '$2y$10$cv0GP4.poGgM1S3va/s1BeXEssxgZl4PtjJmoaUSKZhdLU6MjH94S', 1, 0);
INSERT INTO Admin (id) VALUES
(1);

INSERT INTO Member (id) VALUES
(7),
(8),
(9),
(10),
(11);

INSERT INTO Trainer (id, bio) VALUES
(2, 'Miguel coaches strength and HIIT sessions with a focus on technique, energy, and steady progression.'),
(3, 'Ines teaches yoga and pilates classes that build control, mobility, and recovery habits.'),
(4, 'Bruno leads cycling and endurance classes that improve cardio capacity without losing form.'),
(5, 'Laura focuses on functional movement and core control for day-to-day strength.'),
(6, 'Tiago blends mobility work with total-body conditioning for members who want durability and range of motion.');

INSERT INTO Certification (id, name) VALUES
(1, 'NASM Certified Personal Trainer'),
(2, 'Yoga Alliance RYT-200'),
(3, 'Spinning Instructor'),
(4, 'TRX Suspension Training'),
(5, 'Mobility Specialist');

INSERT INTO Specialization (id, name, description, logo) VALUES
(1, 'Strength Training', 'Progressive resistance work focused on muscle, force, and technique.', 'fitness_center'),
(2, 'Yoga and Pilates', 'Breath-led and controlled movement classes for balance and posture.', 'self_improvement'),
(3, 'Cycling', 'Indoor bike training with cadence, endurance, and interval work.', 'directions_bike'),
(4, 'Functional Fitness', 'Movement patterns that transfer to everyday strength and stability.', 'exercise'),
(5, 'Recovery and Mobility', 'Mobility, stretching, and recovery-focused training.', 'healing');

INSERT INTO TrainerCertification (trainer_id, certification_id) VALUES
(2, 1),
(3, 2),
(4, 3),
(5, 4),
(6, 5);

INSERT INTO TrainerSpecialization (trainer_id, specialization_id) VALUES
(2, 1),
(3, 2),
(4, 3),
(5, 4),
(6, 5);

INSERT INTO MembershipTier (id, name, monthly_fee, enrollment_window_days, max_enrollments, is_active) VALUES
(1, 'Starter', 24.90, 1, 8, 1),
(2, 'Plus', 34.90, 3, 16, 1),
(3, 'Premium', 49.90, 7, 30, 1);


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

INSERT INTO EquipmentType (id, name) VALUES
(1, 'Treadmill'),
(2, 'Exercise Bike'),
(3, 'Rowing Machine'),
(4, 'Yoga Mat');

INSERT INTO Equipment (id, name, status, equipment_type_id) VALUES
(1, 'TM-01', 'available', 1),
(2, 'TM-02', 'maintenance', 1),
(3, 'BI-01', 'available', 2),
(4, 'BI-02', 'available', 2),
(5, 'RW-01', 'available', 3),
(6, 'RW-02', 'available', 3),
(7, 'YM-01', 'available', 4),
(8, 'YM-02', 'available', 4);

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
(14, '19:00', '20:00');

INSERT INTO ClassType (id, trainer_id, name, description, capacity, start_time, end_time, weekday) VALUES
(1, 2, 'Early Burn HIIT', 'Fast-paced intervals that start the week with energy.', 2, '07:00', '08:00', 1),
(2, 2, 'Strength Lab', 'Coached barbell and dumbbell work with progressive overload.', 10, '18:00', '19:00', 4),
(3, 3, 'Sunrise Pilates', 'Controlled mat work focused on posture and core strength.', 10, '07:30', '08:30', 2),
(4, 3, 'Flow Yoga', 'A balanced vinyasa flow for flexibility and recovery.', 14, '19:00', '20:00', 5),
(5, 4, 'Ride and Burn', 'Spin intervals with sprint blocks and endurance climbs.', 16, '18:30', '19:30', 1),
(6, 4, 'Endurance Spin', 'Cadence-based cycling to build aerobic capacity.', 18, '07:00', '08:00', 3),
(7, 5, 'Functional Core', 'Functional movement and core stability for daily performance.', 12, '18:00', '19:00', 2),
(8, 5, 'Recovery Reset', 'Mobility and stretching to finish the week well.', 10, '10:00', '11:00', 6),
(9, 6, 'Total Body Circuit', 'A full-body strength and conditioning circuit.', 15, '09:00', '10:00', 5),
(10, 6, 'Mobility Flow', 'A low-impact mobility session to improve range of motion.', 12, '07:00', '08:00', 4);

INSERT INTO Class (id, class_type_id, start_timestamp, end_timestamp, status) VALUES
(1, 1, '2026-05-18 07:00:00', '2026-05-18 08:00:00', 'completed'),
(2, 1, '2026-06-01 07:00:00', '2026-06-01 08:00:00', 'scheduled'),
(3, 2, '2026-05-21 18:00:00', '2026-05-21 19:00:00', 'completed'),
(5, 3, '2026-05-19 07:30:00', '2026-05-19 08:30:00', 'completed'),
(7, 4, '2026-05-22 19:00:00', '2026-05-22 20:00:00', 'completed'),
(9, 5, '2026-05-25 18:30:00', '2026-05-25 19:30:00', 'completed'),
(19, 10, '2026-05-28 07:00:00', '2026-05-28 08:00:00', 'completed'),
(21, 9, '2026-05-27 09:00:00', '2026-05-27 10:00:00', 'scheduled'),
(22, 2, '2026-06-02 18:00:00', '2026-06-02 19:00:00', 'scheduled'),
(23, 3, '2026-06-03 07:30:00', '2026-06-03 08:30:00', 'scheduled'),
(24, 5, '2026-06-04 18:30:00', '2026-06-04 19:30:00', 'scheduled'),
(25, 6, '2026-06-05 07:00:00', '2026-06-05 08:00:00', 'scheduled'),
(26, 7, '2026-06-06 18:00:00', '2026-06-06 19:00:00', 'scheduled'),
(27, 8, '2026-06-07 10:00:00', '2026-06-07 11:00:00', 'scheduled'),
(28, 9, '2026-06-08 09:00:00', '2026-06-08 10:00:00', 'scheduled'),
(29, 10, '2026-06-09 07:00:00', '2026-06-09 08:00:00', 'scheduled');

INSERT INTO Enrollment (id, member_id, class_id, status, status_update_timestamp) VALUES
(1, 7, 1, 'attended', '2026-05-18 08:10:00'),
(2, 9, 3, 'attended', '2026-05-21 19:10:00'),
(3, 11, 5, 'attended', '2026-05-19 08:40:00'),
(4, 10, 7, 'attended', '2026-05-22 20:10:00'),
(5, 9, 9, 'attended', '2026-05-25 19:40:00'),
(6, 9, 2, 'enrolled', '2026-05-29 09:00:00'),
(7, 10, 2, 'enrolled', '2026-05-29 09:02:00'),
(8, 11, 2, 'waitlisted', '2026-05-29 09:10:00'),
(9, 7, 2, 'waitlisted', '2026-05-29 09:15:00'),
(10, 8, 19, 'enrolled', '2026-05-29 09:20:00'),
(11, 10, 19, 'enrolled', '2026-05-29 09:25:00'),
(12, 7, 22, 'enrolled', '2026-06-01 09:00:00'),
(13, 7, 23, 'enrolled', '2026-06-01 09:05:00'),
(14, 7, 24, 'enrolled', '2026-06-01 09:10:00'),
(15, 7, 25, 'enrolled', '2026-06-01 09:15:00'),
(16, 7, 26, 'enrolled', '2026-06-01 09:20:00'),
(17, 7, 27, 'enrolled', '2026-06-01 09:25:00'),
(18, 7, 28, 'enrolled', '2026-06-01 09:30:00'),
(19, 7, 29, 'enrolled', '2026-06-01 09:35:00');

INSERT INTO Review (id, review_text, rating, review_timestamp, class_type_id, member_id) VALUES
(1, 'Early Burn HIIT was intense but well paced and easy to follow.', 5, '2026-05-20 18:30:00', 1, 7),
(2, 'Strength Lab gave clear coaching and useful corrections.', 5, '2026-05-22 20:00:00', 2, 9),
(3, 'Sunrise Pilates helped a lot with posture and control.', 4, '2026-05-23 08:50:00', 3, 11),
(4, 'Flow Yoga was calm, focused, and a good reset after work.', 5, '2026-05-24 20:10:00', 4, 10),
(5, 'Ride and Burn had strong energy and a great playlist.', 5, '2026-05-26 19:50:00', 5, 9),
(6, 'Early Burn HIIT still felt efficient and motivating on the second pass.', 4, '2026-05-28 19:00:00', 1, 8);

INSERT INTO EquipmentBooking (id, member_id, equipment_id, timeslot_id, booking_date) VALUES
(1, 7, 1, 2, '2026-05-28'),
(2, 8, 7, 3, '2026-05-28'),
(3, 9, 3, 4, '2026-05-29'),
(4, 10, 5, 5, '2026-05-29'),
(5, 11, 6, 6, '2026-06-01'),
(6, 7, 4, 7, '2026-06-03'),
(7, 8, 8, 8, '2026-06-05'),
(8, 9, 1, 9, '2026-06-10');

INSERT INTO Membership (id, member_id, start_timestamp, end_timestamp, status, membership_tier_id, payment_due_timestamp, payment_amount, payment_status) VALUES
(1, 8, '2026-04-29 00:00:00', '2026-05-28 00:00:00', 'active', 1, '2026-04-29 00:00:00', 24.90, 'paid'),
(2, 8, '2026-05-29 00:00:00', '2026-06-29 00:00:00', 'planned', 2, '2026-05-29 00:00:00', 34.90, 'paid'),
(3, 7, '2026-06-02 00:00:00', '2026-07-02 00:00:00', 'active', 1, '2026-06-01 00:00:00', 24.90, 'paid');
