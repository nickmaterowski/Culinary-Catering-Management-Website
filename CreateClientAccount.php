<!-- Allows Caterer to Create New Client -->
<?php
if (!isset($_SESSION)) {
    session_start();
}
include("NavigationBar.php");

// DB connection
include('databaseLogin.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ClientFirstName']) && isset($_POST['ClientLastName']) && isset($_POST['ClientID'])) {
    $first = $_POST['ClientFirstName'];
    $last = $_POST['ClientLastName'];
    $clientID = $_POST['ClientID'];
    $catererID = $_SESSION['CatererID'];

    // Check duplicates
    $check = "SELECT * FROM Client WHERE ClientID = '$clientID'";
    $result = mysqli_query($con, $check);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Client already has an account.');</script>";
    } 
    else {
        $insert = "INSERT INTO Client (ClientID, ClientFirstName, ClientLastName, CatererID)
                   VALUES ('$clientID', '$first', '$last', '$catererID')";
        mysqli_query($con, $insert);

        $_SESSION['NewClientID'] = $clientID;

        echo "<script>alert('Client account created. You will now be redirected to a form to enter the personal infromation for the client.');</script>";

        include('ClientInformation.php');
        exit;
    }
}


mysqli_close($con);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel='stylesheet' href='HomePage.css'>
    <meta charset='UTF-8'>
    <title>Create New Client Account</title>
</head>
<body>
<div id="template">

<h1>Create New Client Account</h1>

<form action= "CreateClientAccount.php" method="POST" onsubmit="return validateClient();">

    <div class="formField">
        <label>Client's First Name:</label><br>
        <input type="text" name="ClientFirstName" id="ClientFirstName" placeholder="Example: John" style="width: 350px;">
        <label> REQUIRED</label>
    </div><br>

    <div class="formField">
        <label>Client's Last Name:</label><br>
        <input type="text" name="ClientLastName" id="ClientLastName" placeholder="Example: Doe" style="width: 350px;">
        <label> REQUIRED</label>
    </div><br>

    <div class="formField">
        <label>Client's ID:</label><br>
        <input type="text" name="ClientID" id="ClientID" placeholder="Example: 2468" style="width: 350px;">
        <label> REQUIRED</label>
    </div><br>

    <input type="submit" class="bttn" value="Submit">

</form>

</div>

<script>

var validName = /^[A-Za-z]+$/;
var validID = /^\d{4}$/;

function validateClient() {
    let first = document.getElementById("ClientFirstName");
    let last = document.getElementById("ClientLastName");
    let id = document.getElementById("ClientID");

    if (first.value == "") {
        alert("Please enter a first name.");
        first.focus();
        return false;
    }
    else if (!validName.test(first.value)) {
        alert("First name must contain only letters.");
        first.value = "";
        first.focus();
        return false;
    }

    if (last.value == "") {
        alert("Please enter a last name.");
        last.focus();
        return false;
    }
    else if (!validName.test(last.value)) {
        alert("Last name must contain only letters.");
        last.value = "";
        last.focus();
        return false;
    }

    if (id.value == "") {
        alert("Please enter a 4-digit Client ID.");
        id.focus();
        return false;
    }
    else if (!validID.test(id.value)) {
        alert("Client ID must contain exactly 4 digits.");
        id.value = "";
        id.focus();
        return false;
    }

    return true;
}
</script>

</body>
</html>
