<?php
include('lib/common.php');
include('lib/show_queries.php');
include('sql/query.php');

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

if (isset($_POST['search'])) {
	$email = mysqli_real_escape_string($db, $_POST['email']);
	$query = search_adopter($email);
	$result = mysqli_query($db, $query);
	if (!is_bool($result) && (mysqli_num_rows($result) > 0) ) {
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	} else {
			array_push($error_msg, "Adopter doesn't exist, please add adopter information.");
	}
}

$fail = 1;
if (isset($_POST['submit'])) {
	$email = mysqli_real_escape_string($db, $_POST['email']);
	$fname = mysqli_real_escape_string($db, $_POST['fname']);
	$lname = mysqli_real_escape_string($db, $_POST['lname']);
	$phonenum = mysqli_real_escape_string($db, $_POST['phonenum']);
	$street = mysqli_real_escape_string($db, $_POST['street']);
	$city = mysqli_real_escape_string($db, $_POST['city']);
	$state = mysqli_real_escape_string($db, $_POST['state']);
	$zipcode = mysqli_real_escape_string($db, $_POST['zipcode']);
	$co_fname = mysqli_real_escape_string($db, $_POST['co_fname']);
	$co_lname = mysqli_real_escape_string($db, $_POST['co_lname']);
	$app_date = mysqli_real_escape_string($db, $_POST['appdate']);

	if( !empty($email) && !empty($fname) && !empty($lname) && !empty($phonenum) && !empty($street) && !empty($city) && !empty($state) && !empty($zipcode))
	{
		if( !empty($app_date) && !empty($email) ){
			$insert_adopter = insert_adopter($email, $fname, $phonenum, $street, $city, $state, $zipcode);
			mysqli_query($db, $insert_adopter); // add new applicant(adopter)
			$insert_app = insert_app($co_fname, $co_lname, $app_date, $email); // add new application
			mysqli_query($db, $insert_app);			
			$fail = 0;
		} else {
			array_push($error_msg,  "Failed to add adopter contact: it's incomplete input");
		}
	} else {
		array_push($error_msg,  "Failed to add application: it's incomplete" );
	}

	if ($fail==0){
		$query_new_app_result = mysqli_query($db, get_newest_app());	
	    if ( !empty($query_new_app_result) && (mysqli_num_rows($query_new_app_result) > 0) ){
	        $new_app = mysqli_fetch_array($query_new_app_result, MYSQLI_ASSOC);
	    }
	}
}



?>

<?php include("lib/header.php"); ?>
		<title>Add Adoption Application</title>
	</head>

	<body>
    	<div id="main_container">
            <?php include("lib/menu.php"); ?>
			<div class="center_content">
				<div class="center_left">
					<div class="profile_section">
						<div class="subtitle">Adopter Contact Information</div>							
						<form action="add_adoption_application.php" method="POST">
						<table>
							<tr>
								<td class="item_label">Email</td>
								<td><input type="text" name="email" value="<?php if ($row['email']) { print $row['email']; } ?>"/></td>
								<td><input type="submit" name="search" value="Search"></td>
							</tr>
							<tr>
								<td class="item_label">First Name</td>
								<td><input type="text" name="fname" value="<?php if ($row['fname']) { print $row['fname']; } ?>"/></td>
							</tr>
							<tr>
								<td class="item_label">Last Name</td>
								<td><input type="text" name="lname" value="<?php if ($row['lname']) { print $row['lname']; } ?>"/></td>
							</tr>
							<tr>
								<td class="item_label">Phone Number</td>
								<td><input type="text" name="phonenum" value="<?php if ($row['phone_num']) { print $row['phone_num']; } ?>"/></td>
							</tr>
							<tr>
								<td class="item_label">Street</td>
								<td><input type="text" name="street" value="<?php if ($row['street']) { print $row['street']; } ?>"/></td>
							</tr>
							<tr>
								<td class="item_label">City</td>
								<td><input type="text" name="city" value="<?php if ($row['city']) { print $row['city']; } ?>"/></td>
							</tr>
							<tr>
								<td class="item_label">State</td>
								<td><input type="text" name="state" value="<?php if ($row['state']) { print $row['state']; } ?>"/></td>
							</tr>
							<tr>
								<td class="item_label">Zip Code</td>
								<td><input type="text" name="zipcode" value="<?php if ($row['zip_code']) { print $row['zip_code']; } ?>"/></td>
							</tr>
						</table>
						<div class='subtitle'>Other Application Information</div>
						<table>
							<tr>
								<td class="item_label">Co-applicant First Name</td>
								<td><input type="text" name="co_fname" value="<?php if ($row['co_fname']) { print $row['co_fname']; } ?>" /></td>
							</tr>
							<tr>
								<td class="item_label">Co-applicant Last Name</td>
								<td><input type="text" name="co_lname" value="<?php if ($row['co_lname']) { print $row['co_lname']; } ?>" /></td>
							</tr>
							<tr>
								<td class="item_label">Date</td>
								<td><input type="date" name="appdate" max="<?php echo date("Y-m-d"); ?>"></td>
								<td><input type="submit" name="submit" value="Submit">
							</tr>
						</table>
						</form>						
					</div>

					<div class='profile_section'>
						<div class='subtitle'>If submitted sucessfully, display here</div>
							<table class='wide_table'>
								<tr>
									<td class="item_label">Application ID</td>
									<td><?php if ($fail==0) { print $new_app['app_id']; } ?></td>
								</tr>
								<tr>
									<td class="item_label">Application Date</td>
									<td><?php if ($fail==0) { print $new_app['app_date']; } ?></td>
								</tr>
								<tr>
									<td class="item_label">Applicant - First Name</td>
									<td><?php if ($fail==0) { print $new_app['fname']; }  ?></td>
								</tr>
								<tr>
									<td class="item_label">Applicant - Last Name</td>
									<td><?php if ($fail==0) { print $new_app['lname']; } ?></td>
								</tr>
								<tr>
									<td class="item_label">Phone Number</td>
									<td><?php if ($fail==0) { print $new_app['phone_num']; } ?></td>
								</tr>
								<tr>
									<td class="item_label">Street</td>
									<td><?php if ($fail==0) { print $new_app['street']; } ?></td>
								</tr>
								<tr>
									<td class="item_label">City</td>
									<td><?php if ($fail==0) { print $new_app['city']; } ?></td>
								</tr>
								<tr>
									<td class="item_label">State</td>
									<td><?php if ($fail==0) { print $new_app['state']; } ?></td>
								</tr>
								<tr>
									<td class="item_label">Zip Code</td>
									<td><?php if ($fail==0) { print $new_app['zip_code']; } ?></td>
								</tr>
								<tr>
									<td class="item_label">Co-applicant - First Name</td>
									<td><?php if ($fail==0) { print $new_app['co_fname']; } ?></td>
								</tr>
								<tr>
									<td class="item_label">Co-applicant - Last Name</td>
									<td><?php if ($fail==0) { print $new_app['co_lname']; } ?></td>
								</tr>
								<tr>
									<td class="item_label">Status</td>
									<td><?php if ($fail==0) { print $new_app['status']; } ?></td>
								</tr>
							</table>
					</div>
				</div>
                <?php include("lib/error.php"); ?>
				<div class="clear"></div>
			</div>
               <?php include("lib/footer.php"); ?>
		</div>
	</body>
</html>
