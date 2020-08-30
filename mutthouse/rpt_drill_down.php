<?php
include('lib/common.php');
include('sql/query.php');
if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

if( $_SERVER['REQUEST_METHOD'] == 'POST'){
    $month = mysqli_real_escape_string($db, $_POST['month']);
    $ms = $month."-01"; # month start day
}
?>


<?php include("lib/header.php"); ?>
</head>

<body>
    <div id="main_container">
        <div class="center_content">
            <?php include("lib/menu.php"); ?>
                <div class='profile_section'>
                    <div class='title'> Month <?php echo "$month" ?> Drill Down Report</div>
                    <div class='subtitle'> Category 1: Surrendered Dogs </div>
                    <table class='wide_table'>
                        <tr>
                            <td class='heading'>Dog ID</td>
                            <td class='heading'>Breed</td>
                            <td class='heading'>Sex</td>
                            <td class='heading'>Alteration Status</td>
                            <td class='heading'>Microchip ID</td>
                            <td class='heading'>Surrender Date</td>
                        </tr>
                        <?php
                            $query = get_query_drilldown1($month."-01");
                            $result = mysqli_query($db, $query);
                            include('lib/show_queries.php');
                            if ( !empty($result) && (mysqli_num_rows($result) > 0) ) {
                                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                    print "<tr>";
                                        print "<td>{$row['dog_id']}</td>";
                                        print "<td>{$row['breed']}</td>";
                                        print "<td>{$row['sex']}</td>";
                                        print "<td>{$row['alteration']}</td>";
                                        print "<td>{$row['microchip_id']}</td>";
                                        print "<td>{$row['surrender_date']}</td>";
                                    print "</tr>";
                                }
                            }              
                        ?>
                    </table>

                    <div class='subtitle'> Category 2: Adopted Dogs </div>
                    <table class='wide_table'>
                        <tr>
                            <td class='heading'>Dog ID</td>
                            <td class='heading'>Breed</td>
                            <td class='heading'>Sex</td>
                            <td class='heading'>Alteration Status</td>
                            <td class='heading'>Microchip ID</td>
                            <td class='heading'>Surrender Date</td>
                            <td class='heading'>Days in Rescue / Days</td>
                        </tr>
                        <?php
                            $query = get_query_drilldown2($month."-01");
                            $result = mysqli_query($db, $query);
                            include('lib/show_queries.php');
                            if ( !empty($result) && (mysqli_num_rows($result) > 0) ) {
                                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                    print "<tr>";
                                        print "<td>{$row['dog_id']}</td>";
                                        print "<td>{$row['breed']}</td>";
                                        print "<td>{$row['sex']}</td>";
                                        print "<td>{$row['alteration']}</td>";
                                        print "<td>{$row['microchip_id']}</td>";
                                        print "<td>{$row['surrender_date']}</td>";
                                        if ($row['days_in_rescue'] >= 60) {print "<td>{$row['days_in_rescue']}</td>";}
                                        else {print "<td> <=60 </td>";}
                                    print "</tr>";
                                }
                            }              
                        ?>
                    </table>

                </div>
            <?php include("lib/error.php"); ?>
            <div class="clear"></div> 
        </div>
        <?php include("lib/footer.php"); ?>
    </div>  
</body>
</html>