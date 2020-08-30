<?php
include('lib/common.php');
include('lib/src.php');
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
            <div class="center_left">
                <div class='profile_section'>
                    <div class='subtitle'> Section 1: Animal Controled dog number </div>
                    <table>
                        <tr>
                            <td class='heading'>Month</td>
                            <td class='heading'>Count</td>
                        </tr>
                        <?php
                            $query = get_query_rpt_actrl1();
                            $result = mysqli_query($db, $query); 
                            include('lib/show_queries.php');
                            if ( !empty($result) && (mysqli_num_rows($result) > 0) ) {
                                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                    print "<tr>";
                                        print "<td>{$row['month']}</td>";
                                        print "<td>{$row['dog_number_by_animal_control']}</td>";
                                    print "</tr>";
                                }
                            } else {
                                array_push($error_msg,  "Query WARN: First query returned NULL...<br>" . __FILE__ ." line:". __LINE__ );
                            }                
                        ?>
                    </table>

                    <div class='subtitle'> Section 2: Animal controled and adopted dog number (>= 60d in rescue) </div>
                    <table>
                        <tr>
                            <td class='heading'>Month</td>
                            <td class='heading'>Count</td>
                        </tr>
                        <?php
                            $query = get_query_rpt_actrl2();
                            $result = mysqli_query($db, $query); 
                            include('lib/show_queries.php');
                            if ( !empty($result) && (mysqli_num_rows($result) > 0) ) {
                                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                    echo "<tr>";
                                        echo "<td>{$row['month']}</td>";
                                        echo "<td>{$row['dog_number_60_days']}</td>";
                                    echo "</tr>";
                                }
                            } else {
                                array_push($error_msg,  "Query WARN: Second query returned NULL...<br>" . __FILE__ ." line:". __LINE__ );
                            }                
                        ?>
                    </table>

                    <div class='subtitle'> Section 3: Total animal controled dog expenses </div>
                    <table>
                        <tr>
                            <td class='heading'>Month</td>
                            <td class='heading'>Expense</td>
                        </tr>
                        <?php
                            $query = get_query_rpt_actrl3();
                            $result = mysqli_query($db, $query); 
                            include('lib/show_queries.php');
                            if ( !empty($result) && (mysqli_num_rows($result) > 0) ) {
                                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                    echo "<tr>";
                                        echo "<td>{$row['month']}</td>";
                                        if ($row['total_expense'] == NULL){
                                            echo "<td>\$0.00</td>";
                                        } else {
                                            $_tmp = round($row['total_expense'], 2);
                                            echo "<td>\$$_tmp</td>";
                                        }
                                    echo "</tr>";
                                }
                            } else {
                                array_push($error_msg,  "Query WARN: Third query returned NULL...<br>" . __FILE__ ." line:". __LINE__ );
                            }                
                        ?>
                    </table>

                    <div class='subtitle'> Section 4: Drill Down Report by Month </div>
                    <table>
                        <form action="rpt_drill_down.php" method="post">
                            <tr>
                                <td class='heading'>Month</td>
                                <td>
                                    <select name="month" onchange='if(this.value != 0) { this.form.submit(); }'>
                                        <?php
                                            $today = new MyDateTime(date("Y-m-d"));
                                            $strtoday = $today->format('Y-m');
                                            echo "<option value = $strtoday> $strtoday </option>";
                                            for($x = 0; $x <= 5; $x++){
                                                $today->addMonth(-1);
                                                $strtoday = $today->format('Y-m');
                                                echo "<option value = $strtoday> $strtoday </option>";
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        </form>
                    </table>

                </div>
            </div>
            <?php include("lib/error.php"); ?>
            <div class="clear"></div> 
        </div>
        <?php include("lib/footer.php"); ?>
    </div>  
</body>
</html>