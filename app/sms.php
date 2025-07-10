<?php
	require '../config/config.php';
	if(empty($_SESSION['username']))
		header('Location: login.php');	

	try {
		$stmt = $connect->prepare('SELECT * FROM users');
		$stmt->execute();
		$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	catch(PDOException $e) {
		$errMsg = $e->getMessage();
	}

	if(isset($_POST['sms_alert'])) {
		try {
			$message = htmlspecialchars($_POST['message']);

			if (!empty($_POST['check']) && is_array($_POST['check'])) {
				foreach ($_POST['check'] as $mobile) {
					// Send SMS logic here
					echo "Sending to: $mobile | Message: $message<br>";
				}
				// Redirect after sending
				header('Location: sms.php');
				exit();
			} else {
				echo "<div class='alert alert-warning text-center'>Please select at least one user to send SMS.</div>";
			}
		}
		catch(PDOException $e) {
			$errMsg = $e->getMessage();
		}
	}
?>

<?php include '../include/header.php';?>

<!-- Header nav -->	
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#212529;" id="mainNav">
  <div class="container">
    <a class="navbar-brand js-scroll-trigger" href="../index.php">Logo/Home</a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive">
      Menu <i class="fa fa-bars"></i>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav text-uppercase ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="login.php"><?php echo $_SESSION['fullname']; ?> <?php if($_SESSION['role'] == 'admin'){ echo "(Admin)"; } ?></a>
        </li>
        <li class="nav-item">
          <a href="../auth/logout.php" class="nav-link">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- End header nav -->

<?php include '../include/side-nav.php';?>

<section class="wrapper" style="margin-left:16%;margin-top: -11%;">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<?php
					if(isset($errMsg)){
						echo '<div style="color:#FF0000;text-align:center;font-size:17px;">'.$errMsg.'</div>';
					}
				?>
				<h2>List Of Users</h2>
				<div class="table-responsive text-center">
					<form action="" method="post" id="smsForm">
						<table class="table table-bordered">
						  <thead>
						    <tr>
						      <th><input type="checkbox" id="selectAll"></th>
						      <th>Full Name</th>
						      <th>Mobile</th>
						    </tr>
						  </thead>
						  <tbody>
						  	<?php 
						  		foreach ($data as $key => $value) {
								   echo '<tr>';
								      echo '<td><input type="checkbox" name="check[]" value="'.$value['mobile'].'"></td>';
								      echo '<td>'.$value['fullname'].'</td>';
								      echo '<td>'.$value['mobile'].'</td>';
								   echo '</tr>';
						  		}
						  	?>
						  </tbody>
						</table>
						
						<textarea name="message" class="form-control" placeholder="Enter Message (Message Body)" required></textarea>
						<br>
						<button type="submit" class="btn btn-success" name='sms_alert' value="sms_alert">Send SMS</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>

<?php include '../include/footer.php';?>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
	// Select/Deselect all checkboxes
	$('#selectAll').click(function(){
		$('input[name="check[]"]').prop('checked', this.checked);
	});

	// Prevent form submit if no checkbox is selected
	document.getElementById("smsForm").addEventListener("submit", function(e) {
		const checked = document.querySelectorAll('input[name="check[]"]:checked');
		if (checked.length === 0) {
			alert("Please select at least one user.");
			e.preventDefault();
		}
	});
</script>
