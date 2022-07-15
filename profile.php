<?php 
	include 'db.php';
	include "config.php";
	session_start();
	if(!isset($_SESSION["user_id"]))
	{
		header('Location:' . URL . 'index.php');
	}

	if(isset($_GET["delete"])) {
		$delete_assets = "DELETE 
							dbShnkr22studWeb1.tbl_219_users_assets,
							dbShnkr22studWeb1.tbl_219_assets 
						FROM dbShnkr22studWeb1.tbl_219_users u
						INNER JOIN
							dbShnkr22studWeb1.tbl_219_users_assets ua
						ON
							u.user_id = ua.user_id
						INNER JOIN
							dbShnkr22studWeb1.tbl_219_assets a
						ON
							a.asset_id = ua.asset_id
						WHERE
							u.user_id =" . $_SESSION["user_id"];
		$delete_user = "DELETE FROM dbShnkr22studWeb1.tbl_219_users user_id =" . $_SESSION["user_id"];

		$delete_assets_r = mysqli_query($connection, $delete_assets);
		$delete_user_r = mysqli_query($connection, $delete_user);
		if(!$delete_assets_r || !$delete_user_r) {
			die("DB query failed.");
		}
		session_destroy();
		header('Location: ' . URL . 'index.php');
	}

	$user_query  = "SELECT * FROM dbShnkr22studWeb1.tbl_219_users WHERE user_id = " . $_SESSION["user_id"];

	if(!empty($_POST["email"])) {
		$update = mysqli_query($connection, "UPDATE dbShnkr22studWeb1.tbl_219_users set f_name='" . $_POST['f_name'] . "', l_name='" . $_POST['l_name'] . "', email='" . $_POST['email'] . "', phone='" . $_POST['phone'] . "' ,img_url='" . $_POST['img_url'] . "' WHERE user_id = " . $_SESSION["user_id"]);
		if(!$update) {
			die("DB query failed.");
		}
		$message = "Record Modified Successfully";
	}
	$query_assets = "SELECT count(asset_id) AS asset_amount 
					FROM 
						dbShnkr22studWeb1.tbl_219_users u
					INNER JOIN
						dbShnkr22studWeb1.tbl_219_users_assets ua
					ON
						u.user_id = ua.user_id
					GROUP BY
						u.user_id
					HAVING
						u.user_id = " . $_SESSION["user_id"];

	$query_sector = "SELECT  sector_id AS common FROM (
						SELECT sector_id ,count(a.asset_id) assets FROM dbShnkr22studWeb1.tbl_219_assets a
							INNER JOIN
								dbShnkr22studWeb1.tbl_219_users_assets ua
							ON
								ua.asset_id = a.asset_id
							INNER JOIN
								dbShnkr22studWeb1.tbl_219_users u
							ON
								u.user_id = ua.user_id
							WHERE
								u.user_id = " . $_SESSION["user_id"] . "
							GROUP BY
								sector_id) AS assets_amount
						ORDER BY
							assets DESC
						LIMIT 1";

	$result_user	= mysqli_query($connection, $user_query);
	$result_assets	= mysqli_query($connection, $query_assets);
	$result_sector	= mysqli_query($connection, $query_sector);
	if(!$result_user || !$result_assets || !$result_sector) {
		die("DB query failed.");
	}
	$user_row		= mysqli_fetch_assoc($result_user);
	$assets_row 	= mysqli_fetch_assoc($result_assets);
	$sector_row 	= mysqli_fetch_assoc($result_sector);
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Profile Page</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<script src="https://kit.fontawesome.com/77b777f4e2.js" crossorigin="anonymous"></script>
		<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" 
		integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
		<link rel="icon" href="images/favicon.ico">
		<link rel="stylesheet" href="css/style.css">
	</head>
	<body id="profile">
		<header class="flexpp">
			<section class="wrapper flexpp header">
				<a class="logo" href="home.php"></a>
				<a href="profile.php" class="profile flexpp">
					<img src="<?php echo $user_row["img_url"];?>" alt="<?php echo $user_row["f_name"];?>">
					<p><?php echo $user_row["f_name"];?></p>
				</a>
			</section>
		</header>
		<div class="wrapper vh-100">
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
			<section class="container h-100">
				<div class="container py-5 h-100">
				  <div class="row d-flex justify-content-center align-items-center h-100">
					<div class="col col-lg-6 mb-4 mb-lg-0 text-start"> 
						<h1>Profile</h1>
					  <div class="card mb-3">
						<form action="#" method="post" id="form">
							<div class="row g-0">
								<div class="col-md-4 gradient-custom text-center text-white">
									<img src="<?php echo $user_row["img_url"];?>" alt="<?php echo $user_row["f_name"];?>" class="img-fluid my-5"/>
									<h5><?php echo $user_row["f_name"] . " " . $user_row["l_name"];?></h5>
									<div class="row">
										<button id="edit" type="button"><i class="far fa-edit mb-5"></i></button>
										
									</div>
									<button id="save" type="submit" class="btn btn-primary  m-1" hidden>Save</button>	
									<button id="cancel" type="button" class="btn btn-secondary  m-1" hidden>Cancel</button>
									<button id="reset" type="reset" class="btn btn-warning m-1" hidden>Reset</button>
									<?php 
										if(isset($message)) {
											echo '<div class="alert alert-success m-2" role="alert" id="alert">' . $message . '</div>';
										}
									?>
									<div class="row"><a href="#?delete=1"><i class="fa-duotone fa-trash-can fa-xl mt-4 mb-2"></i></a></div>
								</div>
								<div class="col-md-8">
									<div class="card-body p-4">
										<h6>Information</h6>
										<hr class="mt-0 mb-4">
										<div class="row pt-1">
											<div class="col-6 mb-3">
												<div class="form-floating">
													<input type="text" class="form-control myinput" id="f_name" name="f_name" placeholder="First Name" value="<?php echo $user_row["f_name"];?>" disabled>
													<label for="f_name">First Name</label>
												</div>
											</div>
											<div class="col-6 mb-3">
												<div class="form-floating">
													<input type="text" class="form-control myinput" id="l_name" name="l_name" placeholder="Last Name" value="<?php echo $user_row["l_name"];?>" disabled>
													<label for="l_name">Last Name</label>
												</div>
											</div>
											<div class="col-6 mb-3">
												<div class="form-floating">
													<input type="email" class="form-control myinput" id="email" name="email" placeholder="Email" value="<?php echo $user_row["email"];?>" disabled>
													<label for="email">Email</label>
												</div>
											</div>
											<div class="col-6 mb-3">
												<div class="form-floating">
													<input type="tel" class="form-control myinput" id="phone" name="phone" placeholder="Phone Number" value="<?php echo $user_row["phone"];?>" disabled>
													<label for="phone">Phone Number</label>
												</div>
											</div>
											<div id="img" class="col-6 mb-3" hidden>
												<div class="form-floating">
													<input type="text" id="img_url" class="form-control myinput" name="img_url" placeholder="IMG UR" value="<?php echo $user_row["img_url"];?>" disabled>
													<label for="img_url">img url</label>
												</div>
											</div>
										</div>
										<h6>Investments</h6>
										<hr class="mt-0 mb-4">
										<div class="row pt-1">
											<div class="col-6 mb-3">
												<h6>Number of assets</h6>
												<p class="text-muted">
													<?php 
														if (isset($assets_row["asset_amount"])) {
															echo $assets_row["asset_amount"];
														}
														else {
															echo 0;
														}
													?>
												</p>
												
											</div>
											<div class="col-6 mb-3">
												<h6>Common sector</h6>
												<span class="text-muted" id="s_id">
												<?php 
													if(isset($sector_row["common"])) {
														echo $sector_row["common"];
													}
													else {
														echo 0;
													}
												?>
												</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
					  </div>
					</div>
				  </div>
				</div>
			</section>
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
					<li>
						<a class="menu__item" href="logout.php">
							<i class="fa-solid fa-arrow-right-from-bracket"></i>
							<span>Log Out</span>
						</a>
					</li>
				</li>
				</ul>
			</nav>	
		</div>
		<footer class="flexpp">
			<section class="wrapper flexpp">
				<a href="index.html" class="logo"></a>
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
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>	
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>
		<script src="js/script.js"></script>
		<script src="js/profile.js"></script>
	</body>
</html>
<?php
	mysqli_close($connection);
?>

