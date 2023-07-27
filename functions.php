<?php
function getOrCreateCohortID($form, $section, $sex, $pdo){
		$sql = "SELECT * FROM cohort WHERE form=? AND section=? AND sex=?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$form,$section,$sex]);
		$cohort = $stmt->fetch(PDO::FETCH_ASSOC);
		$cohort_id = $cohort["cohort_id"];
		if ($cohort_id == ""){
			$sql2 = "INSERT INTO cohort (form, section, sex) VALUES (?,?,?)";
			$stmt2 = $pdo->prepare($sql2);
			$stmt2->execute([$form,$section,$sex]);
			return getOrCreateCohortID($form, $section, $sex, $pdo);
		}
		return $cohort_id;
	}
	
	function getCohortID($form, $section, $sex, $pdo){
		$sql = "SELECT * FROM cohort WHERE form=? AND section=? AND sex=?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$form,$section,$sex]);
		$cohort = $stmt->fetch(PDO::FETCH_ASSOC);
		$cohort_id = $cohort["cohort_id"];
		return $cohort_id;
	}
	
	function cohort_getForm($cohort_id, $pdo){
		$sql = "SELECT form FROM cohort WHERE cohort_id=?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$cohort_id]);
		$cohort = $stmt->fetch(PDO::FETCH_ASSOC);
		$cohort_form = $cohort["form"];
		return $cohort_form;
	}
	
	function cohort_getFormSection($cohort_id, $pdo){
		$sql = "SELECT form, section FROM cohort WHERE cohort_id=?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$cohort_id]);
		$cohort = $stmt->fetch(PDO::FETCH_ASSOC);
		if($cohort["section"] != ""){
			$cohort_formsection = $cohort["form"] . "-" . $cohort["section"];
		}
		else{
			$cohort_formsection = $cohort["form"];
		}
		return $cohort_formsection;
	}
	
	function cohort_getFormSectionSex($cohort_id, $pdo){
		$sql = "SELECT form, section, sex FROM cohort WHERE cohort_id=?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$cohort_id]);
		$cohort = $stmt->fetch(PDO::FETCH_ASSOC);
		if($cohort["section"] != ""  && $cohort["sex"] != ""){
			$cohort_formsectionsex = $cohort["sex"] . ": " . $cohort["form"] . "-" . $cohort["section"];
		}
		else{
			$cohort_formsectionsex = $cohort["form"];
		}
		return $cohort_formsectionsex;
	}

    function cohort_getFormSectionSexHeader($cohort_id, $pdo){
		$sql = "SELECT form, section, sex FROM cohort WHERE cohort_id=?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$cohort_id]);
		$cohort = $stmt->fetch(PDO::FETCH_ASSOC);
		if($cohort["section"] != ""  && $cohort["sex"] != ""){
			$cohort_formsectionsex = cohortSexToString($cohort["sex"]) . " Form " . $cohort["form"] . "-" . $cohort["section"];
		}
		else{
			$cohort_formsectionsex = $cohort["form"];
		}
		return $cohort_formsectionsex;
	}

    function cohort_getFormSectionSexHeader2($cohort_id, $pdo){
		$sql = "SELECT form, section, sex FROM cohort WHERE cohort_id=?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$cohort_id]);
		$cohort = $stmt->fetch(PDO::FETCH_ASSOC);
		if($cohort["section"] != ""  && $cohort["sex"] != ""){
			$cohort_formsectionsex = $cohort["form"] . "-" . $cohort["section"] . " (" . cohortSexToString($cohort["sex"]) . ")";
		}
		else{
			$cohort_formsectionsex = $cohort["form"];
		}
		return $cohort_formsectionsex;
	}
	
	function cohort_getSex($cohort_id, $pdo){
		$sql = "SELECT sex FROM cohort WHERE cohort_id=?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$cohort_id]);
		$cohort = $stmt->fetch(PDO::FETCH_ASSOC);
		$cohort_sex = $cohort["sex"];
		return $cohort_sex;
	}
	
	function cohort_getAll($cohort_id, $pdo){
		$sql = "SELECT * FROM cohort WHERE cohort_id=?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$cohort_id]);
		$cohort = $stmt->fetch(PDO::FETCH_ASSOC);
		$cohort_form = $cohort["form"];
		$cohort_section = $cohort["section"];
		$cohort_sex = $cohort["sex"];
		return array($cohort_form, $cohort_section,$cohort_sex);
	}
	//$cohortArray = cohort_getAll($cohort_id, $pdo);
	//$cohortForm = $cohortArray[0];
	//$cohortSection = $cohortArray[1];
	//$cohortSex = $cohortArray[2];
    function class_getAllCohort($class_id, $pdo){
		$sql = "SELECT *, cohort.sex as c_sex FROM class, cohort WHERE class_id = ? AND class.cohort_id = cohort.cohort_id";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$class_id]);
		$cohort = $stmt->fetch(PDO::FETCH_ASSOC);
		$cohort_form = $cohort["form"];
		$cohort_section = $cohort["section"];
		$cohort_sex = $cohort["c_sex"];
		return array($cohort_form, $cohort_section,$cohort_sex);
	}
    
    function class_getClassID($cohort_id, $subject_id, $year, $semester, $pdo){
        $sql = "SELECT class_id FROM class WHERE cohort_id = ? AND subject_id = ? AND year = ? AND semester = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$cohort_id, $subject_id, $year, $semester]);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            return $row["class_id"];
        }
            return NULL;
    }
    
    function classStudent_getClassID($student_id, $subject_id, $year, $semester, $pdo){
         $sql = "SELECT class.class_id FROM class, stpuser, class_student WHERE stpuser.user_id = ? AND class_student.student_id = stpuser.user_id AND class_student.class_id = class.class_id AND stpuser.cohort_id = class.cohort_id AND class.subject_id = ? AND class.year = ? AND class.semester = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$student_id, $subject_id, $year, $semester]);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            return $row["class_id"];
        }
            return NULL;
    }
	
	function user_getFullName($user_id, $pdo){
		$sql = "SELECT fname, lname FROM stpuser WHERE user_id = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$user_id]);
        while ($user = $stmt->fetch(PDO::FETCH_ASSOC)){
            $fname = $user["fname"];
            $lname = $user["lname"];
            $fullname = $fname . " " . $lname;
            return $fullname;
        }
		return NULL;
	}
	
	function getAllUsers($pdo){
		$sql = "SELECT * FROM stpuser ORDER BY user_id";
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		return $stmt;
	}
	
	// function cohortToString($cohort_id, $pdo){
		// $sql = "SELECT * FROM cohort WHERE cohort_id=?";
		// $stmt = $pdo->prepare($sql);
		// $stmt->execute([$cohort_id]);
		// $cohort = $stmt->fetch(PDO::FETCH_ASSOC);
		// $form = $cohort["form"];
		// $section = $cohort["section"];
		// $sex = $cohort["sex"];
		// if($sex == 'M'){
			// $sex == "Boys";
		// }
		// else{
			// $sex == "Girls";
		// }
		// // return "Form " . $form . $section . " - " . $sex;
		// edit to return null if form is empty, etc...
	 // }
	
	//function cohortString($form, $section, $sex, $pdo){
		//$sql = "SELECT * FROM cohort WHERE form=? AND section=? AND sex=?";
		//$stmt = $pdo->prepare($sql);
		//$stmt->execute([$form,$section,$sex]);
		//$cohort = $stmt->fetch(PDO::FETCH_ASSOC);
		//$cohort_id = $cohort["cohort_id"];
		//$form = $cohort["form"];
		//$section = $cohort["section"];
		//$sex = $cohort["sex"];
		//if($sex == 'M'){
		//	$sex == "Boys";
		//}
		//else{
		//	$sex == "Girls";
		//}
		//return "Form " . $form . $section . " - " . $sex;
	//}
	
	function monthToSemesterID ($month){
		if ($month < 7){
			$semesterID = '1';
		}
		else{
			$semesterID = '2';
		}
		return $semesterID;
	}

    function semesterIDtoString ($semester_id){
		if ($semester_id == '1'){
			$semester_id = "January-May";
		}
		else{
			$semester_id = "July-November";
		}
		return $semester_id;
	}

    function monthYeartoSemesterString ($month, $year){
        $semester_id = monthToSemesterID($month);
		if ($semester_id == '1'){
			$semester = "January-May";
		}
		else{
			$semester = "July-November";
		}
		return $semester . ', ' . $year;
	}
	
	function getSubjectName($subject_id, $pdo){
		$sql_subjname = "SELECT subject_name FROM subject WHERE subject_id = ?";
		$stmt_subjname = $pdo->prepare($sql_subjname);
		$stmt_subjname->execute([$subject_id]);
		$row_subjname = $stmt_subjname->fetch(PDO::FETCH_ASSOC);
		$subject_name = $row_subjname["subject_name"];
		return $subject_name;
	}
	
	function userTypeToString($type_abbreviation){
		if ($type_abbreviation === 'S'){
			$user_type = "Student";
		}
		else if ($type_abbreviation === 'T'){
			$user_type = "Teacher";
		}
		else if ($type_abbreviation === "P"){
			$user_type = "Parent";
		}
		else{
			$user_type = "";
		}
		return $user_type;
	}
	
	function coursework_typeToString($type_abbreviation){
		if ($type_abbreviation === 'A'){
			$coursework_type = "Assignment";
		}
		else if ($type_abbreviation === 'T'){
			$coursework_type = "Test";
		}
		else if ($type_abbreviation === "Q"){
			$coursework_type = "Quiz";
		}
		else if ($type_abbreviation === "E"){
			$coursework_type = "Exam";
		}
		else if ($type_abbreviation === "FE"){
			$coursework_type = "Final Exam";
		}
		else{
			$coursework_type = "";
		}
		return $coursework_type;
	}

    function fee_typeToString($type){
        if ($type === 'S'){
            $type = "School";
        }
        else if ($type === 'CM'){
            $type = "Computer";
        }
        else if ($type === "E"){
            $type = "Emergency";
        }
        else if ($type === "H"){
            $type = "Health";
        }
        else {
            $type = "Other";
        }
        return $type;
    }
	
	function class_getName($class_id, $pdo){
		$sql = "SELECT cohort.form, cohort.section, cohort.sex, subject.subject_name FROM class, subject, cohort WHERE class.class_id = ? AND class.subject_id = subject.subject_id AND class.cohort_id = cohort.cohort_id";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$class_id]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$form_num = $row["form"];
		$section = $row["section"];
		$subject_name = $row["subject_name"];
		$sex = $row["sex"];
		$gender = cohortSexToString($sex);
		$class_name = $gender . " Form " . $form_num . "-" . $section . " " . $subject_name; 
		return $class_name;
	}

    function class_getName2($class_id, $pdo){
		$sql = "SELECT cohort.form, cohort.section, cohort.sex, subject.subject_name, class.semester, class.year FROM class, subject, cohort WHERE class.class_id = ? AND class.subject_id = subject.subject_id AND class.cohort_id = cohort.cohort_id";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$class_id]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$form_num = $row["form"];
		$section = $row["section"];
		$subject_name = $row["subject_name"];
		$sex = $row["sex"];
        $semesterID = $row["semester"];
        $year = $row["year"];
        $semester = semesterIDtoString($semesterID);
		$gender = cohortSexToString($sex);
        if ($subject_name != ''){
		  $class_name = $gender . " Form " . $form_num . "-" . $section . " " . $subject_name . " (" . $semester . ", " . $year . ")"; 
        }
        else{
            $class_name = NULL;
        }
		return $class_name;
	}
	
	function userSexToString($sex_abbreviation){
		if ($sex_abbreviation === 'M'){
			$user_sex = "Male";
		}
		else if ($sex_abbreviation === 'F'){
			$user_sex = "Female";
		}
		else{
			$user_sex = "";
		}	
		return $user_sex;
	}

