-- a sequence of sql statements to create schema and load test data to play with

-- CREATE USER 'newuser'@'localhost' IDENTIFIED BY 'password';
CREATE USER IF NOT EXISTS gatechUser@localhost IDENTIFIED BY 'gatech123';
-- grant all privileges on *.* to 'gatechUser'@'localhost' with grant option;
-- FLUSH PRIVILEGES;

DROP DATABASE IF EXISTS `cs6400_sm20_team04`; 
SET default_storage_engine=InnoDB;
SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS cs6400_sm20_team04 
    DEFAULT CHARACTER SET utf8mb4 
    DEFAULT COLLATE utf8mb4_unicode_ci;
USE cs6400_sm20_team04;

GRANT SELECT, INSERT, UPDATE, DELETE, FILE ON *.* TO 'gatechUser'@'localhost';
GRANT ALL PRIVILEGES ON `gatechuser`.* TO 'gatechUser'@'localhost';
GRANT ALL PRIVILEGES ON `cs6400_sm20_team04`.* TO 'gatechUser'@'localhost';
FLUSH PRIVILEGES;

-- create tables   

CREATE TABLE User (
 	email		varchar(200)	NOT NULL,
 	password	varchar(50)	NOT NULL,
 	fname		varchar(50) 	NOT NULL,
 	lname		varchar(50) 	NOT NULL,
 	phone_num	varchar(50)	NOT NULL,
	start_date	date		NOT NULL,
	PRIMARY KEY (email)
);

CREATE TABLE Volunteer (
 	email		varchar(200)	NOT NULL,
	PRIMARY KEY (email)
);

CREATE TABLE Owner (
 	email		varchar(200)	NOT NULL,
	PRIMARY KEY (email)
);

CREATE TABLE Dog (
	dog_id				int(16) unsigned		NOT NULL 	AUTO_INCREMENT,
	alteration			boolean				DEFAULT NULL,
	sex				enum('unknown','male','female')	DEFAULT 'unknown',
	description			varchar(255)			NOT NULL,
	microchip_id			int(16) unsigned		DEFAULT NULL	UNIQUE,
	name				varchar(50)			NOT NULL,
	age				float				NOT NULL,
	surrender_date			date				NOT NULL,
	surrender_by_animal_control	boolean				NOT NULL,
	surrender_reason		varchar(255)			NOT NULL,
	tracker_email			varchar(200)			NOT NULL,
	PRIMARY KEY (dog_id)
);

CREATE TABLE Adopter (
	email		varchar(200)	NOT NULL,
	fname		varchar(50) 	NOT NULL,
	lname		varchar(50) 	NOT NULL,
	phone_num	varchar(50)	NOT NULL,
	street		varchar(50)	NOT NULL,
	city		varchar(50)	NOT NULL,
	state		varchar(50)	NOT NULL,
	zip_code	varchar(50)	NOT NULL,
	PRIMARY KEY (email)
);

CREATE TABLE Application (
	app_id		int(16) unsigned			NOT NULL		AUTO_INCREMENT,
	app_date	date					NOT NULL,
	status		enum('pending','approved','rejected')	DEFAULT 'pending',
	adopter_email	varchar(200)				NOT NULL,
	co_fname	varchar(50)				DEFAULT NULL,
	co_lname	varchar(50)				DEFAULT NULL,
	PRIMARY KEY (app_id)
);

CREATE TABLE ApprovedApplication (
	app_id		int(16) unsigned	NOT NULL	AUTO_INCREMENT,
	dog_id		int(16) unsigned	NOT NULL	UNIQUE,
	adoption_fee	float			NOT NULL,
	adoption_date	date			NOT NULL,
	PRIMARY KEY (app_id)
);

CREATE TABLE RejectedApplication (
	app_id		int(16) unsigned	NOT NULL	AUTO_INCREMENT,
	PRIMARY KEY (app_id)
);

CREATE TABLE Expense (
	dog_id		int(16) unsigned	NOT NULL,
	expense_date	date 			NOT NULL,
	vendor_name 	varchar(50) 		NOT NULL,
	expense_amount	float			NOT NULL,
	expense_desc	varchar(255)		DEFAULT NULL,
	PRIMARY KEY (dog_id, expense_date, vendor_name)
);

CREATE TABLE Breed (
	breed_name varchar(50) NOT NULL,
	PRIMARY KEY (breed_name)
);

