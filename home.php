<?php
	include 'db.php';
	include "config.php";
	session_start();
	if(!isset($_SESSION["user_id"]))
	{
		header('Location:' . URL . 'index.php');
	}
	$user_query = "SELECT * FROM dbShnkr22studWeb1.tbl_219_users WHERE user_id =" . $_SESSION['user_id'];
	$totals_query = "SELECT	sum(a.cost) AS total_invested, sum(a.total) AS total_value 
						FROM
							dbShnkr22studWeb1.tbl_219_users u
						INNER JOIN
							dbShnkr22studWeb1.tbl_219_users_assets ua
						ON
							u.user_id = ua.user_id
						INNER JOIN
							dbShnkr22studWeb1.tbl_219_assets a
						ON
							ua.asset_id = a.asset_id
						WHERE u.user_id = " . $_SESSION['user_id'];

	$loans_query = "SELECT	sum(a.cost) AS total_loans FROM dbShnkr22studWeb1.tbl_219_users u
						INNER JOIN
							dbShnkr22studWeb1.tbl_219_users_assets ua
						ON
							u.user_id = ua.user_id
						INNER JOIN
							dbShnkr22studWeb1.tbl_219_assets a
						ON
							ua.asset_id = a.asset_id
						WHERE 
							u.user_id = " . $_SESSION['user_id'] . "
						AND
							a.asset_id = 0";

	$user_index_query = "SELECT year, sum(total_value) AS year_sum
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
							GROUP BY
								year";

	$sectors_query = "SELECT sector_id, sum(total) AS sector_total
						FROM 
							dbShnkr22studWeb1.tbl_219_users u
						INNER JOIN
							dbShnkr22studWeb1.tbl_219_users_assets ua
						ON
							u.user_id = ua.user_id
						INNER JOIN
							dbShnkr22studWeb1.tbl_219_assets a
						ON
							ua.asset_id = a.asset_id
						WHERE
							ua.user_id = " . $_SESSION['user_id'] . "
						GROUP BY
								sector_id";

	$user_result	= mysqli_query($connection , $user_query);
	$totals_result	= mysqli_query($connection , $totals_query);
	$usindx_result	= mysqli_query($connection , $user_index_query);
	$sectors_result = mysqli_query($connection , $sectors_query);
	$loans_result	= mysqli_query($connection , $loans_query);

	if(!$user_result || !$totals_result || !$usindx_result || !$sectors_result || !$loans_result) {
		die("DB query failed.");
	}

	$user_index_row	= mysqli_fetch_array($usindx_result);
	$sectors_row	= mysqli_fetch_assoc($sectors_result);
	$user_row		= mysqli_fetch_assoc($user_result);
	$totals_row 	= mysqli_fetch_assoc($totals_result);
	$loans_row	 	= mysqli_fetch_assoc($loans_result);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Home Page</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<script src="https://kit.fontawesome.com/77b777f4e2.js" crossorigin="anonymous"></script>
		<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/charts.css/dist/charts.min.css">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" 
		integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
		<link rel="icon" href="images/favicon.ico">
		<link rel="stylesheet" href="css/style.css">
	</head>
	<body id="home">
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
			<div class="dashboardupper">
				<section class="info">
					<div class="card text-bg-light mb-3 details">
						<div class="card-header">Total Value</div>
						<div class="card-body"> 
							<h5 class="card-title"><?php if (!empty($totals_row["total_value"])) {
															echo $totals_row["total_value"] . ' $';
														} 
														else {
															echo '0 $';
														}
													?>
							</h5>
						</div>
					</div>
					<div class="card text-bg-light mb-3 details">
						<div class="card-header">Total Invested</div>
						<div class="card-body">
							<h5 class="card-title"><?php if (!empty($totals_row["total_invested"])) {
															echo $totals_row["total_invested"] . ' $';
														} 
														else {
															echo '0 $';
														}
													?>
							</h5>
						</div>
					</div>
					<div class="card text-bg-light mb-3 details">
						<div class="card-header">Total Profit</div>
						<div class="card-body">
							<h5 class="card-title"><?php if (!empty($totals_row["total_value"])) {
															echo $totals_row["total_value"]-$totals_row["total_invested"] . ' $';
														} 
														else {
															echo '0 $';
														}
													?>
							</h5>
						</div>
					</div>
					<div class="card text-bg-light mb-3 details">
						<div class="card-header">Total Loans</div>
						<div class="card-body">
							<h5 class="card-title"><?php if (!empty($loans_row["total_loans"])) {
															echo $loans_row["total_loans"] . ' $';
														} 
														else {
															echo '0 $';
														}
													?>
							</h5>
						</div>
					</div>
				</section>
				<section class="update">
					<h2><?php echo $user_row["f_name"];?>'s index</h2>
					<?php 
						
						if (!$user_index_row) {
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
											while ($user_index_row) {
												echo  '<tr>
															<th scope="row">' . $user_index_row["year"] . '</th>
															<td style="--start: 0.2; --size: 0.4"><span class="data">$ <span class="data">' . $user_index_row["year_sum"] . '</span> </td>
														</tr>';
												$user_index_row = mysqli_fetch_assoc($usindx_result);
											}
								echo		'</tbody>
										</table>
									</div>';
						}
					 ?>
					 <!-- <tr>
							th scope="row"> 2019 </th>
							<td style="--start: 0.4; --size: 0.8"> <span class="data"> $ 80K </span> </td>
					</tr> -->
					
				</section>
				<section class="info">
					<!-- TradingView Widget BEGIN -->
					<div class="tradingview-widget-container">
						<div class="tradingview-widget-container__widget"></div>
						<script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-market-overview.js" async>
						{
							"colorTheme": "light",
							"dateRange": "12M",
							"showChart": true,
							"locale": "en",
							"width": "100%",
							"height": "100%",
							"largeChartUrl": "",
							"isTransparent": false,
							"showSymbolLogo": true,
							"showFloatingTooltip": false,
							"plotLineColorGrowing": "rgba(41, 98, 255, 1)",
							"plotLineColorFalling": "rgba(41, 98, 255, 1)",
							"gridLineColor": "rgba(0, 0, 0, 0)",
							"scaleFontColor": "rgba(120, 123, 134, 1)",
							"belowLineFillColorGrowing": "rgba(41, 98, 255, 0.12)",
							"belowLineFillColorFalling": "rgba(41, 98, 255, 0.12)",
							"belowLineFillColorGrowingBottom": "rgba(41, 98, 255, 0)",
							"belowLineFillColorFallingBottom": "rgba(41, 98, 255, 0)",
							"symbolActiveColor": "rgba(41, 98, 255, 0.12)",
							"tabs": [
							{
								"title": "Indices",
								"symbols": [
								{
									"s": "FOREXCOM:SPXUSD",
									"d": "S&P 500"
								},
								{
									"s": "FOREXCOM:NSXUSD",
									"d": "US 100"
								},
								{
									"s": "FOREXCOM:DJI",
									"d": "Dow 30"
								},
								{
									"s": "INDEX:NKY",
									"d": "Nikkei 225"
								},
								{
									"s": "INDEX:DEU40",
									"d": "DAX Index"
								},
								{
									"s": "FOREXCOM:UKXGBP",
									"d": "UK 100"
								}
								],
								"originalTitle": "Indices"
							},
							{
								"title": "Futures",
								"symbols": [
								{
									"s": "CME_MINI:ES1!",
									"d": "S&P 500"
								},
								{
									"s": "CME:6E1!",
									"d": "Euro"
								},
								{
									"s": "COMEX:GC1!",
									"d": "Gold"
								},
								{
									"s": "NYMEX:CL1!",
									"d": "Crude Oil"
								},
								{
									"s": "NYMEX:NG1!",
									"d": "Natural Gas"
								},
								{
									"s": "CBOT:ZC1!",
									"d": "Corn"
								}
								],
								"originalTitle": "Futures"
							},
							{
								"title": "Bonds",
								"symbols": [
								{
									"s": "CME:GE1!",
									"d": "Eurodollar"
								},
								{
									"s": "CBOT:ZB1!",
									"d": "T-Bond"
								},
								{
									"s": "CBOT:UB1!",
									"d": "Ultra T-Bond"
								},
								{
									"s": "EUREX:FGBL1!",
									"d": "Euro Bund"
								},
								{
									"s": "EUREX:FBTP1!",
									"d": "Euro BTP"
								},
								{
									"s": "EUREX:FGBM1!",
									"d": "Euro BOBL"
								}
								],
								"originalTitle": "Bonds"
							},
							{
								"title": "Forex",
								"symbols": [
								{
									"s": "FX:EURUSD",
									"d": "EUR/USD"
								},
								{
									"s": "FX:GBPUSD",
									"d": "GBP/USD"
								},
								{
									"s": "FX:USDJPY",
									"d": "USD/JPY"
								},
								{
									"s": "FX:USDCHF",
									"d": "USD/CHF"
								},
								{
									"s": "FX:AUDUSD",
									"d": "AUD/USD"
								},
								{
									"s": "FX:USDCAD",
									"d": "USD/CAD"
								}
								],
								"originalTitle": "Forex"
							}
							]
					}
					</script>
					</div>
					  <!-- TradingView Widget END -->
				</section>
			</div>
			<section class="cards_container">
				<div class="text">
					<h2>Investments by sector</h2>
				</div>
				<?php 
					if (is_array($sectors_row)) {
						while ($sectors_row) {
							echo '<a href="sector_list.php?sector_id=' . $sectors_row["sector_id"] . '" class="scard">';
							echo 	'<span hidden>' . $sectors_row["sector_id"] . '</span>';
							echo 	'<img>';
							echo 	'<div class="container js">';
							echo 		'<h4></h4>';	
							echo 		'<p>' .  $sectors_row["sector_total"] . '$</p>';
							echo	'</div>';
							echo '</a>';
							$sectors_row = mysqli_fetch_assoc($sectors_result);
						}
					}
					else {
						echo '<div class="alert alert-info m-5" role="alert">
								<h4 class="alert-heading">Your list is empty, you have no assets!</h4>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
								<hr>
								<p class="mb-0">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium.</p>
							 </div>';
					}
					mysqli_free_result($user_result);
					mysqli_free_result($totals_result);
					mysqli_free_result($usindx_result);
					mysqli_free_result($sectors_result);
					mysqli_free_result($loans_result);
				?>
			</section>
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
		<script src="js/sectorcard.js"></script>
	</body>
</html>
<?php
//close DB connection
mysqli_close($connection);
?>