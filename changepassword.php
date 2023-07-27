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
    ?>
            <h2>Change Password</h2>
            <form method="post" action="">
                <table>
                    <tr>
                        <td><strong>Current Password:</strong></td>
                        <td><input required type="password" name="passwordattempt"></input>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>New Password:</strong></td>
                        <td><input required type="password" name="newpass1"></input>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Confirm Password:</strong></td>
                        <td><input required type="password" name="passconfirm"></input>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="Submit" name="change_password" value="Change Password"></td>
                    </tr>
                </table>
            </form>
            <?php
    if (isset($_POST['change_password'])){
        $passAttempt = $_POST["passwordattempt"];
        $password = $_SESSION['password'];
        $auth = password_verify($passAttempt, $password);
        if($auth){
            $newpass1 = $_POST["newpass1"];
            $passconfirm = $_POST["passconfirm"];
            $hash = password_hash($newpass1, PASSWORD_BCRYPT);
            if($newpass1 === $passconfirm){
                $sql = "UPDATE stpuser SET password = ? WHERE stpuser.user_id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$hash, $_SESSION["user_id"]]);
                $_SESSION['password'] = $hash;
                //header('Location: /profile.php');
                echo 'Password changed.';
            }
            else{
                echo "Password was not changed. 'New Password' and 'Confirm Password' did not match. Please try again.";
                //redirect to new page for sign up/recovering password/signing in again
            }
        }
        else{
            echo "Password was not changed. Current password incorrect. Please try again.";
        }
    }
?>
    </body>

    </html>
