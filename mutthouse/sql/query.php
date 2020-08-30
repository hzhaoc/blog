<?php 

function get_query_isonwer($email){
	/*check if the User of current session is onwer*/
	return 
	"SELECT email FROM Owner where email='$email'";
}

function get_query_login($Email, $Password){
	return "SELECT 1 AS res FROM User WHERE email = '$Email' AND password = '$Password'";
}

function get_query_register_validate($Email){
	return "SELECT 1 AS res FROM User WHERE email = '$Email'";
}

function insert_dog($alteration, $sex, $description, $microchip_id, $name, $age, $surrender_date, $surrender_by_animal_control, $surrender_reason, $tracker_email){
	return
	"
	INSERT INTO Dog 
    VALUES (NULL, $alteration, '$sex', '$description', 
    		'$microchip_id', '$name', $age, '$surrender_date', 
			$surrender_by_animal_control, '$surrender_reason', '$tracker_email')
	";
}

function insert_dog_nullchip($alteration, $sex, $description, $name, $age, $surrender_date, $surrender_by_animal_control, $surrender_reason, $tracker_email){
	return
	"
	INSERT INTO Dog 
    VALUES (NULL, $alteration, '$sex', '$description', 
    		NULL, '$name', $age, '$surrender_date', 
			$surrender_by_animal_control, '$surrender_reason', '$tracker_email')
	";
}

function insert_user($email, $password, $fname, $lname, $phone_num, $start_date){
	return 
	"
	INSERT INTO User (email, password, fname, lname, phone_num, start_date)
	VALUES ('$email', '$password', '$fname', '$lname', '$phone_num', '$start_date')
	";
}

function insert_owner($email){
	return 
	"
	INSERT INTO Owner
	VALUES ('$email')
	";
}

function insert_volunteer($email){
	return 
	"
	INSERT INTO Volunteer
	VALUES ('$email')
	";
}

function get_query_availspots($capacity){
	/*return available spaces*/
	return
	"SELECT ($capacity - COUNT(*)) AS available_spots " .
    "FROM Dog " .
    "WHERE dog_id NOT IN (SELECT dog_id from ApprovedApplication) ";
}

function get_query_doglist(){
	return
	"SELECT * FROM (SELECT Dog.dog_id, Dog.name, Dog.sex, Dog.alteration, Dog.age,
	                        Case when Dog.alteration != 0 
	                             And Dog.microchip_id is not null
	                             THEN 'Adoptable'
	                             ELSE 'Nonadoptable' END AS adoptability,
                            Dog.surrender_date,
                            db2.breed
	  From Dog
	  INNER JOIN 
	    (SELECT d.dog_id, group_concat(breed_name order by breed_name ASC SEPARATOR '/') AS breed
	    From Dog d
	    Left join DogBreed db
	    On d.dog_id = db.dog_id
	    Group by d.dog_id
	    ) db2
	    ON Dog.dog_id = db2.dog_id
	    where not exists (
	    select *
	    from ApprovedApplication
	    where Dog.dog_id = ApprovedApplication.dog_id
	    )
	    order by Dog.surrender_date ) result";
}

$dogsort = array("name"=>" ORDER BY name",
				  "breed"=>" ORDER BY breed",
				  "sex"=>" ORDER BY sex",
				  "alteration"=>" ORDER BY alteration",
				  "age"=>" ORDER BY age",
				  "adop"=>" ORDER BY adoptability"
				);

function get_query_rpt_actrl1(){
	/*return number of dogs surrenderd by animal control grouped by month 
	for the current and past 6 months*/
	return 
	"SELECT DATE_FORMAT(surrender_date, '%Y-%m') AS month, " .
	"COUNT(dog_id) AS dog_number_by_animal_control " .
	"FROM Dog " .
	"WHERE Dog.surrender_by_animal_control = TRUE " .
	"AND " .
	"Dog.surrender_date <= CURDATE() " .
	"AND " .
	"Dog.surrender_date > LAST_DAY(DATE_ADD(CURDATE(), INTERVAL -7 MONTH)) " .
	"GROUP BY DATE_FORMAT(surrender_date, '%Y-%m') " .
	"ORDER BY DATE_FORMAT(surrender_date, '%Y-%m') ASC ";
}

