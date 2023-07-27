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

if(!isSet($_SESSION['type'])){
    header('Location: /school.php');
}
else if(!$_SESSION['activated']){
   echo '<h1>Welcome, ' . $_SESSION['fname']. '!</h1>';
    echo "<h2>Before we get started...</h2>";
    ?>
        <form method="post" action="">
            <table>
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
        $newpass1 = $_POST["newpass1"];
        $passconfirm = $_POST["passconfirm"];
        $hash = password_hash($newpass1, PASSWORD_BCRYPT);
        if($newpass1 === $passconfirm){
            $sql = "UPDATE stpuser SET password = ? WHERE stpuser.user_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$hash, $_SESSION["user_id"]]);
            $sql = "UPDATE stpuser SET activated = ? WHERE stpuser.user_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['1', $_SESSION["user_id"]]);
            $_SESSION['activated'] = TRUE;
            if($_SESSION["admin"]){ //admin logged in
					header('Location: /admin/index.php');
					exit;
            }
            else{ //non-admin logged in
                if($_SESSION["type"] === 'S'){ //student logged in
					header('Location: /student/index.php');
					exit;
                }
				else if($_SESSION["type"] === 'T'){ //teacher logged in
					header('Location: /teacher/index.php');
					exit;
				}
				else if($_SESSION["type"] === 'P'){ //parent logged in
					header('Location: /parent/index.php');
					exit;
				}
				else{ //type doesn't match
					//header('Location: error.php');
					//exit;
				}
            }
        }
        else{
            echo "Passwords did not match. Please try again.";
            //redirect to new page for sign up/recovering password/signing in again
        }
    }
}
else{
            if($_SESSION["admin"]){ //admin logged in
					header('Location: /admin/index.php');
					exit;
            }
            else{ //non-admin logged in
                if($_SESSION["type"] === 'S'){ //student logged in
					header('Location: /student/index.php');
					exit;
                }
				else if($_SESSION["type"] === 'T'){ //teacher logged in
					header('Location: /teacher/index.php');
					exit;
				}
				else if($_SESSION["type"] === 'P'){ //parent logged in
					header('Location: /parent/index.php');
					exit;
				}
				else{ //type doesn't match
					//header('Location: error.php');
					//exit;
				}
            }
}
?>
</body>

</html>
