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
DROP TABLE IF EXISTS EquipmentType;
DROP TABLE IF EXISTS Timeslot;
DROP TABLE IF EXISTS EquipmentBooking;
DROP TABLE IF EXISTS MembershipTierPerk;

PRAGMA foreign_keys = ON;

-- user table (contains common data between members, trainers, and admins)
CREATE TABLE User (
    id INTEGER NOT NULL,
    first_name TEXT NOT NULL,
    last_name TEXT NOT NULL,
    username TEXT NOT NULL,
    email TEXT NOT NULL,
    password TEXT NOT NULL,
    is_active INTEGER NOT NULL DEFAULT 1,
    role_change_count INTEGER NOT NULL DEFAULT 0,

    CONSTRAINT PK_User PRIMARY KEY (id),
    CONSTRAINT UQ_User_username UNIQUE (username),
    CONSTRAINT UQ_User_email UNIQUE (email),
    CONSTRAINT ValidActive CHECK (is_active IN (0, 1))
);

-- member table (members will have to have membership)
CREATE TABLE Member (
    id INTEGER NOT NULL,

    CONSTRAINT PK_Member PRIMARY KEY (id),
    CONSTRAINT FK_Member_User
        FOREIGN KEY (id)
        REFERENCES User(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE Trainer (
    id INTEGER NOT NULL,
    bio STRING NOT NULL DEFAULT '',
    
    CONSTRAINT PK_Trainer PRIMARY KEY (id),
    CONSTRAINT FK_Trainer_User 
        FOREIGN KEY (id)
        REFERENCES User(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- table created due to many-to-many relationship
CREATE TABLE TrainerSpecialization (
    trainer_id INTEGER NOT NULL,
    specialization_id INTEGER NOT NULL,

    CONSTRAINT PK_Trainer_Specialization PRIMARY KEY (trainer_id, specialization_id),
    CONSTRAINT FK_Trainer_Specialization_Trainer 
        FOREIGN KEY (trainer_id) 
        REFERENCES Trainer(id) 
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
        REFERENCES Trainer(id) 
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

    CONSTRAINT PK_Certification PRIMARY KEY (id)
);

CREATE TABLE Specialization (
    id INTEGER NOT NULL,
    name TEXT NOT NULL,
    description TEXT NOT NULL,
    logo TEXT NOT NULL,

    CONSTRAINT PK_Specialization PRIMARY KEY (id)
);

-- admin (first user will be directly initialized inside the database)
CREATE TABLE Admin (
    id INTEGER NOT NULL,

    CONSTRAINT PK_Admin PRIMARY KEY (id),
    CONSTRAINT FK_Admin_User 
        FOREIGN KEY (id) 
        REFERENCES User(id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE
);

CREATE TABLE ClassType (
    id INTEGER NOT NULL,
    trainer_id INTEGER NOT NULL,
    name TEXT NOT NULL,
    description TEXT NOT NULL,
    capacity INTEGER NOT NULL,
    start_time TEXT NOT NULL,
    end_time TEXT NOT NULL,
    weekday INTEGER NOT NULL,
    -- add constraints and checks
    CONSTRAINT PK_ClassType PRIMARY KEY (id),
    CONSTRAINT FK_ClassType_Trainer 
        FOREIGN KEY (trainer_id) 
        REFERENCES Trainer(id) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE,
    CONSTRAINT ValidTimes CHECK (start_time < end_time),
    CONSTRAINT ValidWeekday CHECK (weekday >= 0 AND weekday <= 6),
    CONSTRAINT ValidCapacity CHECK (capacity > 0)
);


CREATE TABLE Class (
    id INTEGER NOT NULL, 
    class_type_id INTEGER NOT NULL, -- reference to the class type
    start_timestamp TEXT NOT NULL,
    end_timestamp TEXT NOT NULL,
    status TEXT NOT NULL,
    -- add constraints and checks
    CONSTRAINT PK_ClassSchedule PRIMARY KEY (id),
    CONSTRAINT FK_ClassSchedule_ClassType 
        FOREIGN KEY (class_type_id) 
        REFERENCES ClassType(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT ValidTimes CHECK (datetime(start_timestamp) < datetime(end_timestamp)),
    CONSTRAINT ValidStatus CHECK (status IN ('scheduled', 'completed'))
);

-- Using a trigger checks before if the user has attended that class_type 
CREATE TABLE Review (
    id INTEGER NOT NULL,
    review_text TEXT NOT NULL,   
    rating INTEGER NOT NULL,
    review_timestamp TEXT NOT NULL,
    class_type_id INTEGER NOT NULL,
    member_id INTEGER NOT NULL,
    -- add constraints and checks
    CONSTRAINT PK_Review PRIMARY KEY (id),
    CONSTRAINT ValidRating CHECK (rating >= 1 AND rating <= 5),
    CONSTRAINT FK_Review_ClassType
        FOREIGN KEY (class_type_id)
        REFERENCES ClassType(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT FK_Review_Member 
        FOREIGN KEY (member_id) 
        REFERENCES Member(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);


CREATE TABLE Enrollment (
    id INTEGER NOT NULL,
    member_id INTEGER NOT NULL, -- reference to the member that is enrolling
    class_id INTEGER NOT NULL, -- reference to the class session that the member is enrolling
    status TEXT NOT NULL, -- current status of the enrollment (enrolled, cancelled, attended, waitlisted, missed)
    -- attended and missed are updated manually
    status_update_timestamp TEXT NOT NULL, -- timestamp of the last status update
    -- add constraints and checks
    CONSTRAINT PK_Enrollment PRIMARY KEY (id),
    CONSTRAINT ValidStatus CHECK (status IN ('enrolled', 'cancelled', 'attended', 'waitlisted')),
    CONSTRAINT FK_Enrollment_member
        FOREIGN KEY (member_id)
        REFERENCES Member(id)
        ON UPDATE CASCADE -- if a member id is updated, the enrollment is updated as well
        ON DELETE CASCADE, -- if a member is deleted, all enrollments associated with that member are deleted as well
    CONSTRAINT FK_Enrollment_class
        FOREIGN KEY (class_id) 
        REFERENCES Class(id) 
        ON UPDATE CASCADE
        ON DELETE CASCADE -- if a class is deleted, all enrollments associated with that class are deleted as well
);

CREATE TABLE EquipmentType (
    id INTEGER NOT NULL,
    name TEXT NOT NULL,

    CONSTRAINT PK_EquipmentType PRIMARY KEY (id),
    CONSTRAINT UQ_EquipmentType_name UNIQUE (name)
);

CREATE TABLE Equipment (
    id INTEGER NOT NULL,
    name TEXT NOT NULL,
    status TEXT NOT NULL DEFAULT 'available', -- status of the equipment (available, in use, maintenance)
    equipment_type_id INTEGER NOT NULL,

    CONSTRAINT PK_Equipment PRIMARY KEY (id),
    CONSTRAINT FK_Equipment_EquipmentType 
        FOREIGN KEY (equipment_type_id) 
        REFERENCES EquipmentType(id) 
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT UQ_Equipment_name UNIQUE (name),
    CONSTRAINT ValidStatus CHECK (status IN ('available', 'in use', 'maintenance'))
);


CREATE TABLE Timeslot (
    id INTEGER NOT NULL,
    start_time TEXT NOT NULL,
    end_time TEXT NOT NULL,

    CONSTRAINT PK_Timeslot PRIMARY KEY (id),
    CONSTRAINT ValidTimes CHECK (start_time < end_time)
);


CREATE TABLE EquipmentBooking (
    id INTEGER NOT NULL,
    member_id INTEGER NOT NULL,
    equipment_id INTEGER NOT NULL,
    timeslot_id INTEGER NOT NULL,
    booking_date TEXT NOT NULL,
    CONSTRAINT PK_EquipmentBooking PRIMARY KEY (id),
    CONSTRAINT FK_EquipmentBooking_Member
        FOREIGN KEY (member_id)
        REFERENCES Member(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT FK_EquipmentBooking_Equipment
        FOREIGN KEY (equipment_id)
        REFERENCES Equipment(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT FK_EquipmentBooking_Timeslot
        FOREIGN KEY (timeslot_id)
        REFERENCES Timeslot(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

CREATE TRIGGER IF NOT EXISTS CleanupBookingsOnMaintenance
AFTER UPDATE OF status ON Equipment
FOR EACH ROW
WHEN NEW.status = 'maintenance' AND OLD.status != 'maintenance'
BEGIN
    DELETE FROM EquipmentBooking
    WHERE equipment_id = NEW.id
      AND booking_date = date('now', 'localtime')
      AND timeslot_id IN (
        SELECT id
        FROM Timeslot
        WHERE time(start_time) >= time('now', 'localtime')
      );
END;


-- according to the current membership tier, the user can book classes on advance (through the enrollment_window_days), according to their membership tier
CREATE TABLE MembershipTier(
    id INTEGER NOT NULL,
    name TEXT NOT NULL,
    monthly_fee REAL NOT NULL,
    enrollment_window_days INTEGER NOT NULL, -- number of days in advance that the user can book a class session (0 means same day)
    max_enrollments INTEGER NOT NULL, -- maximum number of class enrollments per membership period (4 weeks)
    is_active INTEGER NOT NULL DEFAULT 1, -- whether the tier is active and can be subscribed to by users
    
    CONSTRAINT PK_MembershipTier PRIMARY KEY (id),
    CONSTRAINT ValidMonthlyFee CHECK (monthly_fee > 0),
    CONSTRAINT ValidBookingWindow CHECK (enrollment_window_days > 0)
);

CREATE TABLE MembershipTierPerk (
    id INTEGER NOT NULL,
    membership_tier_id INTEGER NOT NULL,
    description TEXT NOT NULL,

    CONSTRAINT PK_MembershipTierPerk PRIMARY KEY (id),
    CONSTRAINT FK_MembershipTierPerk_MembershipTier
        FOREIGN KEY (membership_tier_id)
        REFERENCES MembershipTier(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);


CREATE TABLE Membership(
    id INTEGER NOT NULL,
    member_id INTEGER,
    start_timestamp TEXT NOT NULL,
    end_timestamp TEXT NOT NULL,
    status TEXT NOT NULL DEFAULT 'pending',
    membership_tier_id INTEGER NOT NULL,

    payment_due_timestamp TEXT NOT NULL,
    payment_amount REAL NOT NULL,
    payment_status TEXT NOT NULL DEFAULT 'pending',
    CONSTRAINT PK_Membership PRIMARY KEY (id),
    CONSTRAINT FK_Membership_Member
        FOREIGN KEY (member_id)
        REFERENCES Member(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE, -- if a member is deleted, all their memberships are deleted as well
    CONSTRAINT FK_Membership_MembershipTier
        FOREIGN KEY (membership_tier_id)
        REFERENCES MembershipTier(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT ValidMembershipStatus CHECK (
        status IN ('active', 'planned', 'pending', 'expired', 'cancelled')
    ),
    CONSTRAINT ValidPaymentStatus CHECK (
        payment_status IN ('paid', 'failed', 'pending')
    ),
    CONSTRAINT ValidDates CHECK (
        datetime(start_timestamp) < datetime(end_timestamp)
    ),
    CONSTRAINT ValidAmount CHECK (
        payment_amount >= 0
    )
);


CREATE TRIGGER UpdateWaitlistStatusAfterCancelledEnrollment
AFTER UPDATE ON Enrollment
FOR EACH ROW
WHEN NEW.status = 'cancelled' AND OLD.status = 'enrolled'
BEGIN
    UPDATE Enrollment
    SET status = 'enrolled', status_update_timestamp = datetime('now', 'localtime')
    WHERE class_id = NEW.class_id AND status = 'waitlisted' AND status_update_timestamp = (
        SELECT MIN(status_update_timestamp) -- select the oldest waitlisted member for that class session
        FROM Enrollment
        WHERE class_id = NEW.class_id AND status = 'waitlisted'
    );
END;


CREATE TRIGGER RemoveFromMemberWhenNoActiveMemberships
AFTER UPDATE ON Membership
FOR EACH ROW
WHEN NEW.status IN ('expired', 'cancelled')
BEGIN
    DELETE FROM Member
    WHERE id = NEW.member_id
    AND NOT EXISTS (
        SELECT 1 FROM Membership
        WHERE member_id = NEW.member_id 
        AND status IN ('active', 'planned')
    );
END;


CREATE TRIGGER BlockInsertionOfClassTypes
BEFORE INSERT ON ClassType
FOR EACH ROW
BEGIN
    SELECT RAISE(ABORT, 'Cannot insert overlapping classes for the same trainer.')
    WHERE EXISTS (
        SELECT 1
        FROM ClassType
        WHERE trainer_id = NEW.trainer_id
        AND weekday = NEW.weekday
        AND (start_time < NEW.end_time AND end_time > NEW.start_time)
    );
END;

CREATE TRIGGER BlockUpdateOfClassTypes
BEFORE UPDATE ON ClassType
FOR EACH ROW
BEGIN
    SELECT RAISE(ABORT, 'Cannot update to overlapping classes for the same trainer.')
    WHERE EXISTS (
        SELECT 1
        FROM ClassType
        WHERE trainer_id = NEW.trainer_id
        AND weekday = NEW.weekday
        AND (start_time < NEW.end_time AND end_time > NEW.start_time)
        AND id != NEW.id  -- exclude the row being updated
    );
END;


CREATE TRIGGER EraseEnrollementRowsAfterMemberDeactivation
AFTER UPDATE ON User
FOR EACH ROW
WHEN NEW.is_active = 0 AND OLD.is_active = 1
BEGIN
    UPDATE Enrollment
    SET status = 'cancelled'
    WHERE member_id = NEW.id AND status IN ('enrolled', 'waitlisted')
    AND class_id IN (
        SELECT id FROM Class
        WHERE start_timestamp > datetime('now', 'localtime')
    );
END;


CREATE TRIGGER UpdateClassesAfterTrainerDeactivation
AFTER UPDATE ON User
FOR EACH ROW
WHEN NEW.is_active = 0 AND OLD.is_active = 1
BEGIN
    DELETE FROM Class
    WHERE class_type_id IN (SELECT id FROM ClassType WHERE trainer_id = NEW.id) 
    AND start_timestamp > datetime('now', 'localtime');
END;


CREATE TRIGGER OverlappingTimeslotCreation
BEFORE INSERT ON Timeslot
FOR EACH ROW
BEGIN
    SELECT RAISE(ABORT, 'Cannot create overlapping timeslots.')
    WHERE EXISTS (
        SELECT 1
        FROM Timeslot
        WHERE (time(start_time) < time(NEW.end_time) AND time(end_time) > time(NEW.start_time))
    );
END;


CREATE TRIGGER PreventMemberToBookMultipleEquipmentsSameTimeslot
BEFORE INSERT ON EquipmentBooking
FOR EACH ROW
BEGIN
    SELECT RAISE(ABORT, 'Cannot book multiple equipments for the same timeslot.')
    WHERE EXISTS (
        SELECT 1
        FROM EquipmentBooking
        WHERE member_id = NEW.member_id AND booking_date = NEW.booking_date AND timeslot_id = NEW.timeslot_id
    );
END;

CREATE TRIGGER PreventBookingUnavailableEquipment
BEFORE INSERT ON EquipmentBooking
FOR EACH ROW
BEGIN
    SELECT RAISE(ABORT, 'Cannot book equipment that is not available.')
    WHERE EXISTS (
        SELECT 1
        FROM Equipment
        WHERE id = NEW.equipment_id AND status != 'available'
    );
END;