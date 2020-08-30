<?php
include('lib/common.php');
include('lib/show_queries.php');
include('sql/query.php');
include('lib/src.php');
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

// from dog_dashboard.php
if ( isset($_POST['dog_id']) && !empty($_POST['dog_id']) ){
    $_SESSION["dog_id"] = $_POST['dog_id'];
}

// update dog breed
if ( isset($_POST['dog_breed']) && !empty($_POST['dog_breed']) ){
    if ( ($_SESSION['pre_dog_breed']=='Mixed') && (count($_POST['dog_breed'])==1) ){
        array_push($error_msg,  "Failed to update dog breed: choose more than one breed names to update 'mixed'...");
    } elseif ( (count($_POST['dog_breed'])>1) and ((in_array('Unknown', $_POST['dog_breed'])) or (in_array('Mixed', $_POST['dog_breed']))) ){
        array_push($error_msg,  "Failed to update dog breed: multiple breed names must exclude mixed/unknown");
    } else {
        mysqli_query($db, delete_dog_breed($_SESSION["dog_id"]));
        foreach ($_POST['dog_breed'] as $breed_name){
            mysqli_query($db, insert_dog_breed($_SESSION["dog_id"], $breed_name));
        }
    }
}

// update dog chid id
if ( isset($_POST['dog_chipid']) && !empty($_POST['dog_chipid']) ) {
    if (!ctype_digit($_POST['dog_chipid'])){
        array_push($error_msg,  "Failed to update dog microchip id: microchip id must be numeric");
    } else {
        mysqli_query($db, update_dog_chipid($_SESSION['dog_id'], $_POST['dog_chipid'])); // if chipid is not unique, no query error message will be output, but it won't be update in database
    }
}

// update dog alteration
if ( isset($_POST['dog_alteration']) && !empty($_POST['dog_alteration']) ) {
    mysqli_query($db, update_dog_alteration($_SESSION['dog_id'], $_POST['dog_alteration']));
}

// update dog sex
if ( isset($_POST['dog_sex']) && !empty($_POST['dog_sex']) ) {
    mysqli_query($db, update_dog_sex($_SESSION['dog_id'], $_POST['dog_sex']));
}

// insert expense
/*
if (    isset($_POST['expense_date']) && !empty($_POST['expense_date']) && 
        isset($_POST['vendor_name']) && !empty($_POST['vendor_name']) && 
        isset($_POST['expense_amount']) && !empty($_POST['expense_amount']) &&
        isset($_POST['expense_desc']) && !empty($_POST['expense_desc']) ){
    $exp_check = 1;
    if (!validateDate($_POST['expense_date'], 'Y-m-d')){
        $exp_check = 0;
        array_push($query_msg,  "Failed to add dog expense: enter valid date formated as YYYY-mm-dd");
    }
    if (!ctype_digit($_POST['expense_amount'])){
        $exp_check = 0;
        array_push($query_msg,  "Failed to add dog expense: expense amount contain characters");
    }
    if (strtotime($_POST['expense_date']) < strtotime($_SESSION['surrender_date'])){
        $exp_check = 0;
        array_push($query_msg,  "Failed to add dog expense: Only expenses after the dog has been surrendered, and before it was adopted will be tracked");
    }
    if ($exp_check == 1){
        mysqli_query($db, insert_dog_exp($_SESSION['dog_id'], $_POST['expense_date'], $_POST['vendor_name'], $_POST['expense_amount'], $_POST['expense_desc']));
    }
}
*/
if (isset($_POST['expense_date']) && !empty($_POST['expense_date'])){
    mysqli_query($db, insert_dog_exp($_SESSION['dog_id'], $_POST['expense_date'], $_POST['vendor_name'], $_POST['expense_amount'], $_POST['expense_desc']));
}

if (!isset($_SESSION['dog_id'])) {
    header('Location: dog_dashboard.php');
}

?>


<?php include("lib/header.php"); ?>
</head>

