<!-- Allows Caterer to Replace Existing Supply Information for Client -->

<?php
if (!isset($_SESSION)) {
    session_start();
}
include("NavigationBar.php");

// DB connection
include('databaseLogin.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['UpdatedSupply']) && isset($_POST['UpdatedQuantity'])) {
    // Use Catering ID from the booking session
    $cateringID = $_SESSION['CurrentCateringID'] ?? null;
    $supply = $_POST['UpdatedSupply'];
    $qty = $_POST['UpdatedQuantity'];

    if ($cateringID === null || $cateringID === "") {
        echo "<script>alert('No active Catering ID found. Please book a client event first.');</script>";
        echo "
            <form id='redirectForm' action='VerifyClient.php' method='GET'></form>
            <script>
                document.getElementById('redirectForm').submit();
            </script>
        ";
        exit;
    }

    // If user already confirmed, perform update
    if (isset($_POST['confirmed']) && $_POST['confirmed'] == "yes") {

        $update = "UPDATE AdditionalEventSupplies
                   SET TypeOfAdditionalEventSupply = '$supply',
                       QuantityOfNeededAdditionalEventSupply = '$qty'
                   WHERE CateringID = '$cateringID'";

        mysqli_query($con, $update);

        echo "<script>alert('Additional services updated for Catering ID: $cateringID');</script>";
        echo "
            <form id='refreshPage' action='UpdateServices.php' method='GET'></form>
            <script>
                document.getElementById('refreshPage').submit();
            </script>
        ";
        exit;
    }

    // First check if CateringID exists
    $check = "SELECT * FROM AdditionalEventSupplies WHERE CateringID = '$cateringID'";
    $result = mysqli_query($con, $check);

    if (mysqli_num_rows($result) == 0) {
        echo "<script>alert('Catering ID does not exist. Please check the information and re-enter valid data.');</script>";
        echo "
            <form id='refreshPage' action='UpdateServices.php' method='GET'></form>
            <script>
                document.getElementById('refreshPage').submit();
            </script>
        ";
        exit;
    }

    // Ask user to confirm the update
    echo "
        <form id='confirmForm' action='UpdateServices.php' method='POST'>
            <input type='hidden' name='UpdatedSupply' value='$supply'>
            <input type='hidden' name='UpdatedQuantity' value='$qty'>
            <input type='hidden' name='confirmed' value='yes'>
        </form>

        <form id='retryForm' action='UpdateServices.php' method='POST'></form>

        <script>
            if (confirm('Are you sure you want to update the additional services for this catering event?')) {
                document.getElementById('confirmForm').submit();
            } else {
                alert('Update cancelled.');
                document.getElementById('retryForm').submit();
            }
        </script>
    ";
    exit;
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="HomePage.css">
    <meta charset="UTF-8">
    <title>Update Additional Services</title>
</head>
<body>

<div id="template">

<h1>Update Additional Catering Services</h1>

<form action="UpdateServices.php" method="POST" onsubmit="return validateUpdate()">

    <div class="formField">
        <label>Updated Service List:</label><br>
        <input type="text" name="UpdatedSupply" id="UpdatedSupply" placeholder="Example: Tables, Chairs, Utensils" style="width:350px;">
        <label>REQUIRED</label>
    </div><br>

    <div class="formField">
        <label>Updated Quantities:</label><br>
        <input type="text" name="UpdatedQuantity" id="UpdatedQuantity" placeholder="Example: Tables (20), Chairs (50), Utensils (200)" style="width:350px;">
        <label>REQUIRED</label>
    </div><br>

    <input type="submit" class="bttn" value="Submit">

</form>

</div>

<script>
var validName = /^[A-Za-z ]+(?:, [A-Za-z ]+)*$/;
var validQty = /^[A-Za-z ]+\(\d+\)(?:, [A-Za-z ]+\(\d+\))*$/;

function validateUpdate() {
    let service = document.getElementById("UpdatedSupply");
    let qty = document.getElementById("UpdatedQuantity");

    if (service.value == "") {
        alert("Please enter the updated service list.");
        service.focus();
        return false;
    }
    else if (!validName.test(service.value)) {
        alert("Service names must follow the format: Tables, Chairs, Utensils");
        service.value = "";
        service.focus();
        return false;
    }

    if (qty.value == "") {
        alert("Please enter the updated quantities.");
        qty.focus();
        return false;
    }
    else if (!validQty.test(qty.value)) {
        alert("Quantities must follow the format: Tables (20), Chairs (50), Utensils (200)");
        qty.value = "";
        qty.focus();
        return false;
    }

    return true;
}
</script>

</body>
</html>
