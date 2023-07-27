<?php
require($_SERVER["DOCUMENT_ROOT"] . "/connect.php");
//$sql = "SELECT * FROM stpuser";
		//$stmt = $pdo->prepare($sql);
		//$stmt->execute();
   // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
      //  $sql1 = "UPDATE stpuser SET fname = ? WHERE stpuser.user_id = ?";
		//$stmt1 = $pdo->prepare($sql1);
		//$stmt1->execute([ucfirst(strtolower($row["fname"])), $row["user_id"]]);
       //  $sql2 = "UPDATE stpuser SET lname = ? WHERE stpuser.user_id = ?";
		//$stmt2 = $pdo->prepare($sql2);
	//	$stmt2->execute([ucfirst(strtolower($row["lname"])), $row["user_id"]]);
        //$sql1 = "UPDATE stpuser SET fname = ? WHERE stpuser.user_id = ?";
   // }
?>
    <html>

    <head>
        <link rel="stylesheet" type="text/css" href="../css/main.css">
        <script src="../js/jquery-3.3.1.min.js"></script>
        <script src="../js/form-validator/jquery.form-validator.min.js"></script>
        <script>
            $(document).ready(function() {
                $('html').dblclick(function() {
                    $('h1, h2').slideToggle();
                }); // end double click
            }); // end ready

        </script>
    </head>
    <div class="main">

        <body>
            <?php
            //echo "" . $_SESSION["username"];
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
                <h1 class="sitetitle">School Management System</h1>
                <h2 class="sitedescription">Secondary School - Ruhuwiko, Tanzania</h2>
                <form class="login" method="post" action="">
                    <table>
                        <tr>
                            <td>Username:</td>
                            <td><input required type="text" name="email"></td>
                        </tr>
                        <tr>
                            <td>Password:</td>
                            <td><input required type="password" name="passwordattempt"></td>
                        </tr>
                        <tr>
                            <td><input class="loginbtn" type="Submit" name="login" value="Log In"></td>
                            <td><a href="forgotpassword.php">Forgot Password?</a>
                            </td>
                        </tr>
                    </table>
                </form>
                <?php
	if (isset($_POST["login"])){
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
			$passAttempt = $_POST["passwordattempt"];
			$password = $row["password"];
			$auth = password_verify($passAttempt, $password);
			if($auth){
				$_SESSION['logged_in'] = true;
				$_SESSION['user_id'] = $row['user_id'];
                $_SESSION['username'] = $row['email'];
                $_SESSION['password'] = $row['password'];
				$_SESSION['type'] = $row['type'];
				$_SESSION['admin'] = $row['admin'];
				$_SESSION['fname'] = $row['fname'];
				$_SESSION['lname'] = $row['lname'];
                $_SESSION['cohort_id'] = $row['cohort_id'];
                $_SESSION['activated'] = $row['activated'];
				$ip = getenv('HTTP_CLIENT_IP')?:
				getenv('HTTP_X_FORWARDED_FOR')?:
				getenv('HTTP_X_FORWARDED')?:
				getenv('HTTP_FORWARDED_FOR')?:
				getenv('HTTP_FORWARDED')?:
				getenv('REMOTE_ADDR');
				$sql2 = "UPDATE stpuser SET last_login = CURRENT_TIMESTAMP WHERE email = ?";
				$stmt2 = $pdo->prepare($sql2);
				$stmt2->execute([$row["email"]]);
				$sql3 = "UPDATE stpuser SET last_login_ip = ? WHERE email = ?";
				$stmt3 = $pdo->prepare($sql3);
				$stmt3->execute([$ip, $row["email"]]);
                if(!$_SESSION['activated']){
                    header('Location: /welcome.php');
                    exit;
                }
				if($row["admin"]){ //admin logged in
					header('Location: /admin/index.php');
					exit;
				}
				else{ //non-admin logged in
					if($row["type"] === 'S'){ //student logged in
						header('Location: /student/index.php');
						exit;
					}
					else if($row["type"] === 'T'){ //teacher logged in
						header('Location: /teacher/index.php');
						exit;
					}
					else if($row["type"] === 'P'){ //parent logged in
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
				echo "Incorrect username / password combination";
				//redirect to new page for sign up/recovering password/signing in again
			}
		}
		
		
	}
	else{
	}
    }
