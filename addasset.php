

<?php
	include 'db.php';
	include "config.php";
	session_start();
	if(!isset($_SESSION["user_id"]))
	{
		header('Location:' . URL . 'index.php');
	}
	$user_query = "SELECT * FROM dbShnkr22studWeb1.tbl_219_users WHERE user_id =" . $_SESSION['user_id'];
	$user_result	= mysqli_query($connection , $user_query);
	$user_row		= mysqli_fetch_assoc($user_result);
	
	if(!empty($_POST["name"])) {
	
		$total = $_POST['quantity']*$_POST['value'];
		$query = "INSERT INTO dbShnkr22studWeb1.tbl_219_assets 
						(sector_id, category_id, name, title, cost, value, total, quantity, description, address, img_url) 
					VALUES
						(" . $_POST['sector_id'] . ", " . $_POST['category_id'] . ", '" .  $_POST['name'] . "', '" .  $_POST['title'] . "',
						" .  $_POST['cost'] . ", " .  $_POST['value'] . ", " .  $total . ", " .  $_POST['quantity'] . ",'
						" .  $_POST['description'] . "', '" . $_POST['address'] . "', '" . $_POST['img_url'] . "')";

		$result 	= mysqli_query($connection , $query);
		$asset_id_query = "SELECT asset_id FROM dbShnkr22studWeb1.tbl_219_assets WHERE name = '" . $_POST['name'] . "'";
		$result_id 	= mysqli_query($connection , $asset_id_query);
		if(!$result || !$result_id) {
			die("DB query failed.");
		}
	
		$asset_id			= mysqli_fetch_assoc($result_id);

		$user_asset_query	= "INSERT INTO dbShnkr22studWeb1.tbl_219_users_assets (user_id, asset_id) VALUES (" . $_SESSION['user_id'] . ", " .  $asset_id['asset_id'] . ")";

		$save_result 		= mysqli_query($connection , $user_asset_query);

		if(!$save_result) {
			die("DB query failed.");
		}
		$massage = "The asset was successfully added!";

		#mysqli_free_result($save_result);
		#mysqli_free_result($result);
		#mysqli_free_result($result_id);
	}
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
	<body id="new">
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
			<div class="text-center"><h2>Add New Asset</h2></div>
			<div class="new-asset-form">
				<form action="#" method="post">
					<div class="form-icon">
						<span><i class="fa-duotone fa-building-columns"></i></span>
					</div>
					<?php if(isset($massage)) { echo '<div class="alert alert-success mb-5" role="alert">' . $massage . '</div>'; } ?>
					<div class="form-floating">
						<select class="form-select mb-4" id="sector" name="sector_id" required>
						</select>
						<label for="sector">Sector</label>
					</div>
					
					<div class="form-floating">
						<select class="form-select myinput mb-4" id="category" name="category_id" disabled required>
						</select>
						<label for="category">Category</label>
					</div>
					
					<div class="form-floating">
						<input type="text" class="form-control myinput mb-4" id="name" name="name" placeholder="name" disabled required>
						<label class="form-label" for="name">Name</label>
					</div>
					
					<div class="form-floating">
						<input type="number" class="form-control myinput" id="cost"  name="cost" placeholder="cost"  aria-describedby="cost_help" disabled required>
						<label for="cost">Cost</label>
						<div id="cost_help" class="form-text mb-2">Includes all units.</div>
					</div>
					
					<div class="form-floating">
						<input type="number" class="form-control myinput" id="value" name="value" placeholder="value" aria-describedby="value_help" disabled required>
						<label class="form-label" for="value">Value</label>
						<div id="value_help" class="form-text mb-2">Per unit.</div>
					</div>
					
					<div class="form-floating">
						<textarea class="form-control myinput mb-4" id="description" value="" name="description" placeholder="description" disabled></textarea>
						<label for="description">Description</label>
					</div>
					
					<div class="form-floating">
						<input type="text" class="form-control myinput mb-4" id="title" name="title" placeholder="title" disabled>
						<label for="title" id="title_label">Title</label>
					</div>
					
					<div class="form-floating">
						<input type="number" class="form-control myinput mb-4" id="quantity" name="quantity" placeholder="quantity" disabled>
						<label for="quantity">Quantity</label>
					</div>
					
					<div class="form-floating">
						<input type="text" class="form-control myinput mb-4" id="address" name="address" placeholder="address" disabled>
						<label for="img_url">Address</label>
					</div>
					
					<div class="form-floating">
						<input type="text" class="form-control myinput mb-4" id="img_url" name="img_url" placeholder="img_url" disabled value="images/assets/default.jpeg">
						<label for="img_url">Img Url</label>
					</div>
					<div id="buttons" class="mt-3 d-flex justify-content-center" >
						<button id="save" type="submit" class="btn btn-primary w-25 m-2">Save</button>	
						<button id="reset" type="reset" class="btn btn-warning w-25 m-2">Reset</button>
					</div>
				</form>
			</div>
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
		<script src="js/addasset.js"></script>
	</body>
</html>
<?php 
	#mysqli_free_result($user_result);
	mysqli_close($connection);
?>