<?php
include('lib/common.php');
include('lib/show_queries.php');
include('sql/query.php');

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

if (!empty($_GET['approve'])) {
	$appid = mysqli_real_escape_string($db, $_GET['approve']);
	$query = update_app_status($appid, 'approved');
	$result = mysqli_query($db, $query);
    if (mysqli_affected_rows($db) == -1) {
        array_push($error_msg,  "DELETE ERROR: cancel request...<br>" . __FILE__ ." line:". __LINE__ );
	}
}

if (!empty($_GET['reject'])) {
	$appid = mysqli_real_escape_string($db, $_GET['reject']);
	$query = update_app_status($appid, 'rejected');
	$result = mysqli_query($db, $query);
	$query2 = insert_rejapp($appid);
	$result2 = mysqli_query($db, $query2);
    if (mysqli_affected_rows($db) == -1) {
        array_push($error_msg,  "DELETE ERROR: cancel request...<br>" . __FILE__ ." line:". __LINE__ );
	}
}

?>

<?php include("lib/header.php"); ?>
	<title>Review Adoption Application</title>
	</head>

	<body>
		<div id="main_container">
    <?php include("lib/menu.php"); ?>
			<div class="center_content">
				<div class="center_left">
					<div class="title_name"><?php print $user_name; ?></div>
					<div class="features">
						<div class="profile_section">
							<div class="subtitle">Review Adoption Application</div>
							<?php
                                print '<table>';
	                                print '<tr>';
	                                print '<td class="heading">Application ID</td>';
	                                print '<td class="heading">Applicant</td>';
	                                print '<td class="heading">Co-applicant</td>';
	                                print '<td class="heading">Current Status</td>';
									print '<td class="heading">Approve</td>';
									print '<td class="heading">Reject</td>';
	                                print '</tr>';
                                $query = review_pending_app();
                                $result = mysqli_query($db, $query);
                            	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                while ($row){
                                    print '<tr>';
                                        print '<td>' . $row['app_id']. '</td>';
                                        print '<td>' . $row['fname'] . ' ' . $row['lname'] . '</td>';
										print '<td>' . $row['co_fname'] . ' ' . $row['co_lname'] . '</td>';
                                        print '<td>' . $row['status'] . '</td>';
                                        print '<td><a href="review_application.php?approve=' . urlencode($row['app_id']) . '">Approved</a></td>';
										print '<td><a href="review_application.php?reject=' . urlencode($row['app_id']) . '">Rejected</a></td>';
                                    print '</tr>';
                                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                                }
                                print '</table>';
							?>
						</div>
					 </div>
				</div>
                <?php include("lib/error.php"); ?>
				<div class="clear"></div>
			</div>
               <?php include("lib/footer.php"); ?>
		</div>
	</body>
</html>
