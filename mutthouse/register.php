<?php
include('lib/common.php');
include('sql/query.php');
if($showQueries){
  array_push($query_msg, "showQueries currently turned ON, to disable change to 'false' in lib/common.php");
}

//Note: known issue with _POST always empty using PHPStorm built-in web server: Use *AMP server instead
if( $_SERVER['REQUEST_METHOD'] == 'POST') {

    $Email = mysqli_real_escape_string($db, $_POST['email']);
    $Password = mysqli_real_escape_string($db, $_POST['password']);
    $Password2 = mysqli_real_escape_string($db, $_POST['password2']);
    $admin = mysqli_real_escape_string($db, $_POST['admin']);

    while (True){
        if ($Password != $Password2){
            array_push($error_msg,  "confirmed password and password don't match");
            break;
        }
        
        if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
            array_push($error_msg,  "Invalid email format");
            break;
        } 

        $query = get_query_register_validate($Email);
        $result = mysqli_query($db, $query);
        include('lib/show_queries.php');
        if (!empty($result) && (mysqli_num_rows($result) > 0) ) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if ($row['res']==1) {
                array_push($error_msg, "this email has been registered");
                break;
            }
        } else {
            array_push($query_msg, "Registration is successful, going back to login...");
            $query = insert_user($Email, $Password, $_POST['fname'], $_POST['lname'], $_POST['phone'], $_POST['start_date']);
            mysqli_query($db, $query);
            if ((int)$admin == 1){
                mysqli_query($db, insert_owner($Email));
            } else {
                mysqli_query($db, insert_volunteer($Email));
            }
            header(REFRESH_TIME . 'url=login.php');
        }
        break;
    } 
}
?>

<?php include("lib/header.php"); ?>
    </head>
    <body>
        <div id="main_container">
            <div id="header">
                <div class="logo">
                    <img src="img/header.png" style="opacity:0.6" border="0" alt="" title="Logo"/>
                </div>
            </div>

            <div class="center_content">
                    <div class="tall_box">
                        <form action="register.php" method="post" enctype="multipart/form-data">
                            <div class="title">Register</div>
                            <div class="login_form_row">
                                <label class="login_label">Email:</label>
                                <input type="text" name="email" class="login_input" required/>
                            </div>
                            <div class="login_form_row">
                                <label class="login_label">Password:</label>
                                <input type="password" name="password" class="login_input" required/>
                            </div>
                            <div class="login_form_row">
                                <label class="login_label">Confirm Password:</label>
                                <input type="password" name="password2" class="login_input" required/>
                            </div>
                            <div class="login_form_row">
                                <label class="login_label">Admin? </label>
                                <select name='admin' required>
                                    <option value = ''></option>
                                    <option value = 0> no </option>
                                    <option value = 1> yes </option>
                                </select>
                            </div>
                            <div class="login_form_row">
                                <label class="login_label">First Name:</label>
                                <input type="text" name="fname" class="login_input" required/>
                            </div>
                            <div class="login_form_row">
                                <label class="login_label">Last Name:</label>
                                <input type="text" name="lname" class="login_input" required/>
                            </div>
                            <div class="login_form_row">
                                <label class="login_label">Start Date:</label>
                                <input type='date' name='start_date' max='<?php echo date('Y-m-d'); ?>' required></td>
                            </div>
                            <div class="login_form_row">
                                <label class="login_label">Phone:</label>
                                <input type="text" name="phone" class="login_input" required/>
                            </div>
                            <div class="login_form_row">
                                <input type="submit" value="Submit"/>
                                <a href="login.php"><input type="button" value="Back to Login"/></a>
                            </div>
                        </form>
                    </div>
                <?php include("lib/error.php"); ?>
                <div class="clear"></div>
            </div>

            <?php include("lib/footer.php"); ?>
        </div>
    </body>
</html>