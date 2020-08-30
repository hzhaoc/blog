# table schema
TABLES = {}

TABLES['User'] = \
"CREATE TABLE User ( \
 	email		varchar(200)	NOT NULL, \
 	password	varchar(50)		NOT NULL, \
 	fname		varchar(50) 	NOT NULL, \
 	lname		varchar(50) 	NOT NULL, \
 	phone_num	varchar(50)		NOT NULL, \
	start_date	date			NOT NULL, \
	PRIMARY KEY (email) \
)"

TABLES['Volunteer'] = \
"CREATE TABLE Volunteer ( \
	email		varchar(200)	NOT NULL, \
	PRIMARY KEY (email) \
)"

TABLES['Owner'] = \
"CREATE TABLE Owner ( \
	email		varchar(200)	NOT NULL, \
	PRIMARY KEY (email) \
)"

TABLES['Dog'] = \
"CREATE TABLE Dog ( \
	dog_id						int(16) unsigned				NOT NULL 	AUTO_INCREMENT, \
	alteration					boolean							DEFAULT NULL, \
	sex							enum('unknown','male','female')	DEFAULT 'unknown', \
	description					varchar(255)					NOT NULL, \
	microchip_id				varchar(50)						DEFAULT NULL	UNIQUE, \
	name						varchar(50)						NOT NULL,\
	age							float							NOT NULL,\
	surrender_date				date							NOT NULL,\
	surrender_by_animal_control	boolean							NOT NULL,\
	surrender_reason			varchar(255)					NOT NULL,\
	tracker_email				varchar(200)					NOT NULL,\
	PRIMARY KEY (dog_id)\
)"

TABLES['Adopter'] =\
"CREATE TABLE Adopter (\
	email		varchar(200)	NOT NULL,\
	fname		varchar(50) 	NOT NULL,\
	lname		varchar(50) 	NOT NULL,\
	phone_num	varchar(50)		NOT NULL,\
	street		varchar(50)		NOT NULL,\
	city		varchar(50)		NOT NULL,\
	state		varchar(50)		NOT NULL,\
	zip_code	varchar(50)		NOT NULL,\
	PRIMARY KEY (email)\
)"

TABLES['Application']=\
"CREATE TABLE Application (\
	app_id			int(16) unsigned						NOT NULL		AUTO_INCREMENT,\
	app_date		date									NOT NULL,\
	status			enum('pending','approved','rejected')	DEFAULT 'pending',\
	adopter_email	varchar(200)							NOT NULL,\
	co_fname		varchar(50)								DEFAULT NULL,\
	co_lname		varchar(50)								DEFAULT NULL,\
	PRIMARY KEY (app_id)\
)"

TABLES['ApprovedApplication']=\
"CREATE TABLE ApprovedApplication (\
	app_id			int(16) unsigned	NOT NULL	AUTO_INCREMENT,\
	dog_id			int(16) unsigned	NOT NULL	UNIQUE,\
	adoption_fee	float				NOT NULL,\
	adoption_date	date				NOT NULL,\
	PRIMARY KEY (app_id)\
)"

TABLES['RejectedApplication']=\
"CREATE TABLE RejectedApplication (\
	app_id		int(16) unsigned	NOT NULL	AUTO_INCREMENT,\
	PRIMARY KEY (app_id)\
)"

TABLES['Expense']=\
"CREATE TABLE Expense (\
	dog_id			int(16) unsigned	NOT NULL,\
	expense_date	date 				NOT NULL,\
	vendor_name 	varchar(50) 		NOT NULL,\
	expense_amount	float				NOT NULL,\
	expense_desc	varchar(255)		DEFAULT NULL,\
	PRIMARY KEY (dog_id, expense_date, vendor_name)\
)"

TABLES['Breed']=\
"CREATE TABLE Breed (\
	breed_name varchar(50) NOT NULL,\
	PRIMARY KEY (breed_name)\
)"

TABLES['DogBreed']=\
"CREATE TABLE DogBreed(\
	dog_id 		int(16) unsigned 	NOT NULL,\
	breed_name	varchar(50) 		NOT NULL,\
	PRIMARY KEY (dog_id, breed_name)\
)"

