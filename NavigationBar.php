<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        .navbar {
            background-color: #2c2c2cff;
            overflow: auto;
            align-items: center;
            text-align: center;
            padding: 0;
            margin-left: -8px;
            margin-right: -8px;
            margin-top: -8px;
            margin-bottom: 20px;
        }
        
        .navbar a {
            color: white;
            text-align: center;
            padding: 6px 20px;
            text-decoration: none;
            font-size: 14px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="HomePage.php">Home</a>
        <a href="SearchCatererAccount.php">Search Account</a>
        <a href="VerifyClient.php">Book Event</a>
        <a href="CancelClientEvent.php">Cancel Event</a>
        <a href="RequestServices.php">Request Services</a>
        <a href="UpdateServices.php">Update Services</a>
        <a href="CreateClientAccount.php">Create Client</a>
    </div>
</body>
</html>