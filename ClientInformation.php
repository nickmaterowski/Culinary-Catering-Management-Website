<!-- Allows Caterer to Add Information to New Client -->
<?php
if (!isset($_SESSION)) {
    session_start();
}

$clientID = $_SESSION['NewClientID'];

// DB connection
include('databaseLogin.php');

if ($_SERVER["REQUEST_METHOD"] == "POST"
    && isset($_POST['StreetNumber'])
    && isset($_POST['StreetName'])
    && isset($_POST['City'])
    && isset($_POST['State'])
    && isset($_POST['Zip'])
    && isset($_POST['Phone'])) 
{
    $num = $_POST['StreetNumber'];
    $street = $_POST['StreetName'];
    $city = $_POST['City'];
    $state = $_POST['State'];
    $zip = $_POST['Zip'];
    $phone = $_POST['Phone'];

    $insert = "INSERT INTO ClientPersonalInformation
               (ClientID, ClientStreetNumber, ClientStreetName, ClientCity, ClientState, ClientZipCode, ClientPhoneNumber)
               VALUES
               ('$clientID', '$num', '$street', '$city', '$state', '$zip', '$phone')";

    mysqli_query($con, $insert);

    echo "<script>alert('Client information record created successfully.');</script>";
    include('ClientInformation.php');
    exit;
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html>
<head>
    <link rel='stylesheet' href='HomePage.css'>
    <meta charset='UTF-8'>
    <title>Client Personal Information</title>
</head>
<body>
    <div id="template">

    <h1>Enter Client Personal Information</h1>

    <form action="ClientInformation.php" method="POST" onsubmit="return validateInfo();">

        <div class="formField">
            <label>Client Street Number:</label><br>
            <input type="text" name="StreetNumber" id="StreetNumber" placeholder="581" style="width: 350px;">
            <label>REQUIRED</label>
        </div><br>

        <div class="formField">
            <label>Client Street Name:</label><br>
            <input type="text" name="StreetName" id="StreetName" placeholder="Main Street" style="width: 350px;">
            <label>REQUIRED</label>
        </div><br>

        <div class="formField">
            <label>Client City:</label><br>
            <input type="text" name="City" id="City" placeholder="Woodbridge" style="width: 350px;">
            <label>REQUIRED</label>
        </div><br>

        <div class="formField">
            <label>Client State (2 letters):</label><br>
            <input type="text" name="State" id="State" placeholder="NJ" style="width: 350px;">
            <label>REQUIRED</label>
        </div><br>

        <div class="formField">
            <label>Client Zip Code:</label><br>
            <input type="text" name="Zip" id="Zip" placeholder="07095" style="width: 350px;">
            <label>REQUIRED</label>
        </div><br>

        <div class="formField">
            <label>Client Phone Number:</label><br>
            <input type="text" name="Phone" id="Phone" placeholder="123-456-7890 ext 231" style="width: 350px;">
            <label>REQUIRED</label>
        </div><br>

        <input type="submit" class="bttn" value="Submit">

    </form>

    </div>

    <script>
    var validStreetNum = /^\d+$/;
    var validStreetName = /^[A-Za-z][A-Za-z .'-]*$/;
    var validCity = /^[A-Za-z ]+$/;
    var validState = /^[A-Za-z]{2}$/;
    var validZip = /^\d{5}$/;
    var validPhone = /^\d{3}[\s-]\d{3}[\s-]\d{4}\sext\s\d{3}$/;

    function validateInfo() {
        let num = document.getElementById("StreetNumber");
        let street = document.getElementById("StreetName");
        let city = document.getElementById("City");
        let state = document.getElementById("State");
        let zip = document.getElementById("Zip");
        let phone = document.getElementById("Phone");

        if (num.value == "") {
            alert("Street Number Required");
            num.focus();
            return false;
        }
        else if (!validStreetNum.test(num.value)) {
            alert("Street number must be digits only.");
            num.value = "";
            num.focus();
            return false;
        }
        if (street.value == "") {
            alert("Street Name Required");
            street.focus();
            return false;
        }
        else if (!validStreetName.test(street.value)) {
            alert("Street Name must contain only letters and valid characters.");
            street.value = "";
            street.focus();
            return false;
        }
        if (city.value == "") {
            alert("City Name Required");
            city.focus();
            return false;
        }
        else if (!validCity.test(city.value)) {
            alert("City must contain only letters.");
            city.value = "";
            city.focus();
            return false;
        }
        if (state.value == "") {
            alert("State Name Required");
            state.focus();
            return false;
        }
        else if (!validState.test(state.value)) {
            alert("State must be 2 letters.");
            state.value = "";
            state.focus();
            return false;
        }
        if (zip.value == "") {
            alert("Zip Code Required");
            zip.focus();
            return false;
        }
        else if (!validZip.test(zip.value)) {
            alert("Zip code must be 5 digits.");
            zip.value = "";
            zip.focus();
            return false;
        }
        if (phone.value == "") {
            alert("Phone Number Required");
            phone.focus();
            return false;
        }
        else if (!validPhone.test(phone.value)) {
            alert("Phone number must follow format: 123-456-7890 ext 987 or 123 456 7890 ext 369.");
            phone.value = "";
            phone.focus();
            return false;
        }

        return true;
    }
    </script>
</body>
</html>
