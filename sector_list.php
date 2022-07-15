<?php 
	include 'db.php';
	include "config.php";
	session_start();
	
	if(!isset($_SESSION["user_id"]))
	{
		header('Location:' . URL . 'index.php');
	}
	
	$sector_id	= $_GET["sector_id"];
	if(isset($_GET["delete"])) {
		
		$delete_history_query = "DELETE m
									FROM dbShnkr22studWeb1.tbl_219_assets a
								INNER JOIN
									dbShnkr22studWeb1.tbl_219_market m
								ON
									m.asset_id = a.asset_id
								WHERE
									a.asset_id =" . $_GET["delete"];
		$delete_market_query = "DELETE h
									FROM dbShnkr22studWeb1.tbl_219_assets a
									INNER JOIN
										dbShnkr22studWeb1.tbl_219_total_history h
									ON
										h.asset_id = a.asset_id
									WHERE
										a.asset_id =" . $_GET["delete"];;
		$delete_asset_query = "DELETE a, ua
								FROM dbShnkr22studWeb1.tbl_219_assets a
								INNER JOIN
								dbShnkr22studWeb1.tbl_219_users_assets ua
								ON
									a.asset_id = ua.asset_id
								WHERE
									a.asset_id=" . $_GET["delete"];
		if(!mysqli_query($connection, $delete_market_query) || !mysqli_query($connection, $delete_history_query) || !mysqli_query($connection, $delete_asset_query)) {
			die("DB query failed.");
		}

		$massage = "The item was successfully deleted!";
		var_dump(2);
	}
	var_dump(2);
	$user_query 	= "SELECT * FROM dbShnkr22studWeb1.tbl_219_users WHERE user_id = " . $_SESSION["user_id"];
	$assets_query	= "SELECT a.asset_id, a.img_url, name, category_id, cost, total FROM dbShnkr22studWeb1.tbl_219_assets a
						INNER JOIN
							dbShnkr22studWeb1.tbl_219_users_assets ua
						ON
							a.asset_id = ua.asset_id
						INNER JOIN
							dbShnkr22studWeb1.tbl_219_users u
						ON
							ua.user_id = u.user_id
						WHERE
							u.user_id = " .  $_SESSION["user_id"] . "
						AND
							a.sector_id = " . $sector_id;
	

	$index_sector_query = "SELECT year, sum(total_value) AS year_sum
							FROM 
								dbShnkr22studWeb1.tbl_219_total_history h
							INNER JOIN
								dbShnkr22studWeb1.tbl_219_assets a
							ON
								h.asset_id = a.asset_id
							INNER JOIN
								dbShnkr22studWeb1.tbl_219_users_assets ua
							ON
								ua.asset_id = a.asset_id
							WHERE
								ua.user_id = " . $_SESSION['user_id'] . "
							AND
								a.sector_id = " . $sector_id . "
							GROUP BY
								year";

	$user_result			= mysqli_query($connection, $user_query);
	
	$assets_result			= mysqli_query($connection, $assets_query);
	
	$index_sector_result	= mysqli_query($connection, $index_sector_query);

	if(!$user_result || !$assets_result || !$index_sector_result) {
		die("DB query failed.");
	}
	$user_row			= mysqli_fetch_assoc($user_result);
	$assets_row			= mysqli_fetch_assoc($assets_result);
	$index_sector_row	= mysqli_fetch_assoc($index_sector_result);
	
	