<body>
    <div id="main_container">
        <div class="center_content">
            <?php include("lib/menu.php"); ?>
            <form action="dog_detail.php" method="post">
                <div class="center_left">
                    <div class='profile_section'>
                        <table class="wide_table">
                            <?php
                                $query = get_query_dog($_SESSION["dog_id"]);
                                $result = mysqli_query($db, $query); 
                                if ( !empty($result) && (mysqli_num_rows($result) > 0) ) {
                                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                        print "<tr>";
                                            print "<td class='title_name'>{$row['name']}</td>";
                                        print "</tr>";
                                        print "<tr>";
                                            print "<td class='heading'>ID</td>";
                                            print "<td>{$row['dog_id']}</td>";
                                        print "</tr>";
                                        print "<tr>";
                                            print "<td class='heading'>Microchip ID</td>";
                                            if ($row['microchip_id'] == NULL){
                                                print "<td><input type='text' name='dog_chipid'/></td>"; // update chipid
                                            } else {
                                                print "<td>{$row['microchip_id']}</td>";                                            
                                            }
                                        print "</tr>";
                                        print "<tr>";
                                            print "<td class='heading'>Sex</td>";
                                            if ($row['sex'] == 'unknown'){ // update sex
                                                echo "<td>";
                                                echo "<select name='dog_sex'>";
                                                    echo "<option value = 'unknown'> unknown </option>";
                                                    echo "<option value = 'male'> male </option>";
                                                    echo "<option value = 'female'> female </option>";
                                                echo "</select>";
                                                echo "</td>";
                                            } else {
                                                print "<td>{$row['sex']}</td>";
                                            }
                                        print "</tr>";
                                        print "<tr>";
                                            print "<td class='heading'>Age(Years)</td>";
                                            print "<td>".round($row['age'], 2)."</td>";
                                        print "</tr>";
                                        print "<tr>";
                                            print "<td class='heading'>Alteration</td>";
                                            if ($row['alteration'] == 0) { // update alteration
                                                echo "<td>";
                                                echo "<select name='dog_alteration'>";
                                                    echo "<option value = 0> unaltered </option>";
                                                    echo "<option value = 1> altered </option>";
                                                echo "</select>";
                                                echo "</td>";
                                            } else {
                                                print "<td>".array(0=>'unaltered', 1=>'altered')[$row['alteration']]."</td>";
                                            }
                                        print "</tr>";
                                        print "<tr>";
                                            if ((!isset($_SESSION['surrender_date'])) || empty($_SESSION['surrender_date'])){
                                                $_SESSION['surrender_date'] = $row['surrender_date'];
                                            }
                                            print "<td class='heading'>Surrender Date</td>";
                                            print "<td>{$row['surrender_date']}</td>";
                                        print "</tr>";
                                        print "<tr>";
                                            print "<td class='heading'>Animal Control</td>";
                                            print "<td>".array(1=>'yes', 0=>'no')[$row['surrender_by_animal_control']]."</td>";
                                        print "</tr>";
                                        print "<tr>";
                                            print "<td class='heading'>Surrender Reason</td>";
                                            print "<td>{$row['surrender_reason']}</td>";
                                        print "</tr>";
                                        print "<tr>";
                                            print "<td class='heading'>Description</td>";
                                            print "<td>{$row['description']}</td>";
                                        print "</tr>";
                                        print "<tr>";
                                            print "<td class='heading'>Tracking Volunteer/Owner</td>";
                                            print "<td>{$row['fname']} {$row['lname']}</td>";
                                        print "</tr>";
                                        print "<tr>";
                                            print "<td class='heading'>Breed</td>";
                                            print "<td>{$row['breed']}</td>";
                                        print "</tr>";
                                        print "<tr>";
                                            if ( ($row['breed'] == 'Unknown') or ($row['breed'] == 'Mixed') ){
                                                $_SESSION["pre_dog_breed"] = $row['breed'];
                                                print "<td class='heading'>Choose ".array('Unknown'=>'one or more', 'Mixed'=>'more than one')[$row['breed']]." actual breed names to update: </td>";
                                                echo "<td>";
                                                echo "<select name='dog_breed[]' multiple='multiple' size='20'>";
                                                $query = get_query_breedlist();
                                                $result = mysqli_query($db, $query);
                                                if ( !empty($result) && (mysqli_num_rows($result) > 0) ){
                                                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                                        echo "<option value = '{$row['breed_name']}'> {$row['breed_name']} </option>";
                                                    }
                                                }
                                                echo "</select>";
                                                echo "</td>";
                                            }
                                        print "</tr>";
                                    }
                                }          
                            ?>
                        </table>
                    </div>
                    <div class='profile_section'>
                        <div class='subtitle'>Expenses</div>
                        <table class="wide_table">
                            <tr>
                                <td class='heading'>Expense Date</td>
                                <td class='heading'>Vendor Name</td>
                                <td class='heading'>Expense Amount</td>
                                <td class='heading'>Expense Description</td>
                            </tr>
                            <?php
                                $query = get_query_dog_exp($_SESSION["dog_id"]);
                                $result = mysqli_query($db, $query); 
                                if ( !empty($result) && (mysqli_num_rows($result) > 0) ) {
                                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                        print "<tr>";
                                            print "<td class='wide_table_cell'>{$row['expense_date']}</td>";
                                            print "<td>{$row['vendor_name']}</td>";
                                            print "<td> \$".round($row['expense_amount'], 2)."</td>";
                                            print "<td>{$row['expense_desc']}</td>";
                                        print "</tr>";
                                    }
                                }
                                if ( isset($_GET['newexp']) && !empty($_GET['newexp']) ){
                                    if ($_GET['newexp'] == 1){
                                        print "<tr>";
                                            print "<td class='wide_table_cell'>" .
                                                  "<input type='date' name='expense_date' " .
                                                  "min={$_SESSION['surrender_date']} " .
                                                  "max=" . date('Y-m-d') ." required>" .
                                                  "</td>";
                                            print "<td><input type='text' name='vendor_name' required></td>";
                                            print "<td><input type='text' name='expense_amount' required></td>";
                                            print "<td><input type='text' name='expense_desc'/></td>";
                                        print "</tr>";                                         
                                    }
                                }
                                print "<tr>";
                                    print "<td><a href='dog_detail.php?newexp=". 1 ."'>New</a></td>";
                                print "</tr>";
                            ?>
                        </table>
                    </div>
                </div>
                <div class='center_left'>
                    <div class='profile_section'>
                        <table>
                            <tr>
                                <td><input type='submit' value='SAVE'/></td>
                                <?php
                                $isadoptable = false;
                                $query = get_query_dog_adpotability($_SESSION['dog_id']);
                                $result = mysqli_query($db, $query);
                                if (!empty($result) && (mysqli_num_rows($result) > 0) ){
                                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                        $isadoptable = True;
                                    }
                                }
                                if ($isadoptable and $isonwer){ // $isonwer is from lib/menu.php
                                    print "<td><a href='add_adoption.php'><input type='button' value='Add Adoption'/></a></td>";
                                }
                                ?>
                            </tr>
                        </table>
                    </div>
                </div>
            </form>
            <?php include("lib/error.php"); ?>
            <div class="clear"></div> 
        </div>
        <?php include("lib/footer.php"); ?>
    </div>  
</body>
</html>