CREATE TABLE DogBreed(
	dog_id 		int(16) unsigned 	NOT NULL,
	breed_name	varchar(50) 		NOT NULL,
	PRIMARY KEY (dog_id, breed_name)
);

-- Constraints   
-- 	Foreign Keys:	fk_ChildTable_childColumn_ParentTable_parentColumn
-- 	Check:		ck_Table_column


ALTER TABLE Volunteer
  	ADD CONSTRAINT fk_Volunteer_email_User_email FOREIGN KEY (email) REFERENCES User(email)
		ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE Owner
  	ADD CONSTRAINT fk_Owner_email_User_email FOREIGN KEY (email) REFERENCES User(email)
		ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE Dog
  	ADD CONSTRAINT fk_Dog_tracker_email_User_email FOREIGN KEY (tracker_email) REFERENCES User(email)
		ON UPDATE CASCADE,
	ADD CONSTRAINT ck_Dog_age CHECK (age > 0);

ALTER TABLE Application
  	ADD CONSTRAINT fk_Application_adopter_email_Adopter_email FOREIGN KEY (adopter_email) REFERENCES Adopter(email)
		ON UPDATE CASCADE;

ALTER TABLE ApprovedApplication
  	ADD CONSTRAINT fk_ApprovedApplication_app_id_Application_app_id FOREIGN KEY (app_id) REFERENCES Application(app_id)
		ON DELETE CASCADE,
  	ADD CONSTRAINT fk_ApprovedApplication_dog_id_Dog_dog_id FOREIGN KEY (dog_id) REFERENCES Dog(dog_id),
	ADD CONSTRAINT ck_ApprovedApplication_adoption_fee CHECK (adoption_fee > 0);

ALTER TABLE RejectedApplication
  	ADD CONSTRAINT fk_RejectedApplication_app_id_Application_app_id FOREIGN KEY (app_id) REFERENCES Application(app_id)
		ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE Expense
	ADD CONSTRAINT fk_Expense_dog_id_Dog_dog_id FOREIGN KEY (dog_id) REFERENCES Dog(dog_id)
		ON DELETE CASCADE,
	ADD CONSTRAINT ck_Expense_expense_amount CHECK (expense_amount > 0);

ALTER TABLE DogBreed
	ADD CONSTRAINT fk_DogBreed_dog_id_Dog_dog_id FOREIGN KEY (dog_id) REFERENCES Dog(dog_id)
		ON DELETE CASCADE,
	ADD CONSTRAINT fk_DogBreed_breed_name_Breed_breed_name FOREIGN KEY (breed_name) REFERENCES Breed(breed_name)
		ON DELETE CASCADE ON UPDATE CASCADE;



/*--------------testing data----------------*/

-- Insert some data to play with the database 
USE cs6400_sm20_team04;

-- Insert into User
INSERT INTO `User` (email, `password`, fname, lname, phone_num, start_date) VALUES('mo@gatech.edu', '123456', 'Mo', 'Dwayne','1234567890','2020-01-01');
INSERT INTO `User` (email, `password`, fname, lname, phone_num, start_date) VALUES('lb@gatech.edu', '123456', 'Bird', 'Larry','1234567890','2020-01-02');
INSERT INTO `User` (email, `password`, fname, lname, phone_num, start_date) VALUES('sc@gatech.edu', '123456', 'Curry', 'Stephen','1234567890','2020-02-03');
INSERT INTO `User` (email, `password`, fname, lname, phone_num, start_date) VALUES('ma@gatech.edu', '123456', 'Larrymo', 'A','1234567890','2020-02-03');
INSERT INTO `User` (email, `password`, fname, lname, phone_num, start_date) VALUES('cm@gatech.edu', '123456', 'Zack', 'Mcmobird','1234567890','2020-02-03');
INSERT INTO `User` (email, `password`, fname, lname, phone_num, start_date) VALUES('user1@gatech.edu', '123456', 'Fname1', 'Lname1','1234567890','2020-02-03');

-- Insert into Volunteer
INSERT INTO Volunteer (email) VALUES('lb@gatech.edu');
INSERT INTO Volunteer (email) VALUES('sc@gatech.edu');
INSERT INTO Volunteer (email) VALUES('ma@gatech.edu');
INSERT INTO Volunteer (email) VALUES('cm@gatech.edu');
INSERT INTO Volunteer (email) VALUES('user1@gatech.edu');