?>
<!DOCTYPE html>
<html>
	<head>
		<title>sector list</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<script src="https://kit.fontawesome.com/77b777f4e2.js" crossorigin="anonymous"></script>
		<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
		<script src="https://bernii.github.io/gauge.js/dist/gauge.min.js"></script>
		
		<?php 
			if(is_array($index_sector_row)) {
				echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/charts.css/dist/charts.min.css">';
			}
		?>
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" 
		integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
		<link rel="icon" href="images/favicon.ico">
		<link rel="stylesheet" href="css/style.css">
	</head>
	<body>
		<header class="flexpp">
			<section class="wrapper flexpp header">
				<a class="logo" href="home.php"></a>
				<a href="profile.php" class="profile flexpp">
					<img src="<?php echo $user_row["img_url"];?>" alt="<?php echo $user_row["f_name"];?>">
					<p><?php echo $user_row["f_name"];?></p>
				</a>
			</section>
		</header>
		<div class="wrapper">
			<nav class="navbar navbar-expand-lg">
				<div class="container-fluid">
				  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				  </button>
				  <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
					<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					  <li class="nav-item">
						<a class="nav-link" id="open_menu">More</a>
					  </li>
					  <li class="nav-item">
						<a class="nav-link" href="#">Watchlist</a>
					  </li>
					  <li class="nav-item">
						<a class="nav-link active" href="home.php">Home</a>
					  </li>
					  <li class="nav-item">
						<a class="nav-link" href="marketplace.php">Marketplace</a>
					  </li>
					  <li class="nav-item">
						<a class="nav-link" href="#">Market</a>
					  </li>
					</ul>
					<form class="d-flex" role="search">
						<input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
						<button class="btn" type="button" >Search</button>
					</form>
				  </div>
				</div>
			</nav>
			<duv class="d-flex justify-content-center"><h2 class="s_id"><?php echo $sector_id ;?></h2></duv>
			<div class="dashboardupper">
				<section class="update">

					<h3><span class="s_id"><?php echo $sector_id ;?></span> index</h3>
					<?php 
						if(!is_array($index_sector_row)) {
							echo '<div class="alert alert-info m-5" role="alert">
									<h4 class="alert-heading">There is not enough information to build the graph!</h4>
									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
									<hr>
									<p class="mb-0">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.</p>
							 	</div>';
						}
						else {
							echo '<div id="chart">
									<div class="charts_option">
											<div class="first">
												<i id="refresh"></i>
											</div>
											<div class="second">
												<select class="form-select" aria-label="Default select example" id="options">
													<option selected>Line</option>
													<option>Area</option>
													<option>Column</option>
												</select>
											</div>
									</div>
										<table class="charts-css line show-data-axes show-4-secondary-axes show-labels" id="my-chart">
											<tbody id="motion-effect">';
											while ($index_sector_row) {
												echo  '<tr>
															<th scope="row">' . $usindx_row["year"] . '</th>
															<td style="--start: 0.2; --size: 0.4"><span class="data"> $ <><span class="data">' . $usindx_row["year_sum"] . '</span> </td>
														</tr>';
												$index_sector_row = mysqli_fetch_assoc($usindx_result);
											}
								echo		'</tbody>
										</table>
									</div>';
						}
					?>
				</section>
				<section class="dail">
					<h3>Growth meter</h3>
					<div id="preview-textfield"></div>
					<canvas id="demo" height="400" width="400"></canvas>
				</section>
			</div>
			<div class="list_container">
				<?php 
					if(is_array($assets_row)) {
						if ($massage) {
							echo '<div class="alert alert-primary" role="alert">' . $massage .'</div>';
						}

						echo	'<div class="table-wrapper">
									<div class="toolbar">
										<i class="fa-regular fa-trash-can icon" id="delete"></i>
										<a href="addasset.html">
											<i class="fa-regular fa-circle-plus icon"></i> 
										</a>
									</div>
									<table class="fl-table" id="tosort">
										<thead>
											<tr>
												<th></th>
												<th>Picture</th>
												<th onclick="sort(2)">Name</th>
												<th onclick="sort(3)">Category</th>
												<th onclick="sort(4)">Cost</th>
												<th onclick="sort(5)">Value</th>
											</tr>
										</thead>
										<tbody>';
											while ($assets_row) {
												echo	'<tr>
															<td><a href="sector_list.php?delete=' . $assets_row["asset_id"] . '"><i class="fa-duotone fa-trash-can fa-xl" hidden></i></a></td>
															<td><a href="asset.php?asset_id=' . $assets_row["asset_id"] .'"><img src="' .  $assets_row["img_url"] . '" alt="' . $assets_row["name"] . '"></a></td>
															<td>' .  $assets_row["name"] . '</td>
															<td><p class="insertgory">' . $assets_row["category_id"] . '</p></td>
															<td class="cost">' . $assets_row["cost"] . '</td>
															<td class="value">' .  $assets_row["total"] . '</td>
														</tr>';
												$assets_row = mysqli_fetch_assoc($assets_result);
											}
						echo			'<tbody>
									</table>
								</div>';	
					} else {
						echo '<div class="alert alert-info m-5" role="alert">
								<h4 class="alert-heading">Your list is empty, you have no assets!</h4>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
								<hr>
								<p class="mb-0">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.</p>
							 </div>';
					}
				?>


			</div>
		</div>
		<div class="wrapper">
			<!-- TradingView Widget BEGIN -->
			<div class="tradingview-widget-container">
				<div class="tradingview-widget-container__widget"></div>
				<script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js"
					async>
						{
							"symbols": [
								{
									"proName": "FOREXCOM:SPXUSD",
									"title": "S&P 500"
								},
								{
									"proName": "FOREXCOM:NSXUSD",
									"title": "US 100"
								},
								{
									"proName": "FX_IDC:EURUSD",
									"title": "EUR/USD"
								},
								{
									"proName": "BITSTAMP:BTCUSD",
									"title": "Bitcoin"
								},
								{
									"proName": "BITSTAMP:ETHUSD",
									"title": "Ethereum"
								}
							],
							"showSymbolLogo": true,
							"colorTheme": "light",
							"isTransparent": false,
							"displayMode": "adaptive",
							"locale": "en"
						}
					</script>
			</div>
			<!-- TradingView Widget END -->
		</div>
		<div id="menu__box">
			<div id="close_menu">
				<i class="fa-regular fa-xmark fa-3x"></i>
			</div>
			<div id="setime">
				<h2></h2>
				<p></p>
				<p class="digital-clock"></p>
			</div>
			<div class="containers">
				<a href="profile.php" class="profile">
					<img src="<?php echo $user_row["img_url"];?>" alt="<?php echo $user_row["f_name"];?>">
					<p><?php echo $user_row["f_name"];?></p>
				</a>
			</div>
			<nav>
				<ul class="flexpp">
					<li>
						<a class="menu__item" href="home.php">
							<i class="fa-regular fa-house"></i>
							<span>Home</span>
						</a>
					</li>
					<li>
						<a class="menu__item" href="addasset.php">
							<i class="fa-regular fa-file-circle-plus"></i>
							<span>Add asset</span>
						</a>
					</li>
					<li>
						<a class="menu__item" href="#">
							<i class="fa-regular fa-circle-info"></i>
							<span>About</span>
						</a>
					</li>
					<li>
						<a class="menu__item" href="marketplace.php">
							<i class="fa-regular fa-store"></i>
							<span>Marketplace</span>
						</a>
					</li>
					<li>
						<a class="menu__item" href="#">
							<i class="fa-regular fa-circle-dollar"></i>
							<span>Currency</span>
						</a>
					</li>
					<li>
						<a class="menu__item" href="#">
							<i class="fa-regular fa-gauge-high"></i>
							<span>Risks test</span>
						</a>
					</li>
					<li>
						<a class="menu__item" href="#">
							<i class="fa-regular fa-hand-holding-dollar"></i>
							<span>Loans</span>
						</a>
					</li>
					<li>
						<a class="menu__item" href="#">
							<i class="fa-regular fa-gear"></i>
							<span>Settings</span>
						</a>
					</li>
					<li>
						<a class="menu__item" href="logout.php">
							<i class="fa-solid fa-arrow-right-from-bracket"></i>
							<span>Log Out</span>
						</a>
					</li>
				</ul>
			</nav>	
		</div>
		<footer class="flexpp">
			<section class="wrapper flexpp">
				<a href="home.php" class="logo"></a>
				<p>&copy;Abrahem Elnakeeb & Roy Weizman. 2022 all rights reserved</p>
				<div>
					<a href="#"><i class="fa-brands fa-instagram icon ins"></i></a>
					<a href="#"><i class="fa-brands fa-facebook icon fc"></i></a>
					<a href="#"><i class="fa-brands fa-whatsapp icon wu"></i></a>
					<a href="#"><i class="fa-brands fa-twitter icon tw"></i></a>
					<a href="#"><i class="fa-brands fa-linkedin icon in"></i></a>
				</div>
			</section>
		</footer>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>	
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>
		<script src="js/script.js"></script>
		<script src="js/chart.js"></script>
		<script src="js/creat_gauge.js"></script> 
		<script src="js/table.js"></script>
		<script src="js/list.js"></script>
	</body>
</html>
<?php
	mysqli_close($connection);
?>

