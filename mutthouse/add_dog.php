<?php
include('lib/common.php');
include('lib/show_queries.php');
include('sql/query.php');

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

 if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $fail = 0;
    $fail_reason = "";
    if ( isset($_POST['dog_chipid']) && !empty($_POST['dog_chipid']) ) {
        if (!ctype_digit($_POST['dog_chipid'])){
            $fail = 1;
            $fail_reason = "microchip id has to be numeric";
        } 
    }
    if ( isset($_POST['dog_age']) && !empty($_POST['dog_age']) ) {
        try {
            $dog_age = (float)$_POST['dog_age'];
        } catch(Exception $e) {
            $fail = 1;
            $fail_reason = "age has to be float";
        }
    }
    if ( isset($_POST['dog_breed']) && !empty($_POST['dog_breed']) ){
        if ( (count($_POST['dog_breed'])>1) and ((in_array('Unknown', $_POST['dog_breed'])) or (in_array('Mixed', $_POST['dog_breed']))) ){
            $fail = 1;
            $fail_reason = "selected multiple breed names cannot include Mixed/Unknown";
        }
        if ( (count($_POST['dog_breed'])==1) && !empty($_POST['dog_name'])){
            if ( (in_array('Bulldog', $_POST['dog_breed'])) && ($_POST['dog_name']==strtolower('uga')) ){
                $fail = 1;
                $fail_reason = "bulldog can't have name uga";
            }
        }
    }
    if ($fail == 1){
        array_push($error_msg,  "Failed to add new dog: " . $fail_reason);
    } else {
        // add dog
        if (empty($_POST['dog_chipid'])){
            $query = insert_dog_nullchip((int)$_POST['dog_alteration'], 
                                            $_POST['dog_sex'], 
                                            $_POST['dog_description'],
                                            $_POST['dog_name'], 
                                            (float)$_POST['dog_age'], 
                                            $_POST['dog_surrender_date'], 
                                            (int)$_POST['dog_animal_control'], 
                                            $_POST['dog_surrender_reason'], 
                                            $_SESSION['email']);
            mysqli_query($db, $query);
        } else {
            $query = insert_dog((int)$_POST['dog_alteration'], 
                                $_POST['dog_sex'], 
                                $_POST['dog_description'], 
                                $_POST['dog_chipid'], 
                                $_POST['dog_name'], 
                                (float)$_POST['dog_age'], 
                                $_POST['dog_surrender_date'], 
                                (int)$_POST['dog_animal_control'], 
                                $_POST['dog_surrender_reason'], 
                                $_SESSION['email']);
            mysqli_query($db, $query);
        }
        /* debug
        array_push($query_msg,  $_POST['dog_alteration'] . ' '.  $_POST['dog_sex'] . ' '.$_POST['dog_description'] . ' '.$_POST['dog_chipid'] .' '. 
            $_POST['dog_name'] . ' '.$_POST['dog_age'] . ' '.$_POST['dog_surrender_date'] . ' '.$_POST['dog_animal_control'] .' '. 
            $_POST['dog_surrender_reason'] .' '. $_SESSION['email']);
        */
        $query = newest_dogid();
        $result = mysqli_query($db, $query);
        if ( !empty($result) && (mysqli_num_rows($result) > 0) ){
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                $_SESSION["dog_id"] = $row['dog_id'];
            }
        }
        // add dog breed
        foreach ($_POST['dog_breed'] as $breed_name){
            mysqli_query($db, insert_dog_breed($_SESSION["dog_id"], $breed_name));
        }
        header('Location: dog_detail.php');
    }
 }

?>


<?php include("lib/header.php"); ?>
</head>

<body>
    <div id="main_container">
        <div class="center_content">
            <?php include("lib/menu.php"); ?>
                <div class="center_left">
                    <div class='profile_section'>
                        <div class='title_name'>Enter New Dog</div>
                        <form action="add_dog.php" method="post">
                            <table class="wide_table">
                                <tr>
                                    <td class=heading>Name</td>
                                    <td><input type='text' name='dog_name' required></td>
                                </tr>
                                <tr>
                                    <td class=heading>Microchip ID</td>
                                    <td><input type='text' name='dog_chipid'></td>
                                </tr>
                                <tr>
                                    <td class=heading>Sex</td>
                                    <td>
                                        <select name='dog_sex' required>
                                            <option value = ''></option>
                                            <option value = 'unknown'> unknown </option>
                                            <option value = 'male'> male </option>
                                            <option value = 'female'> female </option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class=heading>Age(Year)</td>
                                    <td><input type='text' name='dog_age' required></td>
                                </tr>
                                <tr>
                                    <td class=heading>Alteration</td>
                                    <td>
                                        <select name='dog_alteration' required>
                                            <option value = ''></option>
                                            <option value = 0> unaltered </option>
                                            <option value = 1> altered </option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class=heading>Surrender Date</td>
                                    <td><input type='date' name='dog_surrender_date' max='<?php echo date('Y-m-d'); ?>' required></td>
                                </tr>
                                <tr>
                                    <td class=heading>Surrendered by animal control</td>
                                    <td>
                                        <select name='dog_animal_control' required>
                                            <option value = ''></option>
                                            <option value = 0> no </option>
                                            <option value = 1> yes </option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td class=heading>Surrender Reason</td>
                                    <td><input type='text' name='dog_surrender_reason' required></td>
                                </tr>
                                <tr>
                                    <td class=heading>Description</td>
                                    <td><input type='text' name='dog_description' required></td>
                                </tr>
                                <tr>
                                    <td class=heading>Choose one or more breed names</td>
                                    <td>
                                        <select name='dog_breed[]' multiple='multiple' size='20' required>
                                            <?php
                                            $query = get_query_breedlist();
                                            $result = mysqli_query($db, $query);
                                            if ( !empty($result) && (mysqli_num_rows($result) > 0) ){
                                                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                                    echo "<option value = '{$row['breed_name']}'> {$row['breed_name']} </option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type='submit' value='SUBMIT'/></td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            <?php include("lib/error.php"); ?>
            <div class="clear"></div> 
        </div>
        <?php include("lib/footer.php"); ?>
    </div>  
</body>
</html>