function get_query_rpt_actrl2(){
	/*return number of dogs adopted and who had been in recue for no less than 60 days, 
	grouped by month, for the current and past 6 months*/
	return 
	"SELECT DATE_FORMAT(surrender_date, '%Y-%m') AS month, " .
	"COUNT(*) AS dog_number_60_days " .
	"FROM ApprovedApplication AA " .
	"LEFT JOIN Dog ON Dog.dog_id = AA.dog_id " .
	"WHERE surrender_by_animal_control = TRUE " .
	"AND " .
	"DATEDIFF(adoption_date, surrender_date) >=60 " .
	"AND " .
	"surrender_date <= CURDATE() " .
	"AND " .
	"surrender_date > LAST_DAY(DATE_ADD(CURDATE(), INTERVAL -7 MONTH)) " .
	"GROUP BY DATE_FORMAT(surrender_date, '%Y-%m') " .
	"ORDER BY DATE_FORMAT(surrender_date, '%Y-%m') ASC ";
}

function get_query_rpt_actrl3(){
	/*return total Expense of Dog adopted
	for the current and past 6 months*/
	return
	"SELECT DATE_FORMAT(surrender_date, '%Y-%m') AS month, " .
	"SUM(expense_amount) AS total_expense " .
	"FROM ApprovedApplication AA " .
	"LEFT JOIN Dog D ON D.dog_id = AA.dog_id " .
	"LEFT JOIN Expense E ON E.dog_id = D.dog_id " .
	"WHERE surrender_by_animal_control = TRUE " .
	"AND " .
	"surrender_date <= CURDATE() " .
	"AND " .
	"surrender_date > LAST_DAY(DATE_ADD(CURDATE(), INTERVAL -7 MONTH)) " .
	"GROUP BY DATE_FORMAT(surrender_date, '%Y-%m') " .
	"ORDER BY DATE_FORMAT(surrender_date, '%Y-%m') ASC "; 
}

function get_query_volunteer_lookup($input_name){
	return 
	"SELECT fname, lname, email, phone_num " .
	"FROM User U " .
	"WHERE U.fname LIKE '%$input_name%' " .
	"OR " .
	"U.lname LIKE '%$input_name%' " .
	"ORDER BY lname ASC, fname ASC";
}

function get_query_drilldown1($month_start){
	/*drill down report for animal control sourced surrenderedd dogs of a month*/
	return
	"
	SELECT D0.dog_id, breed, sex, alteration, microchip_id, surrender_date
	FROM Dog D0
	INNER JOIN
		(SELECT DD.dog_id, 
	     		group_concat(breed_name order by breed_name ASC SEPARATOR '/') AS breed
		FROM Dog DD
		LEFT JOIN DogBreed DB ON DD.dog_id = DB.dog_id
		GROUP BY DD.dog_id) D2
	ON D0.dog_id = D2.dog_id
	WHERE surrender_by_animal_control = TRUE
	AND surrender_date >= '$month_start'
	AND surrender_date <= LEAST(LAST_DAY('$month_start'), CURDATE())
	ORDER BY dog_id ASC
	";
}

function get_query_drilldown2($month_start){
	/*drill down report for animal control sourced adopteddogs of a month*/
	return
	"
	SELECT
	    D0.dog_id,
	    breed,
	    sex,
	    alteration,
	    microchip_id,
	    surrender_date,
	    DATEDIFF(adoption_date, surrender_date) AS days_in_rescue
	FROM
	    ApprovedApplication AA
	LEFT JOIN Dog D0 
		ON D0.dog_id = AA.dog_id
	INNER JOIN(
	    SELECT
	        DD.dog_id,
	        GROUP_CONCAT(
	            breed_name
	        ORDER BY
	            breed_name ASC SEPARATOR '/'
	        ) AS breed
	    FROM
	        Dog DD
	    LEFT JOIN DogBreed DB ON
	        DD.dog_id = DB.dog_id
	    GROUP BY
	        DD.dog_id
		) D1
	ON D0.dog_id = D1.dog_id
	WHERE surrender_by_animal_control = TRUE 
	    AND adoption_date >= '$month_start' 
	    AND adoption_date <= LEAST(LAST_DAY('$month_start'),CURDATE())
	ORDER BY days_in_rescue DESC, dog_id DESC
	";
}

