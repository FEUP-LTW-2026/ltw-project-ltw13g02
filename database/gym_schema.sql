DROP TABLE IF EXISTS Enrollment;
DROP TABLE IF EXISTS Review;
DROP TABLE IF EXISTS Class;
DROP TABLE IF EXISTS ClassType;
DROP TABLE IF EXISTS TrainerCertification;
DROP TABLE IF EXISTS TrainerSpecialization;
DROP TABLE IF EXISTS Certification;
DROP TABLE IF EXISTS Specialization;
DROP TABLE IF EXISTS Admin;
DROP TABLE IF EXISTS Trainer;
DROP TABLE IF EXISTS Member;
DROP TABLE IF EXISTS User;
DROP TABLE IF EXISTS Equipment;
DROP TABLE IF EXISTS Membership;
DROP TABLE IF EXISTS MembershipTier;
DROP TABLE IF EXISTS MembershipPayment;

PRAGMA foreign_keys = ON;

-- user table (contains common data between members, trainers, and admins)
CREATE TABLE User (
    id INTEGER NOT NULL,
    first_name TEXT NOT NULL,
    last_name TEXT NOT NULL,
    username TEXT NOT NULL,
    email TEXT NOT NULL,
    password TEXT NOT NULL,

    CONSTRAINT PK_User PRIMARY KEY (id),
    CONSTRAINT UQ_User_username UNIQUE (username),
    CONSTRAINT UQ_User_email UNIQUE (email)
);

-- member table (members will have to have membership)
CREATE TABLE Member (
    id INTEGER NOT NULL,
    is_active INTEGER NOT NULL DEFAULT 1,

    CONSTRAINT PK_Member PRIMARY KEY (id),
    CONSTRAINT FK_Member_User 
        FOREIGN KEY (id) 
        REFERENCES User(id)
        ON UPDATE CASCADE,
    CONSTRAINT InvalidActive CHECK (is_active IN (0, 1))
);

