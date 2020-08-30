
<div id="header">
 <div class="logo"><img src="img/header.png" style="opacity:0.6" border="0" alt="" title="MoHouseLogo"/></div>
</div>

<div class="nav_bar">
	<ul>    
     <li><a href="dog_dashboard.php" <?php if($current_filename=='dog_dashboard.php') echo "class='active'"; ?>>Dog Dashboard</a></li>
     <li><a href="add_adoption_application.php" <?php if($current_filename=='add_adoption_application.php') echo "class='active'"; ?>>Add Adoption Application</a></li>                       
     
     <?php
     /* If current user is owner, link [add adoption application] is visible. */
     $isonwer = false;
     $query = get_query_isonwer($_SESSION['email']);
     $result = mysqli_query($db, $query);
     include('lib/show_queries.php');
     if (!empty($result) && (mysqli_num_rows($result) > 0) ){
          $isonwer = true;
          print "<li><a href='review_application.php' ";
          if($current_filename=='review_application.php') echo "class='active'"; 
          print ">Review Application</a></li>";

     }

     ?>
     
     
     <li><a href="logout.php" ><span class='glyphicon glyphicon-log-out'></span> Log Out</a></li>              
	</ul>
</div>


<?php
/* If current user is owner, reports menu is visible. */
if ($isonwer == true ){

     print "<div class='nav_bar'>";
          print "<ul>";
               print "<li><a href='rpt_animal_control.php' ";
               if($current_filename=='rpt_animal_control.php') echo "class='active'"; 
               print ">Animal Control Report</a></li>";

               print "<li><a href='rpt_monthly_adoption.php' ";
               if($current_filename=='rpt_monthly_adoption.php') echo "class='active'"; 
               print ">Monthly Adoption Report</a></li>";

               print "<li><a href='rpt_expense_analysis.php' ";
               if($current_filename=='rpt_expense_analysis.php') echo "class='active'"; 
               print ">Expense Analysis Report</a></li>";

               print "<li><a href='rpt_volunteer_lookup.php' ";
               if($current_filename=='rpt_volunteer_lookup.php') echo "class='active'"; 
               print ">Volunteer Lookup Report</a></li>";

          print "</ul>";
     print "</div>";

}


?>