<?php
	include 'db.php';
	include "config.php";
	session_start();
	if(!isset($_SESSION["user_id"]))
	{
		header('Location:' . URL . 'index.php');
	}


	$asset_id = $_GET["asset_id"];

	$asset_query = "SELECT * FROM dbShnkr22studWeb1.tbl_219_assets WHERE asset_id =" . $asset_id;

	$user_query = "SELECT * FROM dbShnkr22studWeb1.tbl_219_users WHERE user_id = " . $_SESSION['user_id'];

	$history_query = "SELECT year, sum(total_value) 
						FROM 
							dbShnkr22studWeb1.tbl_219_total_history 
						WHERE
							asset_id = " . $asset_id . "
						GROUP BY
							year";
	if(isset($_GET["market"])) {
		$market_query = "INSERT INTO dbShnkr22studWeb1.tbl_219_market (user_id, asset_id) VALUES (" .  $_SESSION['user_id'] . ", " . $asset_id . ")";
		$market = mysqli_query($connection, $market_query);
		if (!$market) {
			die("DB query failed.");
		}
		$message = "Your property was successfully added";

	}
	if(!empty($_POST["name"])) {
	
		$update_query = "UPDATE dbShnkr22studWeb1.tbl_219_assets SET
							name='"			. $_POST["name"] . 			"', 
							value="			. $_POST["value"] . 		",
							category_id=" 	. $_POST["category_id"] . 	",
							cost="	 		. $_POST["cost"] . 			",
							title='" 		. $_POST["title"] . 		"',
							description='" 	. $_POST["description"] . 	"',
							quantity="	 	. $_POST["quantity"] . 		",
							img_url='" 		. $_POST["img_url"] . 		"',
							total=" 		. $_POST["value"]*$_POST["quantity"] . "
							WHERE asset_id=" . $asset_id;
			
		$update = mysqli_query($connection, $update_query);

		if (!$update) {
			die("DB query failed.");
		}
		$message = "Record Modified Successfully";
	}

	$asset_result	= mysqli_query($connection, $asset_query);
	$user_result	= mysqli_query($connection, $user_query);
	$histoy_result	= mysqli_query($connection, $history_query);
	if(!$asset_result || !$user_result|| !$histoy_result) {
		die("DB query failed.");
	}
	
	$asset	= mysqli_fetch_assoc($asset_result);
	$user	= mysqli_fetch_assoc($user_result);
	$histoy = mysqli_fetch_assoc($histoy_result);

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Asset</title>
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
	<body id="objact">
		<header class="flexpp">
			<section class="wrapper flexpp header">
				<a class="logo" href="home.php"></a>
				<a href="profile.php" class="profile flexpp">
					<img src="<?php echo $user["img_url"];?>" alt="<?php echo $user["f_name"];?>">
					<p><?php echo $user["f_name"];?></p>
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
							<button class="btn" type="button">Search</button>
						</form>
					</div>
				</div>
			</nav>
		</div>
		<div class="wrapper text-center d-flex flex-column align-items-center">
			<h2><?php echo $asset["name"];?></h2>
			<?php 
				if(isset($message)) {
					echo '<div class="alert alert-success m-2 w-25" role="alert" id="alert">' . $message . '</div>';
				}
			?>
			<div class="dashboardupper">
				<section class="info">
					<form id="form" action="#" method="post">
						<div class="row">
							<div class="col">
								<div class="form-floating">
									<input type="text" class="form-control myinput" id="name" name="name" placeholder="name" value="<?php echo $asset["name"];?>" disabled>
									<label class="form-label" for="name">Name</label>
								</div>
							</div>
							<div class="col">
								<div class="form-floating">
									<input type="number" class="form-control myinput" id="value" name="value" placeholder="value" value="<?php echo $asset["value"];?>" disabled aria-describedby="value_help">
									<label class="form-label" for="value">Value</label>
									<div id="value_help" class="form-text">Per unit.</div>
								</div>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col">
								<div class="form-floating">
									<input type="text" class="form-control myinput" id="sector" name="sector_id" placeholder="sector" value="<?php echo $asset["sector_id"];?>" disabled>
									<label for="sector">Sector</label>
								</div>
							</div>
							<div class="col">
								<div class="form-floating">
									<select class="form-select myinput" id="category" name="category_id" disabled>
										<option id="cate" value="<?php echo $asset["category_id"];?>"></option>
									</select>
									<label for="category">Category</label>
								</div>
							</div>
							<div class="col">
								<div class="form-floating">
									<input type="number" class="form-control myinput" id="cost" name="cost" placeholder="cost" value="<?php echo $asset["cost"];?>" disabled>
									<label for="cost">Cost</label>
								</div>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col">
								<div class="form-floating">
									<input type="text" class="form-control myinput" id="title" name="title" placeholder="title" value="<?php echo $asset["title"];?>" disabled>
									<label for="title" id="title_label">Title</label>
								</div>
							</div>
							<div class="col">
								<div class="form-floating">
									<textarea class="form-control myinput" id="description" name="description" placeholder="description" disabled><?php echo $asset["description"];?></textarea>
									<label for="description">Description</label>
								</div>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col">
								<div class="form-floating">
									<input type="text" class="form-control myinput" id="address" name="address" placeholder="address" value="<?php echo $asset["address"];?>" disabled>
									<label for="img_url">Address</label>
								</div>
							</div>
							<div class="col">
								<div class="form-floating">
									<input type="number" class="form-control myinput" id="quantity" name="quantity" placeholder="quantity" value="<?php echo $asset["quantity"];?>" disabled>
									<label for="quantity">Quantity</label>
								</div>
							</div>
							<div class="col">
								<div class="form-floating">
									<input type="text" class="form-control myinput" id="img_url" name="img_url" placeholder="img_url" value="<?php echo $asset["img_url"];?>" disabled>
									<label for="img_url">Img Url</label>
								</div>
							</div>
						</div>
						<div id="buttons" class="mt-3" hidden>
							<button id="save" type="submit" class="btn btn-primary">Save</button>	
							<button id="cancel" type="button" class="btn btn-secondary">Cancel</button>
							<button id="reset" type="reset" class="btn btn-warning">Reset</button>
						</div>
					</form>
					
					
				</section>
				<section class="info">
					<img class="framed" src="<?php echo $asset["img_url"];?>" alt="<?php echo $asset["name"];?>">
					
						<div class="toolbar text-white">
							<i id="edit" class="fa-regular fa-pen-to-square icon"></i>
						</div>
						<button type="button" class="btn btn-light"><a href="asset.php?asset_id<?php echo $asset_id;?>&market=1">Add To Marketplace</a></button>
						
				</section>
			</div>
			<section class="update">
				<h3><?php echo $asset["name"];?> index</h3>
				<?php 
					if(!is_array($histoy)) {
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
										while ($histoy) {
											echo  '<tr>
														<th scope="row">' . $usindx_row["year"] . '</th>
														<td style="--start: 0.2; --size: 0.4"><span class="data"> $ <><span class="data">' . $usindx_row["year_sum"] . '</span> </td>
													</tr>';
											$histoy = mysqli_fetch_assoc($histoy_result);
										}
							echo		'</tbody>
								</table>
							</div>';
					}
					?>
			</section>
			<!-- https://www.tradingview.com/widget/ticker-tape/ -->
			<!-- TradingView Widget BEGIN -->
			<div class="tradingview-widget-container">
				<div class="tradingview-widget-container__widget"></div>
				<script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js" async>
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
					<img src="<?php echo $user["img_url"];?>" alt="<?php echo $user["f_name"];?>">
					<p><?php echo $user["f_name"];?></p>
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
						<a class="menu__item" href="marketplace.php">
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
							<span>Risks Test</span>
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
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js"
			integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk"
			crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js"
			integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy"
			crossorigin="anonymous"></script>
		<script src="js/script.js"></script>
		<script src="js/chart.js"></script>
		<script src="js/asset.js"></script>
	</body>
</html>
<?php
//close DB connection
mysqli_close($connection);
?>