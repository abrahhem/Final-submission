<?php
	include 'db.php';
	include "config.php";
	session_start();
	if(!isset($_SESSION["user_id"]))
	{
		header('Location:' . URL . 'index.php');
	}
	
	$user_query = "SELECT * FROM dbShnkr22studWeb1.tbl_219_users WHERE user_id =" . $_SESSION['user_id'];
	$market_query = "SELECT sector_id, img_url, name, value  FROM dbShnkr22studWeb1.tbl_219_assets a
					 INNER JOIN
					 	dbShnkr22studWeb1.tbl_219_market m
					 ON
					 	a.asset_id = m.asset_id";
					
	$market_result  = mysqli_query($connection , $market_query);
	$user_result	= mysqli_query($connection , $user_query);
	if(!$user_result || !$market_result) {
		die("DB query failed.");
	}
	
	$user_row	= mysqli_fetch_assoc($user_result);
	$row = mysqli_fetch_assoc($market_result);


?>


<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">


	<script src="https://kit.fontawesome.com/77b777f4e2.js" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

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
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse"
					data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false"
					aria-label="Toggle navigation">
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
							<a class="nav-link" href="home.php">Home</a>
						</li>
						<li class="nav-item">
							<a class="nav-link active" href="#">Marketplace</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#">Market</a>
						</li>
					</ul>
					<form class="d-flex" role="search">
						<input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
						<button class="btn" type="button">Search</button>
					</form>
				</div>
			</div>
		</nav>

		<main role="main">

			<section class="jumbotron text-center">
				<div class="container">
					<h1 class="jumbotron-heading">Marketplace</h1>
					<p class="lead text-muted">Let's find your next assets.</p>
				</div>
			</section>

			<div class="album py-5 bg-light">
				<div class="container">

					<div class="row">
						<?php 
							if (is_array($row)) {
								while ($row) {
									echo '<div class="col-md-4">
											<div class="card mb-4 box-shadow">
											<img class="card-img-top" src="' . $row["img_url"] . '" alt="Card">
											<div class="card-body">
											<p class="card-text s_id">' . $row["sector_id"] . '</p>
											<p class="card-text">' . $row["name"] . '</p>
											<div class="d-flex justify-content-between align-items-center">
												<div class="btn-group">
													<button type="button" class="btn m-2 btn-outline-secondary">View</button>
												</div>
												<small class="text-muted">' . $row["value"] . '</small>
												</div>
												</div>
											</div>
										</div>';
								}
							}
						?>
					</div>
				</div>
			</div>

		</main>

		<!-- TradingView Widget BEGIN -->
		<div class="tradingview-widget-container">
			<div class="tradingview-widget-container__widget"></div>
			<script type="text/javascript"
				src="https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js" async>
				{
					"symbols": [{
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
					<a class="menu__item" href="#">
						<i class="fa-regular fa-circle-info"></i>
						<span>About</span>
					</a>
				</li>
				<li>
					<a class="menu__item" href="#">
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
				<a href="#"><i class="fa-brands fa-linkedin icon in"></i></i></a>
			</div>
		</section>
	</footer>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js"
		integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous">
	</script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js"
		integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous">
	</script>
	<script src="js/script.js"></script>
	<script src="js/market.js"></script>
</body>

</html>
<?php
//close DB connection
mysqli_close($connection);
?>