-- Insert into Owner
-- {Jan: 1, Fen: 1, Mar: 4, Apr: 1, May: 2, Jun: 1, Jul: 1}
INSERT INTO Owner (email) VALUES('mo@gatech.edu');

-- Insert into Dog
INSERT INTO Dog (dog_id, alteration, sex, description, microchip_id, name, age, surrender_date, surrender_by_animal_control, surrender_reason, tracker_email) 
	VALUES(NULL, 1, 'unknown', 'this dog is friendly', 15, 'Luffy', 2.2, '2020-03-04', 1, 'owner not able to afford', 'mo@gatech.edu');
INSERT INTO Dog (dog_id, alteration, sex, description, microchip_id, name, age, surrender_date, surrender_by_animal_control, surrender_reason, tracker_email) 
	VALUES(NULL, 1, 'female', 'this dog is shy', 20341, 'Soro', 1.23, '2020-01-31', 1, 'abandoned by owner', 'lb@gatech.edu');
INSERT INTO Dog (dog_id, alteration, sex, description, microchip_id, name, age, surrender_date, surrender_by_animal_control, surrender_reason, tracker_email) 
	VALUES(NULL, 1, 'male', 'this dog is energetic', 98532, 'Hancock', 0.42, '2020-02-20', 1, 'lost owner', 'sc@gatech.edu');
INSERT INTO Dog (dog_id, alteration, sex, description, microchip_id, name, age, surrender_date, surrender_by_animal_control, surrender_reason, tracker_email) 
	VALUES(NULL, 1, 'unknown', 'this dog is friendly', 5213, 'Luffy2', 2.2, '2020-01-05', 1, 'owner not able to afford', 'mo@gatech.edu');
INSERT INTO Dog (dog_id, alteration, sex, description, microchip_id, name, age, surrender_date, surrender_by_animal_control, surrender_reason, tracker_email) 
	VALUES(NULL, 1, 'unknown', 'this dog is friendly', 123, 'Luffy3', 2.2, '2020-01-06', 1, 'owner not able to afford', 'mo@gatech.edu');
INSERT INTO Dog (dog_id, alteration, sex, description, microchip_id, name, age, surrender_date, surrender_by_animal_control, surrender_reason, tracker_email) 
	VALUES(NULL, 1, 'unknown', 'this dog is friendly', 151, 'Luffy4', 2.2, '2020-01-04', 1, 'owner not able to afford', 'mo@gatech.edu');
INSERT INTO Dog (dog_id, alteration, sex, description, microchip_id, name, age, surrender_date, surrender_by_animal_control, surrender_reason, tracker_email) 
	VALUES(NULL, 1, 'unknown', 'this dog is friendly', 1123, 'Luffy5', 2.2, '2020-05-04', 1, 'owner not able to afford', 'mo@gatech.edu');
INSERT INTO Dog (dog_id, alteration, sex, description, microchip_id, name, age, surrender_date, surrender_by_animal_control, surrender_reason, tracker_email) 
	VALUES(NULL, 1, 'unknown', 'this dog is friendly', 4, 'Luffy6', 2.2, '2020-06-04', 1, 'owner not able to afford', 'mo@gatech.edu');
INSERT INTO Dog (dog_id, alteration, sex, description, microchip_id, name, age, surrender_date, surrender_by_animal_control, surrender_reason, tracker_email) 
	VALUES(NULL, 1, 'unknown', 'this dog is friendly', 6, 'Luffy7', 2.2, '2020-07-04', 1, 'owner not able to afford', 'mo@gatech.edu');
INSERT INTO Dog (dog_id, alteration, sex, description, microchip_id, name, age, surrender_date, surrender_by_animal_control, surrender_reason, tracker_email) 
	VALUES(NULL, 1, 'unknown', 'this dog is friendly', 21, 'Luffy8', 2.2, '2020-02-04', 1, 'owner not able to afford', 'mo@gatech.edu');
INSERT INTO Dog (dog_id, alteration, sex, description, microchip_id, name, age, surrender_date, surrender_by_animal_control, surrender_reason, tracker_email) 
	VALUES(NULL, 1, 'unknown', 'this dog is friendly', 9, 'Luffy9', 2.2, '2020-01-04', 0, 'owner not able to afford', 'mo@gatech.edu');
