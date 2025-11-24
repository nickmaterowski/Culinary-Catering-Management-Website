<!-- Handles Login Request & Redirects to Next Page -->
<?php
session_start();

// Makes DB connection
include('databaseLogin.php');

// Get the submitted form data
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$password = $_POST['password'];
$catererID = $_POST['catererID'];
$phoneNumber = $_POST['phoneNumber'];
$email = $_POST['email'] ?? "";
$confirmation = $_POST['confirmation'] ?? null; // If confirmation unchecked, default to null to prevent an undefined array
$nextPage = $_POST['transaction'];

// Verify the caterer using the database
if (isset($confirmation)) {
    // If email confirmation checkbox was checked
    $sql = "SELECT Caterer.CatererFirstName, Caterer.CatererLastName, Caterer.CatererID, Caterer.CatererPhoneNumber, Caterer.CatererEmailAddress, Client.CatererID
            FROM Caterer 
            INNER JOIN Client 
            ON Caterer.CatererID = Client.CatererID 
            WHERE CatererFirstName='$firstName'
            AND CatererLastName='$lastName'
            AND CatererPassword='$password'
            AND Caterer.CatererID='$catererID'
            AND CatererPhoneNumber='$phoneNumber'
            AND CatererEmailAddress='$email'";
} 
else {
    $sql = "SELECT Caterer.CatererFirstName, Caterer.CatererLastName, Caterer.CatererID, Caterer.CatererPhoneNumber, Caterer.CatererEmailAddress, Client.CatererID
            FROM Caterer 
            INNER JOIN Client 
            ON Caterer.CatererID = Client.CatererID 
            WHERE CatererFirstName='$firstName'
            AND CatererLastName='$lastName'
            AND CatererPassword='$password'
            AND Caterer.CatererID='$catererID'
            AND CatererPhoneNumber='$phoneNumber'";
}

$result = mysqli_query($con, $sql); // Store the result set for the SELECT query

// Ensure that caterer exists
if ($result && mysqli_num_rows($result) > 0){
    // Create a session based on caterer login
    $_SESSION['CatererID'] = $catererID;
    $_SESSION['CatererFirstName'] = $firstName;
    $_SESSION['CatererLastName'] = $lastName;

    if ($nextPage == "SearchCatererAccount") {
        include("SearchCatererAccount.php");
        exit;
    }
    elseif ($nextPage == "BookClientEvent") {
        include("BookClientEvent.php");
        exit;
    }
    elseif ($nextPage == "CancelClientEvent") {
        include("CancelClientEvent.php");
        exit;
    }
    elseif ($nextPage == "RequestAdditionalServices") {
        include("RequestServices.php");
        exit;
    }
    elseif ($nextPage == "UpdateAdditionalServices") {
        include("UpdateServices.php");
        exit;
    }
elseif ($nextPage == "CreateNewClient") {
    include("CreateClientAccount.php");
    exit;
}
}

// If verification failed, alert the user and return to the login page
echo "<script>
        alert('The information entered could not be verified. Please try again.');
        window.location.href = 'HomePage.php';
      </script>";

mysqli_close($con);
exit;
?>
