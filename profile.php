<?php
require($_SERVER["DOCUMENT_ROOT"] . "/connect.php");?>
    <html>

    <head>
        <link rel="stylesheet" type="text/css" href="../css/main.css">
    </head>

    <body>
        <?php
       
require($_SERVER["DOCUMENT_ROOT"] . "/functions.php");
$loggedIn = FALSE;

if(!isSet($_SESSION['type'])){
    if(!$_SESSION['activated']){
            header('Location: /welcome.php');
            exit;
    }
	require($_SERVER["DOCUMENT_ROOT"] . "/header.php");
}
else{
    if(!$_SESSION['activated']){
            header('Location: /welcome.php');
            exit;
    }
	if ($_SESSION['admin']){
		require($_SERVER["DOCUMENT_ROOT"] . "/adminheader.php");
		$loggedIn = TRUE;
	}
	else if ($_SESSION['type'] === 'S'){
		require($_SERVER["DOCUMENT_ROOT"] . "/studentheader.php");
		$loggedIn = TRUE;
		$_SESSION['admin']='0';
	}
	else if ($_SESSION['type'] === 'T'){
		require($_SERVER["DOCUMENT_ROOT"] . "/teacherheader.php");
		$loggedIn = TRUE;
		$_SESSION['admin']='0';
	}
	else if ($_SESSION['type'] === 'P'){
		require($_SERVER["DOCUMENT_ROOT"] . "/parentheader.php");
		$loggedIn = TRUE;
		$_SESSION['admin']='0';
	}
}
echo '<br>';
    $curYear = date('Y');
    $curMonth = date('n');
    if (isset($_SESSION['user_id'])){
        if ($_SESSION['type'] === 'S'){
            $sql = "SELECT *, cohort.sex as c_sex FROM stpuser, cohort WHERE stpuser.user_id = ? AND stpuser.cohort_id = cohort.cohort_id";
        }
        else{
            $sql = "SELECT * FROM stpuser WHERE stpuser.user_id = ?";
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$_SESSION['user_id']]);
?>
            <h2>My Account</h2>
            <?php
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            echo '<form method="post" action=""><table border = "1">';
            echo '<tr><td><strong>Username</strong></td><td>' . $row['email'] . '</td></tr>';
            echo '<tr><td><strong>First Name</strong></td><td>' . $row['fname'] . '</td></tr>';
            echo '<tr><td><strong>Last Name</strong></td><td>' . $row['lname'] . '</td></tr>';
            echo '<tr><td><strong>Date of Birth</strong></td><td>' . $row['dob'] . '</td></tr>';
            echo '<tr><td><strong>Sex</strong></td><td>' . userSexToString($row['sex']) . '</td></tr>';
            if ($row['type'] === 'S'){
                echo '<tr><td><strong>Form</strong></td><td>' . $row['form'] . '-' . $row['section'] . ' (' . cohortSexToString($row['c_sex']) . ')</td></tr>';
            }
            else {
                echo '<tr><td><strong>Phone</strong></td><td>' . $row['phone'] . '</td></tr>';
            }
            echo '</table>';
            echo '<table><tr><td><input type="Submit" name="change_password" value="Change Password"></td></tr></table>';
            echo '</form>';
        }
    }
    else{
        header('Location: /school.php');
    }
	if(isset($_POST["change_password"])){
        header('Location: /changepassword.php');
        echo "write code here";
	}
	else{
	}
?>
    </body>

    </html>
