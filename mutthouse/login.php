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

    if (empty($Email)) {
            array_push($error_msg,  "No email address.");
    }

    if (empty($Password)) {
            array_push($error_msg,  "No password.");
    }
    
    if ( !empty($Email) && !empty($Password) )   { 
        $query = get_query_login($Email, $Password);
        $result = mysqli_query($db, $query);
        include('lib/show_queries.php');
        
        if (!empty($result) && (mysqli_num_rows($result) > 0) ) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            
            if($showQueries){
                array_push($query_msg, "Plaintext entered password: ". $Password);
            }
            
            if ($row['res']==1) {
                array_push($query_msg, "Password is Valid! ");
                $_SESSION['email'] = $Email;
                array_push($query_msg, "logging in... ");
                header(REFRESH_TIME . 'url=dog_dashboard.php');   
            }
            
        } else {
            array_push($error_msg, "wrong username or password");
        }
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
            <div class="text_box">
                <form action="login.php" method="post" enctype="multipart/form-data">
                    <div class="title">Login</div>
                    <div class="login_form_row">
                        <label class="login_label">Email:</label>
                        <input type="text" name="email" class="login_input" value="mo@burdell.com"/>
                    </div>
                    <div class="login_form_row">
                        <label class="login_label">Password:</label>
                        <input type="password" name="password" class="login_input" value="mo"/>
                    </div>
                    <input type="image" src="img/login.png" class="login"/>
                </form>
                <a href="register.php">
                    <input type="image" src="img/register.png" class="login"/>
                </a>
            </div>

                <?php include("lib/error.php"); ?>
                <div class="clear"></div>
            </div>

            <?php include("lib/footer.php"); ?>
        </div>
    </body>
</html>