# Constraints   
# 	Foreign Keys:	fk_ChildTable_childColumn_ParentTable_parentColumn
# 	Check:		ck_Table_column
Constraints=[]
Constraints.append(
	"ALTER TABLE Volunteer\
  	ADD CONSTRAINT fk_Volunteer_email_User_email FOREIGN KEY (email) REFERENCES User(email)\
		ON UPDATE CASCADE ON DELETE CASCADE"
	)
Constraints.append(
	"ALTER TABLE Owner\
  	ADD CONSTRAINT fk_Owner_email_User_email FOREIGN KEY (email) REFERENCES User(email)\
		ON UPDATE CASCADE ON DELETE CASCADE"
	)
Constraints.append(
	"ALTER TABLE Dog\
  	ADD CONSTRAINT fk_Dog_tracker_email_User_email FOREIGN KEY (tracker_email) REFERENCES User(email)\
		ON UPDATE CASCADE"
	)
Constraints.append(
	"ALTER TABLE Application\
  	ADD CONSTRAINT fk_Application_adopter_email_Adopter_email FOREIGN KEY (adopter_email) REFERENCES Adopter(email)\
		ON UPDATE CASCADE"
	)
Constraints.append(
	"ALTER TABLE ApprovedApplication\
  	ADD CONSTRAINT fk_ApprovedApplication_app_id_Application_app_id FOREIGN KEY (app_id) REFERENCES Application(app_id)\
		ON DELETE CASCADE,\
  	ADD CONSTRAINT fk_ApprovedApplication_dog_id_Dog_dog_id FOREIGN KEY (dog_id) REFERENCES Dog(dog_id),\
	ADD CONSTRAINT ck_ApprovedApplication_adoption_fee CHECK (adoption_fee >= 0)"
	)
Constraints.append(
	"ALTER TABLE RejectedApplication\
  	ADD CONSTRAINT fk_RejectedApplication_app_id_Application_app_id FOREIGN KEY (app_id) REFERENCES Application(app_id)\
		ON DELETE CASCADE ON UPDATE CASCADE"
	)
Constraints.append(
	"ALTER TABLE Expense\
	ADD CONSTRAINT fk_Expense_dog_id_Dog_dog_id FOREIGN KEY (dog_id) REFERENCES Dog(dog_id)\
		ON DELETE CASCADE,\
	ADD CONSTRAINT ck_Expense_expense_amount CHECK (expense_amount > 0)"\
	)
Constraints.append(
	"ALTER TABLE DogBreed\
	ADD CONSTRAINT fk_DogBreed_dog_id_Dog_dog_id FOREIGN KEY (dog_id) REFERENCES Dog(dog_id)\
		ON DELETE CASCADE,\
	ADD CONSTRAINT fk_DogBreed_breed_name_Breed_breed_name FOREIGN KEY (breed_name) REFERENCES Breed(breed_name)\
		ON DELETE CASCADE ON UPDATE CASCADE"
	)

# insert
add_breed = \
"INSERT INTO Breed (breed_name) VALUES(%s)"

add_user = \
"INSERT INTO `User` (email, `password`, fname, lname, start_date, phone_num) \
VALUES(%s, %s, %s, %s, %s, %s)"

add_owner = \
"INSERT INTO Owner (email) VALUES(%s)"

add_volunteer = \
"INSERT INTO Volunteer (email) VALUES(%s)"

add_dog = \
"INSERT INTO Dog (dog_id, name, sex, alteration, surrender_by_animal_control, \
				  surrender_date, surrender_reason, description, age, microchip_id, tracker_email) \
VALUES(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)"

add_dogbreed = \
"INSERT INTO DogBreed (dog_id, breed_name) VALUES (%s, %s)"

add_app = \
"INSERT INTO Application (app_id, app_date, co_fname, co_lname, adopter_email, status) \
	VALUES(%s, %s, %s, %s, %s, %s)"

add_adopter = \
"INSERT INTO Adopter (email, fname, lname, phone_num, street, city, state, zip_code) \
	VALUES(%s, %s, %s, %s, %s, %s, %s, %s)"

add_app_rej = \
"INSERT INTO RejectedApplication (app_id) VALUES(%s)"

add_app_apr = \
"INSERT INTO ApprovedApplication (app_id, dog_id, adoption_fee, adoption_date)\
	VALUES(%s, %s, %s, %s)"

add_expense = \
"INSERT INTO Expense (dog_id, expense_date, vendor_name, expense_amount, expense_desc)\
	VALUES(%s, %s, %s, %s, %s)"