<!-- Allows Caterer to Cancel Client Event -->
<?php
if (!isset($_SESSION)) {
    session_start();
}
include("NavigationBar.php");

$catererID = $_SESSION['CatererID'];

// DB connection
include('databaseLogin.php');

// User submitted CateringID
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['CateringID']) && !isset($_POST['confirmDelete'])) {

    $cateringID = $_POST['CateringID'];

    // Validate that CateringID isn't blank
    if ($cateringID == "") {
        echo "<script>alert('Catering ID is required.');</script>";
    }
    else {
        // Look up the event
        $check = "SELECT * FROM ClientCateringInformation 
                  WHERE CateringID = '$cateringID'";

        $result = mysqli_query($con, $check);
        $row = mysqli_fetch_assoc($result);

        if (!$row) {
            echo "<script>alert('Catering ID does not exist for this Caterer. Please check and try again.');</script>";
        }
        else {
            $clientID = $row['ClientID'];

            // Ask for confirmation
            echo "
            <!-- Form if user clicks OK -->
            <form id='confirmForm' action='CancelClientEvent.php' method='POST'>
                <input type='hidden' name='CateringID' value='$cateringID'>
                <input type='hidden' name='confirmDelete' value='yes'>
                <input type='hidden' name='ClientID' value='$clientID'>
            </form>

            <script>
                let confirmCancel = confirm('Are you sure you want to cancel Catering ID $cateringID?');

                if (confirmCancel) {
                    document.getElementById('confirmForm').submit();
                } else {
                    alert('No changes have been made.');
                }
            </script>
            ";
        }
    }
}

// Delete the event after confirmation
if (isset($_POST['confirmDelete']) && $_POST['confirmDelete'] == "yes") {

    $cateringID = $_POST['CateringID'];
    $clientID = $_POST['ClientID'];

    $deleteSupplies = "DELETE FROM AdditionalEventSupplies 
               WHERE CateringID = '$cateringID'";
    mysqli_query($con, $deleteSupplies);

    $delete = "DELETE FROM ClientCateringInformation 
               WHERE CateringID = '$cateringID'";
    mysqli_query($con, $delete);

    echo "<script>
        alert('Catering event cancelled. Caterer ID: $catererID, Client ID: $clientID, Catering ID: $cateringID');
    </script>";

    echo "<script>
        <form id='refreshPage' action='CancelClientEvent.php' method='POST'></form>
        document.getElementById('refreshPage').submit();
    </script>";
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel='stylesheet' href='HomePage.css'>
        <meta charset='UTF-8'>
        <title>Cancel Catering Event</title>
    </head>
    <body>
    <div id='template'>

    <h1>Cancel A Client's Catering Event</h1>

    <form action="CancelClientEvent.php" method="POST" onsubmit="return validateCancel();">

        <div class="formField">
            <label>Catering ID:</label><br>
            <input type="text" name="CateringID" id="CateringID" placeholder="12345" style="width: 350px;">
            <label> REQUIRED</label>
        </div><br>

        <input type="submit" class="bttn" value="Submit">

    </form>

    </div>

    <script>
    var validID = /^\d+$/;

    function validateCancel() {
        let id = document.getElementById("CateringID");

        if (id.value == "") {
            alert("Please enter a Catering ID.");
            id.focus();
            return false;
        }
        else if (!validID.test(id.value)) {
            alert("Catering ID must contain digits only.");
            id.value = "";
            id.focus();
            return false;
        }

        return true;
    }
    </script>

    </body>
</html>