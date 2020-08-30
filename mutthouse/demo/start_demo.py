#from __future__ import print_function
import mysql.connector
from mysql.connector import errorcode
from schema import *
import pandas as pd
from datetime import datetime
import numpy as np
import os
from pathlib import Path
#os.chdir(Path(os.getcwd()).parent)
print(os.getcwd())

# This SCRIPT will be used as the start point for the project final demo.
# it connects to mysql server with credentials, create schema, load breed appendix, load demo data
# if it fails, run the following SQL DDL in mysql client interface 
# or phpMyAdmin, it creates the mysql credentials for this project use:
# mysql> CREATE USER IF NOT EXISTS gatechUser@localhost IDENTIFIED BY 'gatech123';
# mysql> grant all privileges on *.* to 'gatechUser'@'localhost' with grant option;
# mysql> FLUSH PRIVILEGES;
print('building connection and specifying database name: ')
user = 'gatechUser'
password = 'gatech123'
host='127.0.0.1'
port=3306
DB_NAME = 'cs6400_sm20_team04'
print('use default mysql connection configuration? (Y/N)')
print(
f"user: \t\t{user}\n\
host: \t\t{host}\n\
port: \t\t{port}\n\
DB_NAME: \t{DB_NAME}\n\
")
default = input()
if (default.lower() == 'n'):
	user = input('Enter mysql client user (please check lib/common.php and AMP manager): \n')
	password = input('Enter passowrd: \n')
	host = input('Enter client address: (e.g. localhost: 127.0.0.1): \n')
	port = input('Enter client port: \n')
	DB_NAME = input('Enter database name: \n')
print("\n\n\n---------------------------------------------------------")

# (init and) connect to db instance
prefix = ''
cnx = mysql.connector.connect(user=user, 
							  password=password,
                              host=host, 
                              port=int(port) )
                              #database='cs6400_sm20_team04')
cursor = cnx.cursor()
print("connection built: Python <-> MySQL server\n")

cursor.execute(f"DROP DATABASE IF EXISTS {DB_NAME}")
try:
	cursor.execute(f"CREATE DATABASE IF NOT EXISTS {DB_NAME} \
				 DEFAULT CHARACTER SET utf8mb4 \
				 DEFAULT COLLATE utf8mb4_unicode_ci")
	print(f"new database {DB_NAME} is created in MySQL Server")
except:
	print('error 1')
try:
	cursor.execute(f"USE {DB_NAME}")
	print(f"using database {DB_NAME}")
except:
	print('error 2')


# create db schema construts
for table_name in TABLES:
    table_description = TABLES[table_name]
    try:
        print(f"Creating table {table_name}: ", end='')
        cursor.execute(table_description)
    except mysql.connector.Error as err:
        if err.errno == errorcode.ER_TABLE_EXISTS_ERROR:
            print("already exists.")
        else:
            print(err.msg)
    else:
        print("OK")

for c in Constraints:
	cursor.execute(c)
print('Adding table constraints: OK')


# 0: breed appendix
breed = pd.read_csv(f"{prefix}data/breed.csv")
for i in breed.index:
	cursor.execute(add_breed, (breed.loc[i, 'breed'], ))
cnx.commit()
print('Loaded: Breed')
del breed


"""load demo data"""
# 1: user, volunteer, owner
user = pd.read_csv(f"{prefix}demo/Users.tsv", sep='\t')
for i in user.index:
	cursor.execute(add_user, (user.loc[i, 'email'],
							  user.loc[i, 'password'],
							  user.loc[i, 'u_f_name'],
							  user.loc[i, 'u_l_name'],
							  user.loc[i, 'start_date'],
							  str(user.loc[i, 'phone']),
								))
	if user.loc[i, 'Volunteer'] == 0:
		cursor.execute(add_owner, (user.loc[i, 'email'], ))
	else:
		cursor.execute(add_volunteer, (user.loc[i, 'email'], ))
