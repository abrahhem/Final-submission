<?php
	include 'db.php';
	include "config.php";
	session_start();
	if(isset($_SESSION["user_id"]))
	{
		header('Location:' . URL . 'home.php');
	}

	if(!empty($_POST["email"])){
		$query = "SELECT * FROM dbShnkr22studWeb1.tbl_219_users WHERE email = '" . $_POST["email"] . "' AND password = '" . $_POST["password"] . "'";
		$result = mysqli_query($connection , $query);

		if(!$result) {
			die("DB query failed.");
		}

		$row 	= mysqli_fetch_array($result);
		mysqli_free_result($result);
		if(!is_array($row)) {
			$massage = "Invalid email or password";
		} else {
			$_SESSION["user_id"] = $row["user_id"];
			header('Location: ' . URL . 'home.php');
		}
	}
	if (!empty($_GET["email"])) {
		$query = "INSERT INTO dbShnkr22studWeb1.tbl_219_users (email, password, f_name, l_name, img_url, phone) VALUES ('" . $_GET['email'] . "', '" . $_GET['password'] . "', '" . $_GET['f_name'] . "', '" . $_GET['l_name'] . "', '" . $_GET['img_url'] . "', '" . $_GET['phone'] . "')";
		$result = mysqli_query($connection , $query);
		$_GET["signed"] = 1;
		if(!$result) {
			die("DB query failed.");
		}
		
	}
	if(!empty($_GET["signed"])) {
		$massage = "You have successfully registered, sign in!";
	}
?>
<!DOCTYPE html>
<html>

<head>
	<title>Portfolio</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<script src="https://kit.fontawesome.com/77b777f4e2.js" crossorigin="anonymous"></script>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
	<link rel="icon" href="images/favicon.ico">
	<link rel="stylesheet" href="css/style.css">
</head>

<body id="login">
	<section class="vh-100 gradient-custom">
		<div class="container py-5 h-100">
			<div class="row d-flex justify-content-center align-items-center h-100">
				<div class="col-12 col-md-8 col-lg-6 col-xl-5">
					<div class="card bg-light">
						<div class="card-body p-5 text-center">
							<form action="#" method="<?php if(empty($_GET["signup"])) {echo "post";} else {echo "get";} ?>">
								<div class="mb-md-5 mt-md-4">
									<a class="logo"></a>
									<h2 class="fw-bold mb-2 m-3"><?php if(empty($_GET["signup"])) {echo "Login";} else {echo "Sign up";} ?></h2>
									<p class="text-black-50 mb-5">Hello investor, Please login</p>
									<div class="row-5 m-2">
										<div class="form-floating">
											<input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
											<label for="email">Email</label>
										</div>
									</div>
									<div class="row-5 m-2">
										<div class="form-floating">
											<input type="password" class="form-control" id="pass" name="password" placeholder="Password" required>
											<label for="pass">Password</label>
										</div>
									</div>
									<div class="row-5 m-2" <?php if(empty($_GET["signup"])) {echo "hidden";} ?>>
										<div class="form-floating">
											<input type="text" class="form-control" id="f_name" name="f_name" placeholder="f_name" <?php if(!empty($_GET["signup"])) {echo "required";}?>>
											<label for="f_name">First Name</label>
										</div>
									</div>
									<div class="row-5 m-2" <?php if(empty($_GET["signup"])) {echo "hidden";} ?>>
										<div class="form-floating">
											<input type="text" class="form-control" id="l_name" name="l_name" placeholder="f_name" <?php if(!empty($_GET["signup"])) {echo "required";}?>>
											<label for="l_name">Last Name</label>
										</div>
									</div>
									<div class="row-5 m-2" <?php if(empty($_GET["signup"])) {echo "hidden";} ?>>
										<div class="form-floating">
											<input type="tel" class="form-control" id="phone" name="phone" placeholder="phone" <?php if(!empty($_GET["signup"])) {echo "required";}?>>
											<label for="phone">Phone Number</label>
										</div>
									</div>
									<div class="row-5 m-2" <?php if(empty($_GET["signup"])) {echo "hidden";} ?>>
										<div class="form-floating">
											<input type="text" class="form-control" id="img_url" name="img_url" placeholder="img_url" value="images/profile/default_profile.png">
											<label for="l_name">Img Url</label>
										</div>
									</div>
									<?php if(empty($_GET["signup"])) {echo '<p class="small mb-5 pb-lg-2"><a class="text-black-50" href="#">Forgot password?</a></p>';} ?>

									<button class="btn btn-outline-light btn-lg px-5" type="submit"><?php if(empty($_GET["signup"])) {echo "Login";} else {echo "Sign up";} ?></button>

									<div class="d-flex justify-content-center mt-4 pt-1">
										<a class="m-1" href="#"><i class="fa-brands fa-facebook"></i></a>
										<a class="m-1" href="#"><i class="fa-brands fa-twitter"></i></a>
										<a class="m-1" href="#"><i class="fa-brands fa-linkedin"></i></a>
									</div>
									<?php 
										if (isset($massage) & empty($_GET["signed"])) {
											echo '<div class="alert alert-danger" role="alert">'. $massage . '</div>';
										}
										else if(isset($massage)) {
											echo '<div class="alert alert-success" role="alert">'. $massage . '</div>';
										}
									?>
								</div>
							</form>
							<div>
								<?php if(empty($_GET["signup"])) {echo '<p class="mb-0">Dont have an account? <a href="index.php?signup=1" class="text-black-50 fw-bold">Sign Up</a></p>';} ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js"
		integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous">
	</script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js"
		integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous">
	</script>
	<script src="js/script.js"></script>
</body>
</html>
<?php
//close DB connection
mysqli_close($connection);
?>