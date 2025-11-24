<!-- Allows Caterer to Add Supply Information for Client -->
<?php
if (!isset($_SESSION)) {
    session_start();
}
include("NavigationBar.php");

// DB connection
include('databaseLogin.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" 
    && isset($_POST['NewSupply']) 
    && isset($_POST['NewQuantity'])) 
{
    // Use the Catering ID from the booking session
    $cateringID = $_SESSION['CurrentCateringID'] ?? null;
    $supply = $_POST['NewSupply'];
    $qty = $_POST['NewQuantity'];

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

    // If user confirms, proceed
    if (isset($_POST['confirmed']) && $_POST['confirmed'] == "yes") {
        $getClientID = "SELECT ClientID FROM ClientCateringInformation WHERE CateringID = '$cateringID'";
        $clientResult = mysqli_query($con, $getClientID);
        $clientRow = mysqli_fetch_assoc($clientResult);
        $clientID = $clientRow['ClientID'];

        // Get previous values to append
        $checkSupplies = "SELECT TypeOfAdditionalEventSupply, QuantityOfNeededAdditionalEventSupply
                  FROM AdditionalEventSupplies
                  WHERE CateringID = '$cateringID'";
        $suppliesResult = mysqli_query($con, $checkSupplies);

        if (mysqli_num_rows($suppliesResult) > 0) {
            $row = mysqli_fetch_assoc($suppliesResult);
            $oldSupply = $row['TypeOfAdditionalEventSupply'];
            $oldQty = $row['QuantityOfNeededAdditionalEventSupply'];

            // Submit data into table based on if data exists already or not
            if ($oldSupply == "") {
                $newSupplyFinal = $supply;
            } else {
                $newSupplyFinal = $oldSupply . ", " . $supply;
            }

            if ($oldQty == "") {
                $newQtyFinal = $qty;
            } else {
                $newQtyFinal = $oldQty . ", " . $qty;
            }

            // Update table
            $update = "UPDATE AdditionalEventSupplies
                    SET TypeOfAdditionalEventSupply = '$newSupplyFinal',
                        QuantityOfNeededAdditionalEventSupply = '$newQtyFinal'
                    WHERE CateringID = '$cateringID'";
            mysqli_query($con, $update);
        }
        else {
            $insert = "INSERT INTO AdditionalEventSupplies 
                   (CateringID, ClientID, TypeOfAdditionalEventSupply, QuantityOfNeededAdditionalEventSupply)
                   VALUES ('$cateringID', '$clientID', '$supply', '$qty')";
            mysqli_query($con, $insert);
        }

        echo "<script>alert('Additional services added for Catering ID: $cateringID');</script>";
        echo "
            <form id='retryForm' action='RequestServices.php' method='GET'></form>
            <script>
                document.getElementById('retryForm').submit();
            </script>
        ";
        exit;
    }


    // Check if CateringID exists
    $check = "SELECT * FROM ClientCateringInformation WHERE CateringID = '$cateringID'";
    $result = mysqli_query($con, $check);

    if (mysqli_num_rows($result) == 0) {
        echo "<script>alert('Catering ID does not exist. Please re-enter valid data.');</script>";
        echo "
            <form id='retryForm' action='RequestServices.php' method='GET'></form>
            <script>
                document.getElementById('retryForm').submit();
            </script>
        ";
        exit;
    }

    // Ask user to confirm (store values as hidden so user doesn't have to re-enter)
    echo "
        <form id='confirmForm' action='RequestServices.php' method='POST'>
            <input type='hidden' name='NewSupply' value='$supply'>
            <input type='hidden' name='NewQuantity' value='$qty'>
            <input type='hidden' name='confirmed' value='yes'>
        </form>

        <form id='retryForm' action='RequestServices.php' method='POST'></form>

        <script>
            if (confirm('Do you want to request these additional services?')) {
                document.getElementById('confirmForm').submit();
            } else {
                alert('Request cancelled.');
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
    <link rel='stylesheet' href='HomePage.css'>
    <meta charset='UTF-8'>
    <title>Request Additional Services</title>
</head>
<body>

<div id="template">

<h1>Request Additional Catering Services</h1>

<form action="RequestServices.php" method="POST" onsubmit="return validateRequest()">

    <div class="formField">
        <label>Additional Service:</label><br>
        <input type="text" name="NewSupply" id="NewSupply" placeholder="Example: Tables, Chairs, Utensils" style="width:350px;">
        <label> REQUIRED</label>
    </div><br>

    <div class="formField">
        <label>Quantity:</label><br>
        <input type="text" name="NewQuantity" id="NewQuantity" placeholder="Example: Tables (20), Chairs (200), Utensils (200)" style="width:350px;">
        <label> REQUIRED</label>
    </div><br>

    <input type="submit" class="bttn" value="Submit">

</form>

</div>

<script>
var validName = /^[A-Za-z ]+(?:, [A-Za-z ]+)*$/;
var validQty = /^[A-Za-z ]+\(\d+\)(?:, [A-Za-z ]+\(\d+\))*$/;

function validateRequest() {
    let supply = document.getElementById("NewSupply");
    let qty = document.getElementById("NewQuantity");

    if (supply.value == "") {
        alert("Please enter the additional services.");
        supply.focus();
        return false;
    }
    else if (!validName.test(supply.value)) {
        alert("Service names must follow the format: Tables, Chairs, Utensils");
        supply.value = "";
        supply.focus();
        return false;
    }

    if (qty.value == "") {
        alert("Please enter a quantity.");
        qty.focus();
        return false;
    }
    else if (!validQty.test(qty.value)) {
        alert("Quantity must follow the format: Tables (20), Chairs (200), Utensils (200)");
        qty.value = "";
        qty.focus();
        return false;
    }

    return true;
}
</script>

</body>
</html>