function get_query_rpt_mthly_adoption(){
	/*total expenses, adoption fees, net profit, number of surrenderered dogs, number of 
		adopted dogs per month*/
	return
	"
	SELECT
	    DATE_FORMAT(surrender_date, '%Y-%m') AS MONTH,
	    breed,
	    COUNT(D0.dog_id) AS num_surrender,
	    COUNT(app_id) AS num_adoption,
	    SUM(dog_expense) AS total_expense,
	    SUM(adoption_fee) AS total_fee,
	    (SUM(adoption_fee) - SUM(dog_expense)) AS net_profit
	FROM
	    Dog D0
	INNER JOIN(
	    SELECT
	        dtmp.dog_id,
	        GROUP_CONCAT(
	            breed_name
	        ORDER BY
	            breed_name ASC SEPARATOR '/'
	        ) AS breed
	    FROM
	        Dog dtmp
	    LEFT JOIN DogBreed DB ON
	        dtmp.dog_id = DB.dog_id
	    GROUP BY
	        dtmp.dog_id
	) D1
	ON
	    D0.dog_id = D1.dog_id
	LEFT JOIN 
		ApprovedApplication AA 
	ON
	   	D0.dog_id = AA.dog_id
	LEFT JOIN(
		SELECT
	    	de.dog_id,
	    	SUM(E.expense_amount) AS dog_expense
	    FROM
	    	Dog de
	    INNER JOIN
	    	Expense E
	    ON de.dog_id = E.dog_id
	    GROUP BY
	    	de.dog_id
	) D2
	ON 
		D0.dog_id = D2.dog_id
	WHERE
		D0.surrender_date <= LAST_DAY(DATE_ADD(CURDATE(), INTERVAL -1 MONTH)) 
	AND 
		D0.surrender_date > LAST_DAY(DATE_ADD(CURDATE(), INTERVAL -13 MONTH))
	GROUP BY
		DATE_FORMAT(surrender_date, '%Y-%m'),
		breed
	ORDER BY
		DATE_FORMAT(surrender_date, '%Y-%m') ASC,
		breed ASC
	";
}

function get_query_rpt_exp(){
	/*total expenses per vendor*/
	return
	"
	SELECT vendor_name, SUM(expense_amount) AS total_expense
	FROM Dog
	LEFT JOIN Expense ON Expense.dog_id = Dog.dog_id
	GROUP BY vendor_name
	ORDER BY total_expense DESC
	";
}

function get_query_dog($dog_id){
	return
	"
	SELECT
	    D.dog_id,
	    D.name,
	    D.age,
	    D.sex,
	    D.alteration,
	    D.surrender_date,
	    D.surrender_by_animal_control,
	    D.surrender_reason,
	    D.description,
	    D.microchip_id,
	    D1.breed,
	    User.fname,
	    User.lname
	FROM
	    Dog D
	INNER JOIN(
	    SELECT
	        DD.dog_id,
	        GROUP_CONCAT(
	            breed_name
	        ORDER BY
	            breed_name ASC SEPARATOR '/'
	        ) AS breed
	    FROM
	        Dog DD
	    LEFT JOIN DogBreed DB ON
	        DD.dog_id = DB.dog_id
	    GROUP BY
	        DD.dog_id
	) D1
	ON
	    D.dog_id = D1.dog_id
	LEFT JOIN
		User
	ON
		User.email = D.tracker_email
	WHERE
	    D.dog_id = $dog_id
	";
}

function get_query_dog_adpotability($dog_id){
	return
	"
	SELECT 1 AS res
	FROM
		Dog
	WHERE
		Dog.dog_id = $dog_id
		AND
		alteration != 0 
		AND
		microchip_id is not null
	";
}

function get_query_dog_exp($dog_id){
	return
	"
	SELECT vendor_name, expense_amount, expense_date, expense_desc
	FROM Dog D
	INNER JOIN
	Expense E
	ON E.dog_id = D.dog_id
	WHERE D.dog_id = $dog_id
	";
}

function get_query_breedlist(){
	return
	"
	SELECT breed_name from Breed
	";
}

function insert_dog_breed($dog_id, $breed_name){
	return 
	"
	INSERT INTO DogBreed (dog_id, breed_name)
	VALUES ($dog_id, '$breed_name')
	";
}

function delete_dog_breed($dog_id){
	return
	"
	DELETE FROM DogBreed
	WHERE dog_id = $dog_id
	";
}

function update_dog_alteration($dog_id, $chipid){
	return 
	"
	UPDATE Dog
	SET alteration = 1
	WHERE dog_id = $dog_id AND alteration = 0
	";
}

function update_dog_sex($dog_id, $sex){
	return 
	"
	UPDATE Dog
	SET sex = '$sex'
	WHERE dog_id = $dog_id AND sex = 'unknown'
	";
}

function insert_dog_exp($dog_id, $expense_date, $vendor_name, $amount, $description){
	return
	"
	INSERT INTO Expense (dog_id, expense_date, vendor_name, expense_amount, expense_desc)
	VALUES ($dog_id, '$expense_date', '$vendor_name','$amount', '$description');
	";
}

