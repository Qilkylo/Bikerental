<! –– Code author : Nurfairuz Binti Ahmad ––>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIKE RENTAL</title>
</head>

<body>

<style>
* {
    margin: 0;
    padding: 0;
}

body {
    background-image: url("../images/regs.jpg");
    background: #ABE7FF; /* Set the background color of the body */
    background-size: cover;
    background-position: center;
}

.main {
    width: 400px;
    margin: 100px auto 0px auto;
    margin-top: 30px;
}

.image {
    width: 350px;
    height: 350px;
    text-align: center;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.btnn {
    width: 240px;
    height: 40px;
    background: #FFB2D8;
    border: none;
    margin-top: 30px;
    margin-left: 40px;
    font-size: 18px;
    border-radius: 10px;
    cursor: pointer;
    color: #fff;
    transition: 0.4s ease;
	font-weight: bold;
}

.btnn:hover {
    background: #fff;
    color: black;
}

.btnn a {
    text-decoration: none;
    color: black;
    font-weight: bold;
}

h2 {
    text-align: center;
    padding: 20px;
    font-family: sans-serif;
    color: #fff; /* Text color for h2 */
}

.register {
    background-color:#0E79C8; 
    width: 100%;
    font-size: 18px;
    border-radius: 10px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    box-shadow: 2px 2px 15px rgba(0, 0, 0, 0.3);
    color: #000; /* Text color for the form */
    margin: auto;
}

form#register {
    margin: 40px;
    margin-top: 10px;
}

label {
    font-family: sans-serif;
    font-size: 18px;
    font-style: italic;
    color: #fff; /* Text color for labels */
}

input#name {
    width: 300px;
    border: 1px solid #ddd;
    border-radius: 3px;
    outline: 0;
    padding: 7px;
    background-color: #fff;
    box-shadow: inset 1px 1px 5px rgba(0, 0, 0, 0.3);
}

#back {
    width: 100px;
    height: 40px;
    background: #FFB2D8;
    border: none;
    margin-top: 10px;
    margin-left: 20px;
    font-size: 18px;
	color: black;
	transition: 0.4s ease;
}

#back:hover {
    background: white;
}

#back a {
    text-decoration: none;
    color: black;
    font-weight: bold;
}

#fam {
    color: #ff7200;
    font-family: 'Times New Roman';
    font-size: 50px;
    padding-left: 20px;
    margin-top: -10px;
    text-align: center;
    letter-spacing: 2px;
    display: inline;
    margin-left: 250px;
}

.reg {
    width: 100%;
}

</style>

<?php

require_once('connection.php');

$bikeid = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($bikeid <= 0) {
    echo "Invalid bike ID";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bikename = mysqli_real_escape_string($con, $_POST['bikename']);
    $avail = intval($_POST['avail']);
    $price = intval($_POST['price']);

    // Handle file upload
    if ($_FILES['fileToUpload']['size'] > 0) {
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);

        // Update database with new image filename
        $sql = "UPDATE bikes SET BIKE_NAME='$bikename', AVAILABILITY=$avail, PRICE=$price, BIKE_IMG='" . basename($_FILES["fileToUpload"]["name"]) . "' WHERE BIKE_ID=$bikeid";
    
	} else {
        
		// Update database without changing the image
        $sql = "UPDATE bikes SET BIKE_NAME='$bikename', AVAILABILITY=$avail, PRICE=$price WHERE BIKE_ID=$bikeid";
    }

    if (mysqli_query($con, $sql)) {
        header("Location: adminbike.php");
        exit();
    
	} else {
        echo "Error updating record: " . mysqli_error($con);
    }
}

// Fetch bike details for displaying in the form
$sql = "SELECT * FROM bikes WHERE BIKE_ID = $bikeid";
$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $bikename = $row["BIKE_NAME"];
    $avail = $row["AVAILABILITY"];
    $price = $row["PRICE"];
    $bikephoto = $row["BIKE_IMG"];
}

?>

<button id="back"><a href="adminbike.php">BACK</a></button> 
    
	<div class="main">
        
        <div class="register">
        
		<h2>Update Details Of Bike</h2>
        
		<form id="register"  action="adminbikeupdate_action.php" method="POST" enctype="multipart/form-data">    
            <label>Bike Name : </label>
            <br>
            <input type ="text" name="bikename" id="name" placeholder="Enter Bike Name" value="<?=$bikename?>">
            <br><br>

            <label>Availability : </label>
            <br>
            <input type="number" name="avail" id="name" placeholder="Enter Availability Of Bike" value="<?=$avail?>">
            <br><br>
            
            <label>Price : </label>
            <br>
            <input type="number" name="price" min="1" id="name" placeholder="Price per One Hour(in Ringgit Malaysia)" value="<?=$price?>">
            <br><br>

			<!-- Display Bike Image -->
			<label>Bike Image:</label><br>
			<img src="<?= 'images/' . $bikephoto ?>" alt="Bike Image" style="max-width: 300px; height: auto;">
			<br><br>

			<!-- Update Photo -->
			<label>Update Photo:</label><br>
			<label>Max size: 488.28KB</label>
			<br>
			<input type="file" name="fileToUpload" accept=".jpg, .jpeg, .png">
			<br><br>
			
			<input type="hidden" name="bikeid" value="<?php echo $bikeid; ?>">

			<input type="submit" class="btnn"  value="UPDATE" name="updatebike"></input>     
        </form>
        
		</div> 
    </div>
</body>
</html>