function userSexToGender($sex_abbreviation){
		if ($sex_abbreviation === 'M'){
			$user_gender = "Boys";
		}
		else if ($sex_abbreviation === 'F'){
			$user_gender = "Girls";
		}
		else{
			$user_gender = "";
		}	
		return $user_gender;
	}
	
	function cohortSexToString($sex_abbreviation){
		if ($sex_abbreviation === 'M'){
			$cohort_sex = "Boys";
		}
		else if ($sex_abbreviation === 'F'){
			$cohort_sex = "Girls";
		}
		else{
			$cohort_sex = "";
		}	
		return $cohort_sex;
	}
	
	function userIsAdminToString($admin_boolean){
		if ($admin_boolean == '0'){
			$admin_boolean = "";
		}
		else{
			$admin_boolean = "yes";
		}
		return $admin_boolean;
	}
	
	function getOrCreateResult($student_id, $work_id, $pdo){
		$sql = "SELECT points_earned FROM coursework_result WHERE student_id = ? AND work_id = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$student_id, $work_id]);
		$empty = TRUE;
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$empty = FALSE;
			$points_earned = $row["points_earned"];
		}
		if ($empty){
			$sql = "INSERT INTO coursework_result (work_id, student_id) VALUES (?,?)";
			$stmt = $pdo->prepare($sql);
			$stmt->execute([$work_id, $student_id]);
			$points_earned = 0;
		}
		return $points_earned;
	}
	
	function date_getMonth($date){
		return substr($date, 5, 2);
	}
	
	function date_getDay($date){
		return substr($date, 8, 2);
	}
	
	function date_getYear($date){
		return substr($date, 0,4);
	}
	
	function date_getMonthString($date){
		$month = substr($date, 5, 2);
		$monthString = "";
		if ($month == 1){
			$monthString = "January";
		}
		else if ($month == 2){
			$monthString = "February";
		}
		else if ($month == 3){
			$monthString = "March";
		}
		else if ($month == 4){
			$monthString = "April";
		}
		else if ($month == 5){
			$monthString = "May";
		}
		else if ($month == 6){
			$monthString = "June";
		}
		else if ($month == 7){
			$monthString = "July";
		}
		else if ($month == 8){
			$monthString = "August";
		}
		else if ($month == 9){
			$monthString = "September";
		}
		else if ($month == 10){
			$monthString = "October";
		}
		else if ($month == 11){
			$monthString = "November";
		}
		else if ($month == 12){
			$monthString = "December";
		}
		return $monthString;
	}

	function coursework_getTotalPoints($work_id, $pdo){
		$sql = "SELECT coursework.totalpoints FROM coursework WHERE coursework.work_id = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$work_id]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row["totalpoints"];
	}

    function coursework_getType($work_id, $pdo){
		$sql = "SELECT coursework.type FROM coursework WHERE coursework.work_id = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$work_id]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row["type"];
	}

    function class_getTeacherID($class_id, $pdo){
		$sql = "SELECT class.teacher_id FROM class WHERE class.class_id = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$class_id]);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            return $row["teacher_id"];
        }
		return NULL;
	}

    function class_getTeacherName($class_id, $pdo){
		$sql = "SELECT stpuser.fname, stpuser.lname FROM class, stpuser WHERE class.class_id = ? AND stpuser.user_id = class.teacher_id";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$class_id]);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $fname = $row["fname"];
            $lname = $row["lname"];
            $fullname = $fname . " " . $lname;
            return $fullname;
        }
		return NULL;
	}

    function class_hasStudent($class_id, $student_id, $pdo){
        $sql = "SELECT COUNT(*) as has_student FROM class_student WHERE class_id = ? AND student_id = ?";
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$class_id, $student_id]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row["has_student"]){
            return TRUE;
        }
        else{
            return FALSE;
        }
    }
?>
