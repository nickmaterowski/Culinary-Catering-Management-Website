<!-- Display Caterer Records -->
<?php
if (!isset($_SESSION)) {
    session_start();
}
include("NavigationBar.php");
include('databaseLogin.php');

$catererID = $_SESSION['CatererID'];

$sql = "SELECT Caterer.CatererFirstName, Caterer.CatererLastName, Caterer.CatererID, Caterer.CatererPhoneNumber, Caterer.CatererEmailAddress,
               Client.CatererID, Client.ClientID, Client.ClientLastName, Client.ClientFirstName,
               ClientCateringInformation.DateOfEvent, ClientCateringInformation.FoodOrder, ClientCateringInformation.ClientID, ClientCateringInformation.CateringID,
               ClientPersonalInformation.ClientStreetNumber, ClientPersonalInformation.ClientStreetName, ClientPersonalInformation.ClientCity, ClientPersonalInformation.ClientState, ClientPersonalInformation.ClientZipCode, ClientPersonalInformation.ClientPhoneNumber, ClientPersonalInformation.ClientID,
               AdditionalEventSupplies.TypeOfAdditionalEventSupply, AdditionalEventSupplies.QuantityOfNeededAdditionalEventSupply
        FROM Caterer
        INNER JOIN Client ON Caterer.CatererID = Client.CatererID
        LEFT JOIN ClientPersonalInformation ON Client.ClientID = ClientPersonalInformation.ClientID
        LEFT JOIN ClientCateringInformation ON Client.ClientID = ClientCateringInformation.ClientID
        LEFT JOIN AdditionalEventSupplies ON  Client.ClientID = AdditionalEventSupplies.ClientID
        WHERE Caterer.CatererID = '$catererID'";

$result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Caterer Account</title>
    <link rel="stylesheet" type="text/css" href="HomePage.css">
</head>
<body>
    <h1>Search A Caterer’s Account</h1>
    <table border="1" cellpadding="6" style="margin:auto; background:white;">
        <tr>
            <th>Caterer ID</th>
            <th>Caterer First Name</th>
            <th>Caterer Last Name</th>
            <th>Cater Phone</th>
            <th>Caterer Email</th>
            <th>Client First Name</th>
            <th>Client Last Name</th>
            <th>Client ID</th>
            <th>Client Street Number</th>
            <th>Client Street Name</th>
            <th>Client City</th>
            <th>Client State</th>
            <th>Client Zip Code</th>
            <th>Client Phone Number</th>
            <th>Event Date</th>
            <th>Food Ordered</th>
            <th>Additional Items</th>
            <th>Quantity of Items</th>
            <th>Catering ID</th>
        </tr>

        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$row['CatererID']}</td>";
                echo "<td>{$row['CatererFirstName']}</td>";
                echo "<td>{$row['CatererLastName']}</td>";
                echo "<td>{$row['CatererPhoneNumber']}</td>";
                echo "<td>{$row['CatererEmailAddress']}</td>";
                echo "<td>{$row['ClientFirstName']}</td>";
                echo "<td>{$row['ClientLastName']}</td>";
                echo "<td>{$row['ClientID']}</td>";
                echo "<td>{$row['ClientStreetNumber']}</td>";
                echo "<td>{$row['ClientStreetName']}</td>";
                echo "<td>{$row['ClientCity']}</td>";
                echo "<td>{$row['ClientState']}</td>";
                echo "<td>{$row['ClientZipCode']}</td>";
                echo "<td>{$row['ClientPhoneNumber']}</td>";
                echo "<td>{$row['DateOfEvent']}</td>";
                echo "<td>{$row['FoodOrder']}</td>";
                echo "<td>{$row['TypeOfAdditionalEventSupply']}</td>";
                echo "<td>{$row['QuantityOfNeededAdditionalEventSupply']}</td>";
                echo "<td>{$row['CateringID']}</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='15'>No records found for this Caterer.</td></tr>";
        }

        mysqli_close($con);
        ?>
    </table>
</body>
</html>
