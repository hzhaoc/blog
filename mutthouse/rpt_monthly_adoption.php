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
            <div class="center_left">
                <div class='profile_section'>
                    <div class='subtitle'>  Summary of Adopotions per Month per Breed </div>
                    <table class="wide_table">
                        <tr>
                            <td class='heading'> Month </td>
                            <td class='heading'> Breed </td>
                            <td class='heading'> Surrendered Dog # </td>
                            <td class='heading'> Adopted Dog # </td>
                            <td class='heading'> Total Expense </td>
                            <td class='heading'> Total Adoption Fee </td>
                            <td class='heading'> Net Profit </td>
                        </tr>
                        <?php
                            $query = get_query_rpt_mthly_adoption();
                            $result = mysqli_query($db, $query); 
                            include('lib/show_queries.php');
                            if ( !empty($result) && (mysqli_num_rows($result) > 0) ) {
                                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                    print "<tr>";
                                        print "<td class='wide_table_cell'>{$row['MONTH']}</td>";
                                        print "<td>{$row['breed']}</td>";
                                        print "<td>{$row['num_surrender']}</td>";
                                        print "<td>{$row['num_adoption']}</td>";
                                        if ($row['total_expense'] == NULL){ echo "<td class='wide_table_cell'>\$0.00</td>";} 
                                        else {
                                            $_tmp = round($row['total_expense'], 2);
                                            echo "<td class='wide_table_cell'>\$$_tmp</td>";
                                        }
                                        if ($row['total_fee'] == NULL){ echo "<td class='wide_table_cell'>\$0.00</td>";} 
                                        else {
                                            $_tmp = round($row['total_fee'], 2);
                                            echo "<td class='wide_table_cell'>\$$_tmp</td>";
                                        }
                                        if ($row['net_profit'] == NULL){ echo "<td class='wide_table_cell'>\$0.00</td>";} 
                                        else {
                                            $_tmp = round($row['net_profit'], 2);
                                            echo "<td class='wide_table_cell'>\$$_tmp</td>";
                                        }
                                    print "</tr>";
                                }
                            } else {
                                array_push($error_msg,  "Query WARN: query returned NULL...<br>" . __FILE__ ." line:". __LINE__ );
                            }                
                        ?>
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