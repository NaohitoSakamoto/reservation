<?php
    session_start();

	$title = "データベース | ";
    require_once( 'header.php' );
    
    /* データベースへ接続 */
	require_once( "connectDB.php" );

    /* それぞれのデータベースを格納する連想配列を用意 */
    $dataBaseNames = [
        "room_reservation" => $roomReservationTable = array(),
        "customer_info" => $customerInfoTablee = array(),
        "room_info" => $roomInfoTable = array(),
        "room_grade" => $roomGradeTable = array()
    ];

    /* それぞれのデータベースを取得 */
    foreach( $dataBaseNames as $dataBaseName => &$dataBaseTable ){
        $sth = $dbh->prepare( "select * from " . $dataBaseName );
        $sth->execute();
        $dataBaseTable = $sth->fetchAll( PDO::FETCH_ASSOC );
    }

    unset($dataBaseTable);

    /* データベース数を取得 */
    $dataBaseNamesNum = count( $dataBaseNames );

?>
<main>
    <div id="showDB">
        <h2>データベース一覧</h2>
<?php
    /* すべてのデータベースの表示 */
    foreach( $dataBaseNames as $dataBaseName => $dataBaseTable ){
        print "<h3>" . $dataBaseName . "</h3>";
        print "<div class='table'>";
        /* データベースを1行ずつ表示 */
        foreach( $dataBaseTable as $lineNum => $line ){
            /* 列数を取得し、幅を計算 */
            $rowNum = count( $line );
            $rowWidth = 100 / $rowNum;

            /* タイトルを表示 */
            if( $lineNum == 0 ){
                foreach( $line as $rowName => $row ){
                    print "<div class='title' style='width:" . $rowWidth . "%;'>" . $rowName . "</div>";
                }
            }

            /* それぞれのデータを表示 */
            foreach( $line as $row ){
                print "<div class='data' style='width:" . $rowWidth . "%;'>" . $row . "</div>";
            }
        }
        print "</div>";
    }
?>
    </div><!-- #showDB -->
</main>
<?php
	require_once( 'footer.php' );
?>