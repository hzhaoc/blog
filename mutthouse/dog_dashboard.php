<?php
include('lib/common.php');
include('sql/query.php');
if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}
?>


<?php include("lib/header.php"); ?>
<title>Dog Dashboard</title>
</head>

<body>
	<div id="main_container">
        <div class="center_content">
            <?php include("lib/menu.php"); ?>
            <div class="center_left">
                <div class="features">   
                    <div class="profile_section">
                        <table>
                            <tr>
                       			<td class="item_label">Available Spaces</td>
                       			<?php
                                $const = json_decode(file_get_contents('data/const.json'), true); // covert json to php assoc array
                                $query = get_query_availspots($const['capacity']);
                            	$result = mysqli_query($db, $query);
                                if ( !empty($result) && (mysqli_num_rows($result) > 0) ){
                                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                        if ($row['available_spots']>0){
                                            print "<td> {$row['available_spots']} </td>";
                                            print "<form action='add_dog.php'>";
                                            print "<td><input class='fancy_button' type='submit' value='Add Dog'/></td>";
                                            print "</form>";
                                        } else {
                                            print "<td> 0 </td>";
                                        }
                                    }
                                }
                                ?>
                   			</tr>						
                        </table>						
                   
                        <div class="subtitle">Dog List</div>
                        <table>
                            <form action="dog_dashboard.php" method="post">
                            <tr>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td> </td>
                                <td>
                       				<select name='filter' onchange='if(this.value != 0) {this.form.submit();}'>
                                        <option value = ""></option>
                            			<option value = "None">No Filter</option>
                            			<option value = "Adoptable">Adoptable</option>
                            			<option value = "Nonadoptable">Nonadoptable</option>
                        			</select>
                       			</td>
                            </tr>
                            </form>

                            <tr>
                                <th><a href="dog_dashboard.php?sort=name">Name</a></th>
                                <th><a href="dog_dashboard.php?sort=breed">Breed</a></th>
                                <th><a href="dog_dashboard.php?sort=sex">Sex</a></th>
                                <th><a href="dog_dashboard.php?sort=alteration">Alteration</a></th>
                                <th><a href="dog_dashboard.php?sort=age">Age/Years</a></th>
                                <th><a href="dog_dashboard.php?sort=adop">Adoptability</a></th>
                            </tr>							

                            <?php
                            $query = get_query_doglist();
                            if( $_SERVER['REQUEST_METHOD'] == 'POST'){
                                $_SESSION['filter_dog_adopt'] = $_POST['filter'];
                            }
                            if ( $_SERVER['REQUEST_METHOD'] == 'GET'){
                                $_SESSION['sort_dog_attr'] = $_GET['sort'];
                            }
                            if (isset($_SESSION['filter_dog_adopt']) && ($_SESSION['filter_dog_adopt'] != 'None') ) {
                                $query .= " where adoptability = '{$_SESSION['filter_dog_adopt']}' ";
                            }
                            if (isset($_SESSION['sort_dog_attr'])) {
                                $query .= $dogsort[$_SESSION['sort_dog_attr']];
                            }              
                            $result = mysqli_query($db, $query);
                            include('lib/show_queries.php');
                            if (!empty($result) && (mysqli_num_rows($result) > 0) ) {
            					while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                    print "<tr>";
                                    print "<form action='dog_detail.php' method='post'>";
                                    print "<td><input type='hidden' name='dog_id' value={$row['dog_id']}>{$row['name']} </td>";
                                    // print "<td>" . $row['name'] . "</td>";
                                    print "<td>" . $row['breed'] . "</td>";
                                    print "<td>" . $row['sex'] . "</td>";
                                    print "<td>" . $row['alteration'] . "</td>";
                                    $_age = round($row['age'], 2);
                                    print "<td>$_age</td>";
                                    print "<td>" . $row['adoptability'] . "</td>";
                                    print "<td><input class='fancy_button' type='submit' value='View This Dog'/></td>";
                                    print "</form>";
            						print "</tr>";
            					}
                            } else {array_push($error_msg,  "No dogs ...<br>" . __FILE__ ." line:". __LINE__ );}
    						?>
                        </table>						
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
