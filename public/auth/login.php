<?php
require_once '../../app/config/bootstrap.php';
?>


<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Login</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.6.3/css/all.min.css" integrity="sha512-f2MWjotY+JCWDlE0+QAshlykvZUtIm35A6RHwfYZPdxKgLJpL8B+VVxjpHJwZDsZaWdyHVhlIHoblFYGkmrbhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body class="d-flex align-items-center">
	<div class="container h-100">
		<div class="row justify-content-center align-items-center min-vh-100">
			<div class="col-md-6 col-lg-4"> <!-- Adjust column size as needed -->
				<form method="POST" action="<?= request_url('/auth/login.php') ?>" class="card p-4 shadow">
					<h3 class="h3 mb-3 text-center">Please login</h3>

					<div class="form-floating mb-3">
						<input type="text" class="form-control" id="floatingInput" placeholder="username" name="username">
						<label for="floatingInput">Username</label>
						<span id="roleError" class="text-danger"><?= Message::getError('Auth@login', 'username') ?></span>

					</div>

					<div class="form-floating mb-3">
						<input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password">
						<label for="floatingPassword">Password</label>
						<span id="roleError" class="text-danger"><?= Message::getError('Auth@login', 'password') ?></span>
					</div>

					<div class="mb-3">
						<button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
					</div>

					<?= Message::getMessage('Auth@login') ?>
				</form>
			</div>
		</div>
	</div>
</body>

</html>