function update_dog_chipid($dog_id, $chipid){
	return 
	"
	UPDATE Dog
	SET microchip_id = '$chipid'
	WHERE dog_id = '$dog_id'
	";
}

function newest_dogid(){
	return
	"
	SELECT MAX(dog_id) AS dog_id FROM Dog
	";
}

function update_app_status($app_id, $status){
	return
	"
	UPDATE Application
	      SET status = '$status'
	      WHERE app_id = '$app_id'
	";
}

function insert_rejapp($app_id){
	return
	"
	INSERT INTO RejectedApplication (app_id) VALUES ('$app_id')
	";
}

function review_pending_app(){
	return
	"
	SELECT B.app_id, A.fname, A.lname, B.co_fname, B.co_lname, B.status
	FROM Adopter AS A
	INNER JOIN Application AS B
	ON A.email = B.adopter_email
	WHERE B.status ='pending'
	";
}

function search_adopter($email){
	return
	"
	SELECT email, fname, lname, phone_num, street, city, state, zip_code
	FROM Adopter
	WHERE email = '$email'
	";
}

function insert_adopter($email, $fname, $phonenum, $street, $city, $state, $zipcode){
	return
	"
	INSERT INTO Adopter (email, fname, lname, phone_num, street, city, state, zip_code)
	VALUES ('$email','$fname','$lname','$phonenum','$street','$city','$state','$zipcode')
	";
}

function insert_app($co_fname, $co_lname, $app_date, $email){
	return
	"
	INSERT INTO Application (co_fname, co_lname, app_date, adopter_email, status)
	VALUES ('$co_fname','$co_lname', '$app_date','$email', 'pending')
	";
}

function get_app($email){
	return
	"
	SELECT email, fname, lname, phone_num, street, city, state, zip_code
 	FROM Adopter
	WHERE email = '$email'
	";
}

function get_newest_app(){
	return
	"
	SELECT *
	FROM
		Application aa
	INNER JOIN 
		(SELECT COUNT(*) AS newest_id 
		FROM 
			Application a) tmp
	ON
		tmp.newest_id = aa.app_id
	INNER JOIN
		Adopter ad
	ON
		ad.email = aa.adopter_email
	";
}

function search_adopter_and_coapplicant_bylname($last_name){
	return
	"
	SELECT B.app_id, B.app_date, A.fname, A.lname, B.adopter_email, A.phone_num, A.street, A.city, A.state, A.zip_code, B.co_fname, B.co_lname, B.status
	FROM Adopter AS A
	INNER JOIN Application AS B
	ON A.email = B.adopter_email
	WHERE A.lname LIKE '%$last_name%'
	OR B.co_lname LIKE '%$last_name%'
	AND B.status = 'approved'
	";
}

function newest_app_byemail($adopter_email){
	/*by Adopter's email, get his newest Application that's approved and unadopted*/
	return
	"
	SELECT A.app_id, A.status, A.adopter_email, tmp.latest_approved_date
	FROM Application A
	INNER JOIN
	    (SELECT B.adopter_email, MAX(B.app_date) AS latest_approved_date
	    FROM (
            SELECT *
            FROM Application a3
            WHERE NOT EXISTS (
                SELECT 1
                FROM
                    ApprovedApplication aa3
                WHERE
                    aa3.app_id = a3.app_id
            ) ) B
	    WHERE B.adopter_email = '$adopter_email'
	    And B.status = 'approved'
	    GROUP BY B.adopter_email) tmp
	ON A.adopter_email = tmp.adopter_email
    WHERE
    A.app_date = tmp.latest_approved_date
    AND
   	A.status = 'approved'
	";
}

function adoption_fee_bydog($dog_id){
	return
	"
	SELECT D.dog_id,
		 IF(D.surrender_by_animal_control=1, total_exp* 0.15, total_exp* 1.15) AS adoption_fee
	FROM Dog D
	INNER JOIN
	(SELECT
	 	EE.dog_id,
	 	SUM(expense_amount) AS total_exp
	    FROM Expense EE
	    WHERE
	    EE.dog_id = $dog_id) AggE
	ON D.dog_id = AggE.dog_id
	";
}

function insert_approvedapp($appid, $dog_id, $fee, $date){
	return
	"
	INSERT INTO ApprovedApplication (app_id, dog_id, adoption_fee, adoption_date)
	VALUES ('$appid','$dog_id','$fee','$date')
	";
}

?>