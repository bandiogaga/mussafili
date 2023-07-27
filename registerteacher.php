<?php
ob_start();
require($_SERVER["DOCUMENT_ROOT"] . "/connect.php");?>
    <html>

    <head>
        <link rel="stylesheet" type="text/css" href="../css/main.css">
    </head>

    <body>
        <?php
require($_SERVER["DOCUMENT_ROOT"] . "/functions.php");
$curYear = date('Y');
$curMonth = date('n');

if(!isSet($_SESSION['type'])){
	header('Location: /admin/unauthorized.php');
}
	if($_SESSION['admin']){
		require($_SERVER["DOCUMENT_ROOT"] . "/adminheader.php");
		echo "<br>";
?>
            <h2>Register Teacher</h2>
            <form method="post" action="">
                Username:<input required type="text" name="email"><br> Password:
                <input required type="password" name="password"><br> Confirm Password:<input required type="password" name="password2"><br> First Name: <input required maxlength="40" type="text" name="fname"><br> Last Name: <input required maxlength="40" type="text" name="lname"><br> Date of Birth: <input required type="date" name="dob"><br> Sex:
                <select required name="sex">
		<option value=""></option>
		<option value="M">Male</option>
		<option value="F">Female</option>
	</select>
                <br> Phone: <input type="tel" name="phone"><br> Photo: <input type="file" name="photo"><br>
                <input type="Submit" name="submit_teacher" value="Register Teacher">
            </form>
            <?php
	if(isset($_POST["submit_teacher"])){
		$hash = password_hash($_POST["password"], PASSWORD_BCRYPT);
		if($_POST["password"] === $_POST["password2"]){
			$ip = getenv('HTTP_CLIENT_IP')?:
				getenv('HTTP_X_FORWARDED_FOR')?:
				getenv('HTTP_X_FORWARDED')?:
				getenv('HTTP_FORWARDED_FOR')?:
				getenv('HTTP_FORWARDED')?:
				getenv('REMOTE_ADDR');
			$sql = "INSERT INTO stpuser (type, email, password, fname, lname, dob, sex, phone, photo, last_login_ip) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(['T',$_POST["email"],$hash,$_POST["fname"], $_POST["lname"], $_POST["dob"], $_POST["sex"], $_POST["phone"], $_POST["photo"], $ip]);
			echo "<br>";
			echo "added teacher!";
		} else{
			echo "Password confirmation did not match.";
		}
	}
    }
    else{
        header('Location: /admin/unauthorized.php');
    }
?>
    </body>

    </html>
