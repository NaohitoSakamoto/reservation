<?php
	session_start();

	/* 予約日時が設定されていた場合 */
	if( isset( $_SESSION["checkInDate"] ) ){
		/* 予約情報を初期化 */
		unset( $_SESSION["checkInDate"] );
		unset( $_SESSION["checkOutDate"] );
		unset( $_SESSION["numberOfPeople"] );
		unset( $_SESSION["numberOfRooms"] );
		unset( $_SESSION["roomGradeId"] );
		unset( $_SESSION["feePerPerson"] );
	}

	/* 管理者の場合ユーザーID解除 */
	if( isset( $_SESSION["userId"] ) ){
		if( $_SESSION["userId"] == 1 ){
			unset( $_SESSION["userId"] );
			unset( $_SESSION["userName"] );
		}
	}

	$title = "";
	require_once( 'header.php' );
?>
<main>
	<div id="home">
		<div class="reservation">
			<h2>予約</h2>
			<form action="selectRoom.php" method="post">
				<p>チェックイン</p>
				<input type="date" name="checkInDate">
				<p>宿泊数</p>
				<div class="selectForm">
					<select name="numberOfNights">
						<option value="1">1泊</option>
						<option value="2">2泊</option>
						<option value="3">3泊</option>
					</select>
				</div><!--/selectForm-->
				<p>大人</p>
				<div class="selectForm">
					<select name="numberOfPeople">
						<option value="1">1人</option>
						<option value="2">2人</option>
						<option value="3">3人</option>
					</select>
				</div><!--/selectForm-->
				<p>部屋数</p>
				<div class="selectForm">
					<select name="numberOfRooms">
						<option value="1">1部屋</option>
						<option value="2">2部屋</option>
						<option value="3">3部屋</option>
					</select>
				</div><!--/selectForm-->
				<button type="submit">検索</button>
			</form>
		</div><!--/reservation-->
		<div class="information">
			<h2>お知らせ</h2>
			<dl>
				<dt>2019.1.15</dt>
				<dd>消防定期点検実施のご案内</dd>
				<dt>2019.1.26</dt>
				<dd>宿泊システム変更のご案内</dd>
				<dt>2019.2.23</dt>
				<dd>客室リニューアルのお知らせ</dd>
				<dt class="last">2019.3.09</dt>
				<dd>サイトリニューアルのお知らせ</dd>
			</dl>
		</div><!--/information-->
		<div class="facility">
			<h2>ホテル設備</h2>
			<ul>
				<li>
					<i class="fas fa-wifi"></i>
					<p>Wi-Fi</p>
				</li>
				<li>
					<i class="fas fa-bus"></i>
					<p>送迎バス</p>
				</li>
				<li>
					<i class="fas fa-utensils"></i>
					<p>レストラン</p>
				</li>
				<li>
					<i class="fas fa-bath"></i>
					<p>客室備え付き風呂</p>
				</li>
				<li>
					<i class="fas fa-store"></i>
					<p>売店</p>
				</li>
			</ul>
		</div><!--/facility-->
		<div class="access">
			<h2>アクセス</h2>
			<img src="images/map.jpg" alt="案内地図">
			<div class="accessInfo">
				<div>
					<p>〒650-0034<br>兵庫県神戸市中央区京町６７<br>TEL:078-381-9820</p>
					<h3>・交通手段</h3>
					<ul>
						<li>地下鉄海岸線旧居留地大丸前駅より徒歩10分</li>
						<li>地下鉄海岸線三宮花時計前駅より徒歩10分</li>
						<li>JR三ノ宮駅より徒歩12分</li>
						<li>阪急神戸三宮駅より徒歩12分</li>
						<li>阪神神戸三宮駅より徒歩12分</li>
					</ul>
				</div>
			</div><!--/accessInfo-->
		</div><!--/access-->
	</div><!--/home-->
</main>
<?php
	require_once( 'footer.php' );
?>