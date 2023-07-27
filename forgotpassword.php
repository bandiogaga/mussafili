<?php
require($_SERVER["DOCUMENT_ROOT"] . "/connect.php");
?>
    <html>

    <head>
        <link rel="stylesheet" type="text/css" href="../css/main.css">
        <script src="../js/jquery-3.3.1.min.js"></script>
        <script src="../js/form-validator/jquery.form-validator.min.js"></script>
    </head>
    <div class="main">

        <body>
            <?php
require($_SERVER["DOCUMENT_ROOT"] . "/functions.php");
$loggedIn = FALSE;

if(!isSet($_SESSION['type'])){
}
else{
    if(!$_SESSION['activated']){
            header('Location: /welcome.php');
            exit;
    }
	if ($_SESSION['admin']){
		require($_SERVER["DOCUMENT_ROOT"] . "/adminheader.php");
		$loggedIn = TRUE;
        echo '<br>';
	}
	else if ($_SESSION['type'] === 'S'){
		require($_SERVER["DOCUMENT_ROOT"] . "/studentheader.php");
		$loggedIn = TRUE;
		$_SESSION['admin']='0';
        echo '<br>';
	}
	else if ($_SESSION['type'] === 'T'){
		require($_SERVER["DOCUMENT_ROOT"] . "/teacherheader.php");
		$loggedIn = TRUE;
		$_SESSION['admin']='0';
        echo '<br>';
	}
	else if ($_SESSION['type'] === 'P'){
		require($_SERVER["DOCUMENT_ROOT"] . "/parentheader.php");
		$loggedIn = TRUE;
		$_SESSION['admin']='0';
        echo '<br>';
	}
}
$curYear = date('Y');
$curMonth = date('n');
    $showlogin = FALSE;
    if (!isset($_SESSION['logged_in'])){
        $showlogin = TRUE;
    }
    else if(!$_SESSION['logged_in']){
        $showlogin = TRUE;
    }
    
    if ($showlogin){
?>
                <h1 class="sitetitle">ST. BENEDICT'S</h1>
                <h2 class="sitedescription">Secondary School - Hanga, Tanzania</h2>
                <form class="login" method="post" action="">
                    <table>
                        <tr>
                            <td>Username:</td>
                            <td><input required type="text" name="email"></td>
                        </tr>
                        <tr>
                            <td><input class="loginbtn" type="Submit" name="notify" value="Notify Admin"></td>
                            <td><a href="school.php">Back to Home</a></td>
                        </tr>
                    </table>
                </form>
                <?php
	if (isset($_POST["notify"])){
		$sql1 = "SELECT user_id, fname, lname, admin, type, activated, email, password, cohort_id FROM stpuser WHERE email = ?";
		$stmt1 = $pdo->prepare($sql1);
		$stmt1->execute([$_POST["email"]]);
		$row = $stmt1->fetch(PDO::FETCH_ASSOC);
		// the message
	//$msg = "First line of text\nSecond line of text";

// use wordwrap() if lines are longer than 70 characters
	//$msg = wordwrap($msg,70);

// send email
	//mail($_POST["email"],"My subject",$msg);
		if($row === false){
			echo "Username '";
			echo $_POST["email"];
			echo "' is not registered";
		} else{
            $sql = "UPDATE stpuser SET forgotpass = 1 WHERE stpuser.email = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$_POST["email"]]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "Admin Notified. Speak with Web Admin to reset your password.";
		}
	}
    }
            else{
                header('Location: /changepassword.php');
            }
?>
        </body>
    </div>

    </html>