INSERT INTO Dog (dog_id, alteration, sex, description, microchip_id, name, age, surrender_date, surrender_by_animal_control, surrender_reason, tracker_email) 
	VALUES(NULL, 1, 'unknown', 'this dog is friendly', 99, 'Luffy10', 2.2, '2020-04-15', 1, 'owner not able to afford', 'sc@gatech.edu');

-- Insert into Adopter
INSERT INTO Adopter (email, fname, lname, phone_num, street, city, state, zip_code) 
	VALUES('alice@email.com', 'Alice', 'Dwight', '123123', '1 Beacon Street', 'Baton Rouge','LA', 00001);
INSERT INTO Adopter (email, fname, lname, phone_num, street, city, state, zip_code) 
	VALUES('bob@email.com', 'Bob', 'Bryant', '123123', '1 Lincoln Street', 'Houston','TX', 00002);
INSERT INTO Adopter (email, fname, lname, phone_num, street, city, state, zip_code) 
	VALUES('cody1@email.com', 'Cody1', 'Jordan1', '123123', '1 Big Street', 'New York','NY', 00003);
INSERT INTO Adopter (email, fname, lname, phone_num, street, city, state, zip_code) 
	VALUES('cody2@email.com', 'Cody2', 'Jordan2', '123123', '1 Big Street', 'New York','NY', 00003);
INSERT INTO Adopter (email, fname, lname, phone_num, street, city, state, zip_code) 
	VALUES('cody3@email.com', 'Cody3', 'Jordan3', '123123', '1 Big Street', 'New York','NY', 00003);

-- Insert into Application
INSERT INTO Application (app_id, app_date, status, adopter_email, co_fname, co_lname) 
	VALUES(NULL, '2020-05-21', 'approved', 'alice@email.com', 'AliceCo', 'DwightCo');
INSERT INTO Application (app_id, app_date, status, adopter_email, co_fname, co_lname) 
	VALUES(NULL, '2020-01-22', 'approved', 'bob@email.com', 'BobCo', 'BryantCo');
INSERT INTO Application (app_id, app_date, status, adopter_email, co_fname, co_lname) 
	VALUES(NULL, '2020-02-23', 'approved', 'cody1@email.com', 'CodyCo1', 'JordanCo1');
INSERT INTO Application (app_id, app_date, status, adopter_email, co_fname, co_lname) 
	VALUES(NULL, '2020-03-26', 'approved', 'cody2@email.com', 'CodyCo2', 'JordanCo2');
INSERT INTO Application (app_id, app_date, status, adopter_email, co_fname, co_lname) 
	VALUES(NULL, '2020-04-23', 'approved', 'cody3@email.com', 'CodyCo31', 'JordanCo31');
INSERT INTO Application (app_id, app_date, status, adopter_email, co_fname, co_lname) 
	VALUES(NULL, '2019-12-24', 'approved', 'cody3@email.com', 'CodyCo32', 'JordanCo32');

-- Insert into Approved Application
INSERT INTO ApprovedApplication (app_id, dog_id, adoption_fee, adoption_date)
	VALUES(1, 1, 10.123, '2020-05-22');
INSERT INTO ApprovedApplication (app_id, dog_id, adoption_fee, adoption_date)
	VALUES(2, 2, 21.234, '2020-05-25');
INSERT INTO ApprovedApplication (app_id, dog_id, adoption_fee, adoption_date)
	VALUES(3, 3, 50.523, '2020-05-30');
INSERT INTO ApprovedApplication (app_id, dog_id, adoption_fee, adoption_date)
	VALUES(4, 4, 50.523, '2020-07-08');

-- Insert into Breed
INSERT INTO `Breed` (breed_name) VALUES('unknown');
INSERT INTO `Breed` (breed_name) VALUES('mixed');
INSERT INTO `Breed` (breed_name) VALUES('Affenpinscher');
INSERT INTO `Breed` (breed_name) VALUES('Mudi');
INSERT INTO `Breed` (breed_name) VALUES('Saluki');
INSERT INTO `Breed` (breed_name) VALUES('Maltese');
INSERT INTO `Breed` (breed_name) VALUES('Lurcher');

-- Insert into Expense
INSERT INTO `Expense` (dog_id, expense_date, vendor_name, expense_amount, expense_desc) 
	VALUES(1, '2020-03-24', 'vendor1', 100.42, 'medical use');
INSERT INTO `Expense` (dog_id, expense_date, vendor_name, expense_amount, expense_desc) 
	VALUES(2, '2020-02-28', 'vendor2', 929.15, 'food use');