//add teacher_id in submit class(for now always add to same teacher) ***
//populate user table (students, teachers, etc.) with pre-generated insert statements
//add date and ip address insertion upon student, teacher, parent sign-up  *** STILL NEED TO add IP address
/////create way for students to become activated (admin?).. [how to send an email from php (Activation)]
//make students inactive upon initial registration (DEFAULT FALSE) ***
/////error handling for duplicate classes added
/////format phone number input in registration form
/////re-format dob picking in registration
/////storing photos and other files in database (BLOB?)
//password confirmation on registration ****
//storing encrypted passwords in database after registration ****
//change user booleans to a single usertype ENUM(S,T,P)... ****
//have boolean for active students ***
//admin attribute in user table (give special privileges and control over system) ****
/////send activation email
//update date of last login upon signing in as user ****
//admin log-in and different view ***
/////prompt if forgotten password for password reset email
//redirect log-in page ***
/////refill with previous values on incorrect password confirmation
/////error handling for registration attempt with pre-registered email address
/////add security so unauthorized user can't access page by typing in url
//pass user information when redirecting page after login... save session attributes? ****
/////option to edit user information after login
/////student class registration (by admin and by student)
//preset functions for... *****
//1) getting subject name from subject_id *****
//2) converting semester id number to string (ex. January-May) *****
//fix undefined type error if no log-in ***
//show student as enrolled after clicking class_register link ****
//ability for student to drop classes ****
/////show student's current term and future classes in table(s) on student index
//message if the student is not enrolled in any classes
//admin ability to create new subject in database *****
//admin ability to edit subjects in database 
//admin ability to remove subjects from database *****
//admin ability to create classes in database *****
//admin ability to edit classes
//admin ability to delete classes from database *****
//admin ability to assign teachers to classes *****
//admin ability to manage class enrollment (remove students from class, etc.)
//admin ability to view all classes and grades in real time (also print reports)
//admin ability to authorize new account as admin?? 
//admin ability to view all teachers and their information
//admin ability to view all students and their information
//WHAT TO DO WITH activated attribute in stpuser table? (default 0 or 1??)
	//admin ability to activate/approve new users for site access?
//error handling/redirection for invalid urls
//PAYMENT HANDLING!
	//for parents or students??
		//parent ability to link to students
		//parent ability to view a student's fees
		//parent ability to pay for a student's fees
		//parent ability to view and print financial report
//automatic insert statement for admin user in sqlqueries
//order student registered classes table by year, semester, form...
//
//-----------------------
//fee table *****
//attributes: fee_id, fee_type, student_id, amount, balance, boolean paid, *****
//Grades: Average of Mazoezi and Final Exam (Majar) -- A {100-81) B (80-65) C(64-46) D(45-30) F(29-0) *****
// fees: school, computer maintenance, emergency, health *****
// gender in class table.. *****
//option for students to add non-core classes (computer, bible, etc.)
	//add boolean core attribute to class table?
//admin ability to view all classes... add and remove classes from list below similar to how students were able to search and drop
//ask if admin is sure they want to delete a class
//show admin current (or all) classes without search. enable them to edit, delete, assign/edit teachers
//allow admin to add class with remarks/description
//enable admin to add class for boys/girls??? variable for gender?
//warnings when deleting subjects to not mess up foreign keys... option to edit subject name (and only delete subjects that aren't tied to classes?)
//warnings after all attempted DB deletions
//refreshing css/cookies on localhost ***** (CTRL+F5)
//make classes searchable by boys/girls as admin *****
//delete all students from class in student_class, then delete class.
//don't allow admin to add empty subject
//fees for specific cohorts *****
//number input type on registration
//allow users to change their passwords
//admin ability to remove fees *****
//add cohort_id to students *****
//add cohort id to class *****
//add section attribute to
//convert form, section, gender combinations to cohort_ids *****
//remove gender from class... should be cohort_id instead *****
//DEAL WITH EXCEPTION/ERROR when trying to add a duplicate cohort.. also duplicate subject 
// Warning: Cannot modify header information - headers already sent by (output started at ...
		//ob_start temporary fix
