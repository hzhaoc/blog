<?php

include('lib/common.php');
include('lib/show_queries.php');
include('sql/query.php');

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
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
						<div class="subtitle">Search Approved Application by co(applicant) last name</div>
						<form name="searchform" action="add_adoption.php" method="POST">
							<table>
								<tr>
									<td class="item_label">(Co)Applicant Last Name</td>
									<td><input type="text" name="last_name"  value="<?php if ($row1['email']) { print $row1['email']; } ?>" /></td>
									<td><input type="submit" name="search" value="Search">
								</tr>
							</table>
							<table class='wide_table'>
								<tr>;
									<td class="heading">Applicant</td>
									<td class="heading">Co-applicant</td>
									<td class="heading">Email</td>
									<td class="heading">Phone Number</td>
									<td class="heading">Street</td>
									<td class="heading">City</td>
									<td class="heading">State</td>
									<td class="heading">Zip Code</td>
									<td class="heading">Add Adoption</td>
								</tr>
								<tr>
									<?php
									if (isset($_POST['search'])) {
										$last_name = mysqli_real_escape_string($db, $_POST['last_name']);
										$query1 = search_adopter_and_coapplicant_bylname($last_name);
										$result1 = mysqli_query($db, $query1);
										if (!is_bool($result1) && (mysqli_num_rows($result1) > 0) ) {
											while($row1 = mysqli_fetch_array($result1, MYSQLI_ASSOC)){
												print '<tr>';
													print '<td>' . $row1['fname'] . ' ' . $row1['lname'] . '</td>';
													print '<td>' . $row1['co_fname'] . ' ' . $row1['co_lname'] . '</td>';
													print '<td>' . $row1['adopter_email'] . '</td>';
													print '<td>' . $row1['phone_num'] . '</td>';
													print '<td>' . $row1['street'] . '</td>';
													print '<td>' . $row1['city'] . '</td>';
													print '<td>' . $row1['state'] . '</td>';
													print '<td>' . $row1['zip_code'] . '</td>';
													print '<td><a href="Add_Adoption.php?select=' . urlencode($row1['adopter_email']) . '">Select</a></td>';
												print '</tr>';						
											}
										} else {
											array_push($error_msg, "Adopter doesn't exist, please add adopter information.");
										}
									}
									?>
								</tr>
							</table>
						</form>
					</div>

					<div class="profile_section">
						<div class="subtitle">Most Recent Approved Outstanding Application</div>
						<form action="add_adoption.php" method="POST">
						<?php
						print '<table>';
							print '<tr>';
								print "<td class='heading'>Application ID</td>";
								print "<td class='heading'>Email</td>";
								print "<td class='heading'>Status</td>";
								print "<td class='heading'>Application Date</td>";
							print '</tr>';
						// display most recent applicaiton of this adopter
						if (!empty($_GET['select'])) {
						  	$adopter_email = mysqli_real_escape_string($db, $_GET['select']);
							$query2 = newest_app_byemail($adopter_email);
							$result2 = mysqli_query($db, $query2);
							if (!is_bool($result2) && (mysqli_num_rows($result2) > 0) ) {
								$row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
								print '<tr>';
									print "<td><input type='hidden' name='app_id' value={$row2['app_id']}>". $row2['app_id'] . "</td>";
									print '<td>' . $row2['adopter_email'] . '</td>';
									print '<td>' . $row2['status'] . '</td>';
									print '<td>' . $row2['latest_approved_date'] . '</td>';
								print '</tr>';
							}
						print '</table>';
						// we have the wanted newest application of this adopter, and remember we have kept wanted dog, 
						// now display computed adoption fee
						$query_for_adoptionfee = adoption_fee_bydog($_SESSION["dog_id"]);
						$result_adoptionfee = mysqli_query($db, $query_for_adoptionfee);
						if (!is_bool($result_adoptionfee) && (mysqli_num_rows($result_adoptionfee) > 0) ){
							$row_adoption_fee = mysqli_fetch_array($result_adoptionfee, MYSQLI_ASSOC);
							$adoption_fee = (int)$row_adoption_fee['adoption_fee'];
						} else {
							$adoption_fee = 0;
						}
						?>
						<table>
							<tr>
								<td class="item_label">Adoption Fee</td>
								<td><input type='hidden' name='app_fee' value= <?php print $adoption_fee; ?> > <?php print '$ ' . $adoption_fee; } ?></td>
							</tr>
							<tr>
								<td class="item_label">Adoption Date</td>
								<td><input type="date" name="adopt_date" max="<?php echo date("Y-m-d"); ?>" required="required"></td>
								<td><input type="submit" name="add" value="Complete Adoption">
							</tr>
						</table>
						</form>
					</div>

					<div class="profile_section">
						<?php
						print "<div class='subtitle'>Submitted Adoption Display</div>";
						if (isset($_POST['add'])) { // insert approved application
							$adopt_date = mysqli_real_escape_string($db, $_POST['adopt_date']);
							$app_id = mysqli_real_escape_string($db, $_POST['app_id']);
							$app_fee = mysqli_real_escape_string($db, $_POST['app_fee']);
							if ((!empty($app_id)) && (!empty($app_fee)) ){
								$query_insert_aprapp = insert_approvedapp($app_id, $_SESSION["dog_id"], $app_fee, $adopt_date);
								mysqli_query($db, $query_insert_aprapp);
								print '<table>';
									print '<tr>';
										print "<td class='heading'>Adoption Date</td>";
										print "<td class='heading'>Application ID</td>";
										print "<td class='heading'>Adoption Fee</td>";
										print "<td class='heading'>Adopted Dog ID</td>";
									print '</tr>';
									print '<tr>';
										print "<td>$adopt_date</td>";
										print "<td>$app_id</td>";
										print "<td>\$$app_fee</td>";
										print "<td>{$_SESSION['dog_id']}</td>";
									print '</tr>';
								print '</table>';							
							}
						}
						?>
					</div>
				</div>
                <?php include("lib/error.php"); ?>
				<div class="clear"></div>
			</div>
           	<?php include("lib/footer.php"); ?>
		</div>
	</body>
</html>