CREATE TABLE Trainer (
    trainer_id INTEGER NOT NULL,
    is_active INTEGER NOT NULL DEFAULT 1,

    CONSTRAINT PK_Trainer PRIMARY KEY (trainer_id),
    CONSTRAINT FK_Trainer_User 
        FOREIGN KEY (trainer_id) 
        REFERENCES User(id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE,
    CONSTRAINT InvalidActive CHECK (is_active IN (0, 1))
);

-- table created due to many-to-many relationship
CREATE TABLE TrainerSpecialization (
    trainer_id INTEGER NOT NULL,
    specialization_id INTEGER NOT NULL,

    CONSTRAINT PK_Trainer_Specialization PRIMARY KEY (trainer_id, specialization_id),
    CONSTRAINT FK_Trainer_Specialization_Trainer 
        FOREIGN KEY (trainer_id) 
        REFERENCES Trainer(trainer_id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE,
    CONSTRAINT FK_Trainer_Specialization_Specialization 
    FOREIGN KEY (specialization_id) 
        REFERENCES Specialization(id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE
);

-- table created due to many-to-many relationship
CREATE TABLE TrainerCertification (
    trainer_id INTEGER NOT NULL,
    certification_id INTEGER NOT NULL,

    CONSTRAINT PK_Trainer_Certification PRIMARY KEY (trainer_id, certification_id),
    CONSTRAINT FK_Trainer_Certification_Trainer 
        FOREIGN KEY (trainer_id) 
        REFERENCES Trainer(trainer_id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE,
    CONSTRAINT FK_Trainer_Certification_Certification 
        FOREIGN KEY (certification_id) 
        REFERENCES Certification(id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE
);

-- specialization and certification tables
CREATE TABLE Certification (
    id INTEGER NOT NULL,
    name TEXT NOT NULL,
    description TEXT NOT NULL,

    CONSTRAINT PK_Certification PRIMARY KEY (id)
);

CREATE TABLE Specialization (
    id INTEGER NOT NULL,
    name TEXT NOT NULL,
    description TEXT NOT NULL,

    CONSTRAINT PK_Specialization PRIMARY KEY (id)
);

-- admin (first user will be directly initialized inside the database)
CREATE TABLE Admin (
    admin_id INTEGER NOT NULL,

    CONSTRAINT PK_Admin PRIMARY KEY (admin_id),
    CONSTRAINT FK_Admin_User 
        FOREIGN KEY (admin_id) 
        REFERENCES User(id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE
);

CREATE TABLE ClassType (
    id INTEGER NOT NULL,
    name TEXT NOT NULL,
    description TEXT NOT NULL,
    capacity INTEGER NOT NULL,
    start_time TEXT NOT NULL,
    end_time TEXT NOT NULL,
    weekday INTEGER NOT NULL,
    -- add constraints and checks
    CONSTRAINT PK_ClassType PRIMARY KEY (id),
    CONSTRAINT ValidTimes CHECK (start_time < end_time),
    CONSTRAINT ValidWeekday CHECK (weekday >= 0 AND weekday <= 6),
    CONSTRAINT ValidCapacity CHECK (capacity > 0)
);

CREATE TABLE Class (
    id INTEGER NOT NULL, 
    trainer_id INTEGER NOT NULL, -- reference to the trainer that will be teaching this class
    class_type_id INTEGER NOT NULL, -- reference to the class type
    start_timestamp TEXT NOT NULL,
    end_timestamp TEXT NOT NULL,
    status TEXT NOT NULL,
    -- add constraints and checks
    CONSTRAINT PK_ClassSchedule PRIMARY KEY (id),
    CONSTRAINT FK_ClassSchedule_Trainer 
        FOREIGN KEY (trainer_id) 
        REFERENCES Trainer(trainer_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT FK_ClassSchedule_ClassType 
        FOREIGN KEY (class_type_id) 
        REFERENCES ClassType(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT ValidTimes CHECK (datetime(start_timestamp) < datetime(end_timestamp))
);

-- after the capacity is full all tuples should be inserted with status as waitlisted (should be checked in the php file)
-- After the class has ended, the status of all enrollments are changed to attended or missed(decided to do manual update, also update status timestamp)
-- its not possible in sqlite to do time based updates
CREATE TABLE Enrollment (
    id INTEGER NOT NULL,
    member_id INTEGER NOT NULL, -- reference to the member that is enrolling
    class_id INTEGER NOT NULL, -- reference to the class session that the member is enrolling
    status TEXT NOT NULL, -- current status of the enrollment (enrolled, cancelled, attended, waitlisted, missed)
    -- attended and missed are updated manually
    status_update_timestamp TEXT NOT NULL, -- timestamp of the last status update

    review_id INTEGER, -- can have an associated review_id
    -- add constraints and checks
    CONSTRAINT PK_Enrollment PRIMARY KEY (id),
    CONSTRAINT ValidStatus CHECK (status IN ('enrolled', 'cancelled', 'attended', 'waitlisted', 'missed')),
    CONSTRAINT FK_Enrollment_member 
        FOREIGN KEY (member_id) 
        REFERENCES Member(id)
        ON UPDATE CASCADE, -- if a member id is updated, the enrollment is updated as well
    CONSTRAINT FK_Enrollment_class
        FOREIGN KEY (class_id) 
        REFERENCES Class(id) 
        ON UPDATE CASCADE,
    CONSTRAINT FK_Enrollment_review 
        FOREIGN KEY (review_id) 
        REFERENCES Review(id)
        ON DELETE SET NULL 
        ON UPDATE CASCADE -- if a review id is updated, the enrollment is updated as well
);

-- Only allows a single review, considering that the status of the enrollment is attended (trigger)
-- Logic can be checked inside the php file by querying the Enrollment class or using a trigger upon insertion
CREATE TABLE Review (
    id INTEGER NOT NULL,
    review_text TEXT, -- allows only rating review (without text)?
    rating INTEGER NOT NULL,
    review_timestamp TEXT NOT NULL,
    -- add constraints and checks
    CONSTRAINT PK_Review PRIMARY KEY (id),
    CONSTRAINT ValidRating CHECK (rating >= 1 AND rating <= 5)
);

CREATE TABLE Equipment (
    id INTEGER NOT NULL,
    name TEXT NOT NULL,
    description TEXT NOT NULL,
    status TEXT NOT NULL, -- status of the equipment (available, in use, maintenance)

    CONSTRAINT PK_Equipment PRIMARY KEY (id),
    CONSTRAINT ValidStatus CHECK (status IN ('available', 'in use', 'maintenance'))
);

-- guarantee that when a membership tier has its price updated, only new memberships will be affected, current ones will be charged
-- the price before. Only when that user has to pay next months, the new price will be applied
-- the moment a membership payment is created, that amount is fixed and set by checking the monthly fee of the current membership tier, so already
-- valid just needs to be implemented in php

-- according to the current membership tier, the user can book classes on advance (through the booking_window_days), according to their membership tier
CREATE TABLE MembershipTier(
    id INTEGER NOT NULL,
    name TEXT NOT NULL,
    description TEXT NOT NULL,
    monthly_fee REAL NOT NULL,
    booking_window_days INTEGER NOT NULL, -- number of days in advance that the user can book a class session (0 means same day)
    
    CONSTRAINT PK_MembershipTier PRIMARY KEY (id),
    CONSTRAINT ValidMonthlyFee CHECK (monthly_fee > 0),
    CONSTRAINT ValidBookingWindow CHECK (booking_window_days > 0)
);

-- membership start_date is one day after the payment due date
-- membership end_date is one month after the start date
-- when end_date has passed from an active membership, the status is updated to expired  and update the next one to active (to be done in php)
CREATE TABLE Membership(
    id INTEGER NOT NULL,
    member_id INTEGER NOT NULL,
    start_date TEXT NOT NULL,
    end_date TEXT NOT NULL,
    status TEXT NOT NULL DEFAULT 'pending', -- status of membership (active, planned, pending, expired, suspended, cancelled_by_user, cancelled);
    membership_tier_id INTEGER NOT NULL, -- FK
    CONSTRAINT PK_Membership PRIMARY KEY (id),
    CONSTRAINT FK_Membership_Member
        FOREIGN KEY (member_id)
        REFERENCES Member(id)
        ON UPDATE CASCADE,
    CONSTRAINT FK_Membership_MembershipTier
        FOREIGN KEY (membership_tier_id)
        REFERENCES MembershipTier(id)
        ON UPDATE CASCADE,
    CONSTRAINT ValidStatus CHECK (status IN ('active', 'planned', 'pending', 'expired', 'suspended', 'cancelled_by_user', 'cancelled')),
    CONSTRAINT ValidDates CHECK (datetime(start_date) < datetime(end_date))
);

-- when payment becomes paid before due date, create a new tuple in membership 
-- if a membership already exists, only update the membership tier id and that membership 
CREATE TABLE MembershipPayment(
    id INTEGER NOT NULL,
    member_id INTEGER NOT NULL,
    membership_id INTEGER NOT NULL, -- FK
    payment_due_timestamp TEXT NOT NULL, -- this timestamp
    amount REAL NOT NULL,
    status TEXT NOT NULL, -- status of the payment (paid, failed, pending)
    CONSTRAINT PK_MembershipPayment PRIMARY KEY (id),
    CONSTRAINT FK_MembershipPayment_Membership
        FOREIGN KEY (membership_id)
        REFERENCES Membership(id)
        ON UPDATE CASCADE,
    CONSTRAINT FK_MembershipPayment_Member
        FOREIGN KEY (member_id)
        REFERENCES Member(id)
        ON UPDATE CASCADE,
    CONSTRAINT ValidStatus CHECK (status IN ('paid', 'failed', 'pending')),
    CONSTRAINT ValidAmount CHECK (amount >= 0)
);

-- Create triggers for when some member cancels their enrollment (upon tuple deletion)
-- the oldest timestamp with status as "waitlisted" will be updated to "enrolled" 
-- (if there are any waitlisted members for that class session)
CREATE TRIGGER UpdateWaitlistStatusAfterCancelledEnrollment
AFTER UPDATE ON Enrollment
FOR EACH ROW
WHEN NEW.status = 'cancelled' AND OLD.status = 'enrolled'
BEGIN
    UPDATE Enrollment
    SET status = 'enrolled', status_update_timestamp = datetime('now')
    WHERE class_id = NEW.class_id AND status = 'waitlisted' AND status_update_timestamp = (
        SELECT MIN(status_update_timestamp)
        FROM Enrollment
        WHERE class_id = NEW.class_id AND status = 'waitlisted'
    );
END;

-- when a membership is cancelled (to be done in php)
-- if the user has a membership with future months not paid, the access is maintained for paid months but the remaining are cancelled (pending -> cancelled)
-- if the user has a membership with future months paid, the access is maintained for paid months (nothing really happens)

-- when a payment is missed, all membership tuples that are pending 
-- have their status changed to suspended and the user is not a member until the value + fine (increases by day)
-- should exist a limit where the user is not allowed to pay anymore and the membership is cancelled and the user is deactivated until the amount is paid
-- if the user eventually pays before the limit due date, the membership is active again

CREATE TRIGGER SuspendMembershipAfterFailedPayment
AFTER UPDATE ON MembershipPayment
FOR EACH ROW
WHEN NEW.status = 'failed' AND (OLD.status = 'pending')
BEGIN
    -- Change the membership status to suspended
    UPDATE Membership
    SET status = 'suspended'
    WHERE id = NEW.membership_id;
END;

-- When the user clicks to subscribe, two tuples are created: 
-- one inside membership and other inside membership payment (to be done as a transaction inside the php file)
-- when a payment is updated to paid before the due date:
-- if the membership is pending and the start date is before or equal to the current date, the membership becomes active
-- if the membership is pending and the start date is after the current date, the membership becomes planned (the user has paid but the membership did not start yet)
CREATE TRIGGER ActivateMembershipAfterPayment
AFTER UPDATE ON MembershipPayment
FOR EACH ROW
WHEN NEW.status = 'paid' AND OLD.status = 'pending' AND datetime('now') <= datetime(NEW.payment_due_timestamp)
BEGIN
    UPDATE Membership
    SET status = CASE 
        WHEN datetime(start_date) <= datetime('now') THEN 'active'
            ELSE 'planned'
        END
    WHERE id = NEW.membership_id AND status = 'pending';
END;

CREATE TRIGGER ActivateMembershipAfterLatePayment
AFTER UPDATE ON MembershipPayment
FOR EACH ROW
WHEN NEW.status = 'paid' AND OLD.status = 'failed'
BEGIN
    UPDATE Membership
    SET status = CASE 
        WHEN datetime(start_date) <= datetime('now') THEN 'active'
        ELSE 'planned'
    END
    WHERE id = NEW.membership_id AND status = 'suspended';
END;

-- user can pay as much months as he wants in advance
-- upgrade tier in current month: user is asked whether he wants to upgrade immediately. If so he pays the difference between the two tiers
-- also, if the user has more than 1 month paid in advance, ask if he wants to make next memberships with the new tier
-- ask if the user only wants to upgrade the tier for the next months and keep the current month with the old tier 

CREATE TRIGGER DeactivateMemberWhenNoActiveMemberships
AFTER UPDATE ON Membership
FOR EACH ROW
WHEN NEW.status IN ('expired', 'cancelled', 'cancelled_by_user')
BEGIN
    UPDATE Member
    SET is_active = 0
    WHERE id = NEW.member_id
    AND NOT EXISTS (
        SELECT 1 FROM Membership
        WHERE member_id = NEW.member_id 
        AND status IN ('active', 'planned')
        AND datetime(start_date) == datetime(NEW.end_date)
    );
END;

-- Reactivate member when a membership becomes active (if they were deactivated)
-- will also run even if the member is not deactivated
CREATE TRIGGER ReactivateMemberOnActiveMembership
AFTER UPDATE ON Membership
FOR EACH ROW
WHEN NEW.status = 'active'
BEGIN
    UPDATE Member
    SET is_active = 1
    WHERE id = NEW.member_id;
END;

-- OTHER IDEA FOR SAME MONTH UPGRADE
-- that will result in a new payment with a limit date of that same day
-- Update on a single month
-- maths: 20 / 30 = 0.66 (price by day)
-- maths: 30 / 30 = 1.0 (price by day)
-- remaining days of the month: 5
-- fairly would charge 5 * 1.0 - 5 * 0.66 = 1.7 for the price tier change for the remaining of that month
-- but since that requires multiple logic, updates and so on, charging full price is not that bad

-- If a user tries to pay for future months while having unpaid past months the payment is blocked
CREATE TRIGGER PreventNonConsecutivePayments
BEFORE UPDATE ON MembershipPayment
FOR EACH ROW
WHEN NEW.status = 'paid' AND OLD.status IN ('pending', 'failed')
BEGIN
    SELECT RAISE(ABORT, 'Cannot pay for future months while older payments are unpaid.')
    WHERE EXISTS (
        SELECT 1 
        FROM MembershipPayment
        WHERE member_id = NEW.member_id
        AND status IN ('pending', 'failed')
        AND datetime(payment_due_timestamp) < datetime(NEW.payment_due_timestamp)
    );
END;

-- o botão é desativado quando é clicado no frontend pelo que um double click não é possível

-- can only delete classes that did not occur yet
CREATE TRIGGER BlockDeleteOfPastClasses
BEFORE DELETE ON Class
FOR EACH ROW
WHEN OLD.start_timestamp <= datetime('now')
BEGIN
    SELECT RAISE(ABORT, 'Cannot delete classes that have already started or ended.'); 
    -- ABORT -> stops the statement and rolls back changes (all or nothing)
    -- can be catched as an exception using the pdo
END;

-- verificar possivelmente se a inserção de uma nova aula não interfere com as aulas já existentes desse treinador (trigger)
CREATE TRIGGER BlockInsertionOfOverlappingClasses
BEFORE INSERT ON Class
FOR EACH ROW
BEGIN
    SELECT RAISE(ABORT, 'Cannot insert overlapping classes for the same trainer.')
    WHERE EXISTS (
        SELECT 1
        FROM Class
        WHERE trainer_id = NEW.trainer_id
        AND (datetime(end_timestamp) > datetime(NEW.start_timestamp) AND datetime(start_timestamp) < datetime(NEW.end_timestamp))
    );
END;

-- only delete classes that did not occur yet (start_time > curr_time)
-- deleting every enrollment associated with this class
-- (enrolled, waitlisted, cancelled)
CREATE TRIGGER DeleteEnrollmentsAfterClassDeletion
AFTER DELETE ON Class
FOR EACH ROW
WHEN datetime(OLD.start_timestamp) > datetime('now')
BEGIN
    DELETE FROM Enrollment
    WHERE class_id = OLD.id AND status IN ('enrolled', 'waitlisted', 'cancelled');
END;

-- ON UPDATE TRIGGER: When a member is deactivated, 
-- all enrollments in future classes (start_time > current_time) 
-- are cancelled (enrollment, waitlisted)
CREATE TRIGGER EraseEnrollementRowsAfterMemberDeactivation
AFTER UPDATE ON Member
FOR EACH ROW
WHEN NEW.is_active = 0 AND OLD.is_active = 1
BEGIN
    UPDATE Enrollment
    SET status = 'cancelled'
    WHERE member_id = NEW.member_id AND status IN ('enrolled', 'waitlisted')
    AND class_id IN (
        SELECT id FROM Class
        WHERE start_timestamp > datetime('now')
    );
END;

-- if a member is deactivated his active membership is cancelled
CREATE TRIGGER CancelMembershipAfterMemberDeactivation
AFTER UPDATE ON Member
FOR EACH ROW
WHEN NEW.is_active = 0 AND OLD.is_active = 1
BEGIN
    UPDATE Membership
    SET status = 'cancelled'
    WHERE member_id = NEW.member_id AND status = 'active';
END;

-- ON UPDATE TRIGGER :When a trainer is deactivated, all classes that did not happen yet are deleted 
-- (by checking the current time and the start_time of all of his classes)
CREATE TRIGGER UpdateClassesAfterTrainerDeactivation
AFTER UPDATE ON Trainer
FOR EACH ROW
WHEN NEW.is_active = 0 AND OLD.is_active = 1
BEGIN
    DELETE FROM Class
    WHERE trainer_id = NEW.trainer_id AND start_timestamp > datetime('now');
END;