//searching by single attributes as admin
//student page to add/request cohort access after registration
//throw error if user tries to register with pre-existing email
//show only cohort form after initial student registration
//add date type to fees
//catch exception/error when inserting duplicate fee as admin
//getting sum of balances from fee_student from PDO row.. "undefined index" *****
//multi-variable searching from admin index
//option to assign fees to individual student or entire cohort
//don't allow teacher to view students, post coursework, etc. by posting courseid in url for past/future classes... secure these areas
//don't allow duplicate coursework to be uploaded/added.. catch error/exception when attempting to
//set today's date as default in create coursework date picker
//update points earned if entry already exists...
	//create coursework_result entries for all students in class with null points earned upon creating coursework?
		//update upon edit
//add coursework type in upload results header
//format percentage decimal points when uploading results
//automatically assign student to all core cohort classes following registration ... only for the current/upcoming semester!
//don't factor in un-updated results to final grade view
//prompt to ask if they are sure they want to delete all assigned fees when removing a created fee
//fix grading and ranking for new students with unrecorded/un-uploaded coursework
//firefox links
//automatic registration only for a set of core classes... elective/manual registration for the others
//allow for fee assignment to multiple cohorts and more than one single student at a time
//fix section attribute in student "class registration" search
// only allow students to access classes that they are registered for
//REGISTRATION FORM PASSWORD VALIDATION (min amount of chars, etc.)
//add ability to change student cohort for re-registration at conclusion of a semester
//myresults divide by zero error
//show student information in All students table when clicking edit from admin index "search classes"
//teacher/viewstudents ... new student registered/added to class without pre-existing coursework result gives offset table
//dropping a student from a class in student/index should remove all coursework_result entries for the student in that respective class
// teacher/uploadresults .. button to say upload before uploaded... then option to edit.
//Option to edit and upload for all students rather than a single student
//option to add final exam only in may and december (coursework.php) ...
//only able to add one final exam per class
//username for students vs. email
//array for monthly grades in viewstudents
//checks to make sure for coursework created is within the given year/semester(months) of the if ($curMonth<=6){
//arrange viewstudents by final grade rather than pointsearned (custom orderby?)
//make viewstudents grading work for second semester
//restricting date input for coursework creation (only current semester, etc.)
//detailedresults teacher vs admin check.. make similar to viewstudents
//creating an empty PHP multidimensional array??
//change "percentage_grade" to finalexam_percentage
//detailedresults/viewstudents... change default coursework results value from NULL to -1 OR... find a way to check for NULL value in sql query ****
    
//SELECT coursework_result.student_id, MONTH(coursework.date) AS coursework_month, SUM(coursework_result.points_earned) AS sum_pointsearned, SUM(coursework.totalpoints) AS sum_totalpoints, (SUM(coursework_result.points_earned)/SUM(coursework.totalpoints) * 100) AS percentage FROM stpuser, coursework, coursework_result WHERE stpuser.user_id 
//							= coursework_result.student_id AND coursework.work_id = coursework_result.work_id AND 
//							coursework.class_id = 3 AND stpuser.user_id = 36 AND coursework_result.points_earned IS NOT NULL GROUP BY coursework_month ORDER BY coursework_month DESC, coursework.date DESC
    //have option to delete uploaded coursewor
    //create student report with all classes in a given semester
    //only allow final exam to be created in May (javascript??)
    //catch error on duplicate coursework addition
    //value = "" showing up as 0 until refresh (ctrl+f5)
    
    //subject report (studentreports.php)
    //SELECT *,  FROM stpuser, class, cohort, coursework, coursework_result WHERE class.cohort_id = 29 AND cohort.cohort_id = class.cohort_id AND class.class_id = coursework.class_id AND coursework.work_id = coursework_result.work_id AND stpuser.user_id = coursework_result.student_id GROUP BY class.class_id, stpuser.user_id
    //check for appropriate year and semester in studentreports query...
    //only allow one class per subject in a given cohort and year/semester
    //ties in ranks for student reports
    //adminheader in detailedresults not showing ***
    //studentreport not refreshing without first visiting studentreports
    //catch error when providing invalid position in studentreport url param
    //use student_id as param instead of position in studentreport (need to get student array differently)
    //form resubmission backspace issue from studentreport to studentreports
    //cohortreport only for cohorts of valid year and semester
    //add titles to all pages
    //add semester and year + form information in individual student report
    //student/detailedresults.php ... check to make sure student is in class, (and class is of current semester/year?)... show message if not
?>
        </body>
    </div>

    </html>
