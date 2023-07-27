<script type="text/javascript">
    <!--
    function Redirect() {
        window.location = "/logout.php";
    }

    function Warn() {
        window.alert("Still there? You will be logged out in 1 minute.");
    }
    setTimeout('Warn()', 240000);
    setTimeout('Redirect()', 3000000);
    //-->

</script>
<meta name="viewport" content="width=device-width">
<?php 
echo '<h1 class="sitetitle">';
echo "School Management System'S</h1>";
echo '<h2 class="sitedescription">';
echo "Secondary School - Ruhuwiko, Tanzania</h2>";
echo '<div class="dropdown">
	<div class="btn"><a href="/teacher/index.php">My Classes</a></div>
</div>
<div class="dropdown">
  <button class="dropbtn">Admin Portal</button>
  <div class="dropdown-content">
    <a href="/admin/index.php">Manage Classes</a>
    <a href="/admin/currentclasses.php">Current Classes</a>
    <a href="/admin/manageusers.php">User Information</a>
    <a href="/admin/changepasswords.php">Reset Passwords</a>
  </div>
</div>
<div class="dropdown">
  <button class="dropbtn">Academic Reports</button>
  <div class="dropdown-content">
  <a href="/admin/formreport.php">First Term</a>
    <a href="/admin/formreport0.php">January</a>
    <a href="/admin/formreport1.php">February</a>
    <a href="/admin/formreport2.php">March</a>
    <a href="/admin/formreport3.php">April</a>
    <a href="/admin/formreport.php">Second Term</a>
    <a href="/admin/formreport0.php">July</a>
    <a href="/admin/formreport1.php">August</a>
    <a href="/admin/formreport2.php">September</a>
    <a href="/admin/formreport3.php">October</a>
  </div>
</div>
<div class="dropdown">
  <button class="dropbtn">School Fees</button>
  <div class="dropdown-content">
    <a href="/admin/managefees.php">Assign Fees</a>
    <a href="/admin/feepayment.php">Fee Payment</a>
    <a href="/admin/studentbalances.php">Student Balances</a>
    <a href="/admin/financialreport.php">Financial Reports</a>
  </div>
</div>
<div class="dropdown">
  <button class="dropbtn">Register</button>
  <div class="dropdown-content">
    <a href="/registerstudent.php">Student</a>
    <a href="/registerteacher.php">Teacher</a>
  </div>
</div>
<div class="dropdown">
	<div class="btn"><a href="/profile.php">My Account</a></div>
</div>
<div class="dropdown">
	<div class="btn"><a href="/logout.php">Log Out</a></div>
</div>'
;
echo " ";
?>
