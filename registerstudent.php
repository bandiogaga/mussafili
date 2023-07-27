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
            <h2>Register Student</h2>
            <form method="post" action="">
                <table>
                    <tr>
                        <td class="flabel">Username:</td>
                        <td><input required type="text" name="email"></td>
                    </tr>
                    <tr>
                        <td class="flabel">Password:</td>
                        <td><input required type="password" name="password"></td>
                    </tr>
                    <tr>
                        <td class="flabel">Confirm Password:</td>
                        <td><input required type="password" name="password2"></td>
                    </tr>
                    <tr>
                        <td class="flabel">First Name: </td>
                        <td><input required maxlength="40" type="text" name="fname"></td>
                    </tr>
                    <tr>
                        <td class="flabel">Last Name:</td>
                        <td><input required maxlength="40" type="text" name="lname"></td>
                    </tr>
                    <tr>
                        <td class="flabel">Date of Birth:</td>
                        <td><input required type="date" name="dob"></td>
                    </tr>
                    <tr>
                        <td class="flabel">Sex:</td>
                        <td>
                            <select required name="sex">
		<option value=""></option>
		<option value="M">Male</option>
		<option value="F">Female</option>
                </select></td>
                    </tr>
                    <tr>
                        <td class="flabel">Photo:</td>
                        <td><input type="file" name="photo"></td>
                    </tr>
                    <tr>
                        <td> <input type="Submit" name="submit_student" value="Register Student"></td>
                    </tr>
                </table>
            </form>
            <?php
	if(isset($_POST["submit_student"])){
		$hash = password_hash($_POST["password"], PASSWORD_BCRYPT);
		if($_POST["password"] === $_POST["password2"]){
			$sql = "INSERT INTO stpuser (type, email, password, fname, lname, dob, sex, photo, last_login_ip) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
			$stmt = $pdo->prepare($sql);
			$sex = $_POST["sex"];
			$ip = getenv('HTTP_CLIENT_IP')?:
				getenv('HTTP_X_FORWARDED_FOR')?:
				getenv('HTTP_X_FORWARDED')?:
				getenv('HTTP_FORWARDED_FOR')?:
				getenv('HTTP_FORWARDED')?:
				getenv('REMOTE_ADDR');
			$stmt->execute(['S',$_POST["email"],$hash,$_POST["fname"], $_POST["lname"], $_POST["dob"], $sex, $_POST["photo"], $ip]);
			$sql5 = "SELECT user_id FROM stpuser WHERE email=?";
			$stmt5 = $pdo->prepare($sql5);
			$stmt5->execute([$_POST["email"]]);
			$row5 = $stmt5->fetch(PDO::FETCH_ASSOC);
			$_SESSION['r_user_id'] = $row5["user_id"];
			?>
                <form method="post" action="">
                    Cohort:
                    <select required name="cohort">
					<option value=""></option>
					<?php
					$sql2 = "SELECT * FROM cohort WHERE sex = ? ORDER BY form, section, cohort_id, sex";
						$stmt2 = $pdo->prepare($sql2);
						$stmt2->execute([$sex]);
						while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
							$cid = $row["cohort_id"];
							echo '<option value="' . $cid . '">' . "Form " . cohort_getFormSection($cid, $pdo) . '</option>';
						} 
					?>
				</select>
                    <input type="Submit" name="submit_cohort" value="Complete Registration">
                </form>
                <?php
		} else{
			echo "Password confirmation did not match.";
		}
	}
	if(isset($_POST["submit_cohort"])){
		$sql3 = "UPDATE stpuser SET cohort_id = ? WHERE stpuser.user_id=?";
		$stmt3 = $pdo->prepare($sql3);
		$stmt3->execute([$_POST["cohort"],$_SESSION['r_user_id']]);
		$sql_class = "SELECT * FROM class WHERE cohort_id = ?";
		$stmt_class = $pdo->prepare($sql_class);
		$stmt_class->execute([$_POST["cohort"]]);
		while ($row_class = $stmt_class->fetch(PDO::FETCH_ASSOC)){
			$sql5 = "INSERT INTO class_student (class_id, student_id) VALUES (?,?)";
			$stmt5 = $pdo->prepare($sql5);
			$stmt5->execute([$row_class["class_id"],$_SESSION['r_user_id']]);
		}
		echo "registration complete!";
	}
}
    else{
        header('Location: /admin/unauthorized.php');
    }
?>
    </body>

    </html>
