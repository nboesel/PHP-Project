<?php
if ( ! isset($_GET['name']) || strlen($_GET['name']) < 1)  {
	die('Name parameter missing');

}

if ( isset($_POST['logout']) ) { 
	header('Location: index.php');
	return;
}
require_once "pdo.php";

$failure = false;
$success = false;

if ( isset($_POST['make']) && isset($_POST['year']) 
     && isset($_POST['mileage'])) {
    	if (!is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])) {
    		$failure = "Mileage and year must be numeric";
		 }  else {
		 	if (strlen($_POST['make']) < 1) {
		 		$failure = "Make is required";
		 	}   else {
		 		$success = "Record inserted";
		 		$sql = "INSERT INTO autos (make, year, mileage)
		 			 	VALUES (:mk, :yr, :mile)";
		 			 echo("<pre>\n".$sql.'\n</pre>\n');
		 			 $stmt = $pdo->prepare($sql);
    				 $stmt->execute(array(
        			 ':mk' => $_POST['make'],
        			 ':yr' => $_POST['year'],
       			     ':mile' => $_POST['mileage']));
		 	}
		 }
} 
?>   

<!DOCTYPE html>
<html>
<head>
<title> Nick Boesel's Auto Tracker </title>
</head>
<body>
<div class = "container">
<h1>
	<?php
	if ( isset($_REQUEST['name'])) {
		echo "<p> Tracking autos for: ";
		echo htmlentities($_REQUEST['name']);
		echo "<p>\n";
	}
?>
</h1>
<?php
if ($failure !== false ) {
	echo('<p style="color: red;>">'.htmlentities($failure)."</p>\n");
}
if ($success !== false ) {
	echo('<p style="color: green;>">'.htmlentities($success)."</p>\n");
}
?>
<form method="post">
<p>Make:
<input type="text" name="make" size="60"/></p>
<p>Year:
<input type="text" name="year"/></p>
<p>Mileage:
<input type="text" name='mileage'/></p>
<input type ="submit" value = "Add">
<input type ="submit" name = "logout" value="Logout">
</form>

<h2>Auto-cars-mobiles</h2>
<ul>
	<?php
	$stmt = $pdo->query("SELECT make, year, mileage FROM autos");
	while ( $row = $stmt ->fetch(PDO::FETCH_ASSOC)) {
		echo "<li>";
		echo(htmlentities($row['year'])." ".htmlentities($row['make'])." / ".htmlentities($row['mileage']));
	}
	?>
<p>
</ul>
</div>
</body>
