<?php
if (!isset($_SESSION)) {
    session_start();
}

include("NavigationBar.php");

// DB connection
include('databaseLogin.php');

// If form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ClientFirstName']) && isset($_POST['ClientLastName']) && isset($_POST['ClientID'])) {

    $firstName = $_POST['ClientFirstName'];
    $lastName = $_POST['ClientLastName'];
    $clientID = $_POST['ClientID'];

    $sql = "SELECT * FROM Client 
              WHERE ClientFirstName = '$firstName'
              AND ClientLastName = '$lastName'
              AND ClientID = '$clientID'";

    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {

        $_SESSION['VerifiedClientID'] = $clientID;
        $_SESSION['VerifiedClientFirst'] = $firstName;
        $_SESSION['VerifiedClientLast'] = $lastName;

        include('BookClientEvent.php');
        exit;
    } 
    else {
        echo "
            <form id='retryForm' action='VerifyClient.php' method='POST'></form>
            <form id='createForm' action='CreateClientAccount.php' method='POST'></form>
            <script>
                let choice = confirm('Client does not exist. Click OK to re-enter data or Cancel to create a new account.');
                if (choice) {
                    document.getElementById('retryForm').submit();
                } else {
                    document.getElementById('createForm').submit();
                }
            </script>
        ";
        exit;
    }
}
if (isset($con)) {
    mysqli_close($con);
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel='stylesheet' href='HomePage.css'>
    <meta charset='UTF-8'>
    <title>Verify Client</title>
</head>
<body>
<div id="template">

<h1>Verify Client Form</h1>

<form action="VerifyClient.php" method="POST" onsubmit="return validateVerifyForm()">

    <div class="formField">
        <label>Client's First Name:</label><br>
        <input type="text" name="ClientFirstName" id="ClientFirstName" placeholder="Example: Roy" style="width: 350px;"> <label> REQUIRED</label>
    </div><br>

    <div class="formField">
        <label>Client's Last Name:</label><br>
        <input type="text" name="ClientLastName" id="ClientLastName" placeholder="Example: Woods" style="width: 350px;"> <label> REQUIRED</label>
    </div><br>

    <div class="formField">
        <label>Client's ID Number:</label><br>
        <input type="text" name="ClientID"  id="ClientID" placeholder="Example: 8231" style="width: 350px;"> <label> REQUIRED</label>
    </div><br>

    <input type="submit" class="bttn" value="Submit">

</form>

<script>

var validName = /^[A-Za-z]+$/;
var validID = /^\d{4}$/;

function validateVerifyForm() {
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
        alert("Please enter a 4 digit Client ID.");
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

</div>
</body>
</html>