cnx.commit()
print('Loaded: User, Owner, Volunteer')
del user
# 2: dog, dogbreed
dog = pd.read_csv(f"{prefix}demo/Dog.tsv", sep='\t')
seen = set()
for i in dog.index:
	_t = dog.loc[i, :].to_dict()
	if _t['dog_id'] not in seen:
		cursor.execute(add_dog,  (int(_t['dog_id']),
								  _t['dog_name'],
								  _t['sex'],
								  int(_t['alt_status']),
								  int(_t['animal_control']),
								  datetime.strptime(_t['surrender_date'], "%Y-%m-%d").date(),
								  _t['surrender_reason'],
								  _t['description'],
								  float(_t['age_months']/12),
								  str(int(_t['microchip'])) if not np.isnan(_t['microchip']) else None,
								  _t['email']
								 ) )
		seen.add(_t['dog_id'])
	cursor.execute(add_dogbreed, (int(_t['dog_id']), 
								  _t['breed_name']) )
cnx.commit()
print('Loaded: Dog, DogBreed')
del seen
# 3: adopter, app, app_rej
seen = set()
app = pd.read_csv(f"{prefix}demo/Applications.tsv", sep='\t')
_dic = {0:'pending', -1:'approved', 1:'rejected'}
for i in app.index:
	# adopter
	if app.loc[i, 'a_email'] not in seen:
		cursor.execute(add_adopter, (
			app.loc[i, 'a_email'],
			app.loc[i, 'a_f_name'],
			app.loc[i, 'a_l_name'],
			str(app.loc[i, 'a_phone']),
			app.loc[i, 'a_street_addr'],
			app.loc[i, 'a_city'],
			app.loc[i, 'a_state'],
			str(app.loc[i, 'a_postal_code']),
			))
		seen.add(app.loc[i, 'a_email'])	
	# app
	_status = _dic[app.loc[i, 'is_approved'] - app.loc[i, 'is_rejected']]
	cursor.execute(add_app, (
		int(app.loc[i, 'app_num']),
		app.loc[i, 'app_date'],
		app.loc[i, 'coapp_f_name'] if not pd.isnull(app.loc[i, 'coapp_f_name']) else None,
		app.loc[i, 'coapp_l_name'] if not pd.isnull(app.loc[i, 'coapp_l_name']) else None,
		app.loc[i, 'a_email'],
		_status,
		) )
	# app_rej
	if _status == 'rejected':
		cursor.execute(add_app_rej, (int(app.loc[i, 'app_num']), ) )
cnx.commit()
print('Loaded: Adopter, Application, Rejected Application')
del seen
# 4: expense
expense = pd.read_csv(f"{prefix}demo/Expenses.tsv", sep='\t')
for i in expense.index:
	cursor.execute(add_expense, (
		int(expense.loc[i, 'dog_id']),
		expense.loc[i, 'date_expense'],
		expense.loc[i, 'vendor_name'],
		float(expense.loc[i, 'amount_expense']),
		expense.loc[i, 'description'] if not pd.isnull(expense.loc[i, 'description']) else None,
		))
# 5: app_apr
seen = set()
adoption = pd.read_csv(f"{prefix}demo/Adoption.tsv", sep='\t')
dog = pd.read_csv('demo/Dog.tsv', sep='\t').drop_duplicates(subset=['dog_id', 'animal_control'], keep="first")
dog = pd.merge(dog.loc[:, ['dog_id', 'animal_control']], adoption, left_on='dog_id', right_on='dog_id', how='left')
_merg = pd.merge(dog, expense, left_on='dog_id', right_on='dog_id', how='left')
_merg['fee'] = _merg.apply(lambda x: x['amount_expense']*0.15 if x['animal_control']==1 else x['amount_expense']*1.15, axis=1)
_grp = _merg.groupby('dog_id', as_index=False)['fee'].sum()
dog = pd.merge(dog, _grp, left_on='dog_id', right_on='dog_id', how='left')
dog = dog.dropna(subset=['adoption_date'])
for i in dog.index:
	cursor.execute(add_app_apr, (
		int(dog.loc[i, 'app_num']),
		int(dog.loc[i, 'dog_id']),
		float(dog.loc[i, 'fee']),
		dog.loc[i, 'adoption_date'],
		))
cnx.commit()
print('Loaded: Approved Application')
del app
del expense
del adoption
del _merg
del dog
del _grp
print('\nnow demo data are ready in database')

# exit mysql connection and cleanup
cursor.close()
cnx.close()

print('closed connection from python')