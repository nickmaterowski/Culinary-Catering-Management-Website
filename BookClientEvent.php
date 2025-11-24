<!-- Allows Caterer Book Event for Client -->
<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['VerifiedClientID'])) {
    include('VerifyClient.php');
    exit;
}

$clientID = $_SESSION['VerifiedClientID'];
$clientF = $_SESSION['VerifiedClientFirst'];
$clientL = $_SESSION['VerifiedClientLast'];

// DB connection
include('databaseLogin.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['DateOfEvent']) && isset($_POST['FoodOrder']))  {

    $date = $_POST['DateOfEvent'];
    $food = $_POST['FoodOrder'];
    $catererID = $_SESSION['CatererID'];

    if ($date == "" || $food == "") {
        echo "<script>alert('All fields are required.');</script>";
    }
    else {
        $cateringID = rand(1, 10000);

        // Insert only the date and food - adjust column names to match your database
        $sql = "INSERT INTO ClientCateringInformation
                   (DateOfEvent, FoodOrder, ClientID, CateringID)
                   VALUES
                   ('$date', '$food', '$clientID', '$cateringID')";

        if (mysqli_query($con, $sql)) {

            $_SESSION['CurrentCateringID'] = $cateringID;

            echo "<script>
                alert('Catering event has been booked. Your Catering ID: $cateringID');
                </script>";

            unset($_SESSION['VerifiedClientID']);
            unset($_SESSION['VerifiedClientFirst']);
            unset($_SESSION['VerifiedClientLast']);

            mysqli_close($con);
            include('VerifyClient.php');
            exit;
        }
    }
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel='stylesheet' href='HomePage.css'>
    <meta charset='UTF-8'>
    <title>Book Event</title>
</head>
<body>
<div id="template">

<h1>Book Catering Event</h1>

<form method="POST" action="BookClientEvent.php" onsubmit="return validateBooking()">

    <div class="formField">
        <label>Date of Event:</label><br>
        <input type="text" name="DateOfEvent" id="DateOfEvent" placeholder="Example: November 2, 2025" style="width: 350px;"> <label> REQUIRED </label>
    </div><br>

    <div class="formField">
        <label>Food Needed:</label><br>
        <input type="text" name="FoodOrder" id="FoodOrder" placeholder="Example: Shrimp, Crab, Clams" style="width: 350px;"> <label> REQUIRED </label>
    </div><br>

    <input type="submit" class="bttn" value="Submit">

</form>

<script>
var validDate = /^[A-Za-z]+ \d{1,2}, \d{4}$/;
var validFood = /^[A-Za-z ]+(?:, [A-Za-z ]+)*$/;

function validateBooking() {
    let date = document.getElementById("DateOfEvent");
    let food = document.getElementById("FoodOrder");

    // Date validation
    if (date.value == "") {
        alert("Please enter a date.");
        date.focus();
        return false;
    }
    else if (!validDate.test(date.value)) {
        alert("Date must follow the format such as: November 2, 2025");
        date.value = "";
        date.focus();
        return false;
    }

    // Food validation
    if (food.value == "") {
        alert("Please enter the food needed.");
        food.focus();
        return false;
    }
    else if (!validFood.test(food.value)) {
        alert("Food must be entered as words separated by commas such as: Shrimp, Crab, Clams");
        food.value = "";
        food.focus();
        return false;
    }

    return true;
}
</script>

</div>
</body>
</html>