<?php
include('lib/common.php');
include('sql/query.php');
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

?>


<?php include("lib/header.php"); ?>
</head>

<body>
    <div id="main_container">
        <div class="center_content">
            <?php include("lib/menu.php"); ?>

            <?php
            if( $_SERVER['REQUEST_METHOD'] == 'POST'){ // search results 
                $input_name = mysqli_real_escape_string($db, $_POST['input_name']);
                $query = get_query_volunteer_lookup($input_name);
                $result = mysqli_query($db, $query);
                include('lib/show_queries.php');
                print "<div class='profile_section'>";
                    print "<div class='subtitle'>Volunteer search results</div>";
                    print "<table>";
                        print "<tr>";
                            print "<td class='heading'>First Name</td>";
                            print "<td class='heading'>Last Name</td>";
                            print "<td class='heading'>Email</td>";
                            print "<td class='heading'>Phone Number</td>";
                        print "</tr>";
                if ( !empty($result) && (mysqli_num_rows($result) > 0) ) {
                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                        print "<tr>";
                            print "<td>{$row['fname']}</td>";
                            print "<td>{$row['lname']}</td>";
                            print "<td>{$row['email']}</td>";
                            print "<td>{$row['phone_num']}</td>";
                        print "</tr>";                        
                    }
                }
                    print "</table>";
                print "</div>";
            }
            else{ // search form
                print "<div class='text_box'>";
                    print "<form action='rpt_volunteer_lookup.php' method='post'>";
                        print "<div class='title'>Search Volunteer</div>";
                        print "<div class='login_form_row'>";
                            print "<label class='login_label'>Name:</label>";
                            print "<input type='text' name='input_name' value='' class='login_input'/>";
                        print "</div>";
                        print "<input type='submit' value='SEARCH'/>";
                    print "<form/>";
                print "</div>";
            }
            ?>

            <?php include("lib/error.php"); ?>
            <div class="clear"></div>
        </div>
        <?php include("lib/footer.php"); ?>
    </div>  
</body>
</html>