<meta name="viewport" content="width=device-width">
<?php
ob_start();
echo '<h1 class="sitetitle">';
echo "Ruhuwiko</h1>";
echo '<h2 class="sitedescription">';
echo "Secondary School - Ruhuwiko, Tanzania</h2>";
echo '<div class="dropdown">
	<div class="btn"><a href="/school.php">Home</a></div>
</div>
<div class="dropdown">
  <button class="dropbtn">Parent</button>
  <div class="dropdown-content">
	<a href="/parent/index.php">Home</a>
  </div>
</div>
<div class="dropdown">
	<div class="btn"><a href="/profile.php">My Account</a></div>
</div>
<div class="dropdown">
	<div class="btn"><a href="/logout.php">Log Out</a></div>
</div>';
echo " ";
?>
