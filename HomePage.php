<?php
session_start();
session_unset();
session_destroy();

unset($_SESSION['VerifiedClientID']);
unset($_SESSION['VerifiedClientFirst']);
unset($_SESSION['VerifiedClientLast']);
?>

<!DOCTYPE html>
<html lang ="en">
    <head>
        <link rel="stylesheet" type="text/css" href="HomePage.css"> 
        <meta charset="UTF-8"> 
    </head>
    <body>
        <div id="template">
            <title>Culinary Connoisseurs</title>
            <h1>Culinary Connoisseurs</h1>
            <form action="LoginHandler.php" method="POST" id="submissionForm">
                <div class="formField">
                    <label>Caterer's First Name:</label>
                    <br>
                    <input type="text"  name="firstName"  id="firstName"  placeholder="Example: John"value="" style="width: 350px;"/>
                    <label> REQUIRED</label>
                </div>
                <div class="formField">
                    <label>Caterer's Last Name:</label>
                    <br>
                    <input type="text"  name="lastName"  id="lastName"  placeholder="Example: Doe" style="width: 350px;"/>
                    <label> REQUIRED</label>
                </div>
                <div class="formField">
                    <label>Caterer's Password:</label>
                    <br>
                    <input type="password"  name="password"  id="password"  placeholder="Example: $Cg@1" style="width: 350px;"/>
                    <button type="button" id="toggleBtn" onclick="togglePassword()"><img src="passwordIcon.png" alt="Show password" width="16px;"></button>        
                    <label> REQUIRED</label>
                </div>
                <div class="formField">
                    <label>Caterer's ID #:</label>
                    <br>                
                    <input type="text"  name="catererID"  id="catererID"  placeholder="Example: 2468" style="width: 350px;"/>
                    <label> REQUIRED</label>
                </div>
                <div class="formField">
                    <label>Caterer's Phone #</label>
                    <br>
                    <input type="text"  name="phoneNumber"  id="phoneNumber"  placeholder="Example: 555-555-5555 ext 555" style="width: 350px;"/>
                    <label> REQUIRED</label>
                </div>
                <div class="formField">
                    <label>Representative's Email:</label>
                    <br>
                    <input type="text"  name="email"  id="email"  placeholder="Example: john@CC.com" style="width: 350px;"/>
                    <label id="required" style="visibility: hidden;"> REQUIRED</label>
                </div>
                <div class="formField">
                    <br>
                    <label>Check the box to request an Email Confirmation:</label> <input type ="checkbox" name="confirmation" id="confirmation" onclick="toggleRequiredLabel()" value="confirmation"/></label>
                </div>
                <br>
                <div class="formField">
                    <label>Select a Transaction:</label>
                        <select id="transaction" name="transaction">
                            <option value="SearchCatererAccount" selected>Search a Caterer's Account</option>
                            <option value="BookClientEvent">Book a Client's Catering Event</option>
                            <option value="CancelClientEvent">Cancel a Client's Catering Event</option>
                            <option value="RequestAdditionalServices">Request Additional Catering Services</option>
                            <option value="UpdateAdditionalServices">Update Additional Catering Services</option>
                            <option value="CreateNewClient">Create a New Client</option>
                        </select>
                        <div style="text-align: right;">
                        <button type="button" onclick="validate()" class="bttn" style="margin-left: 75px;">Submit</button>
                        <input type="reset" class="bttn"/>
                        </div>
                    </div>
                    <!-- Send the data to a PHP form to determine the next page -->
                    <input type="hidden" name="nextPage" id="nextPage">
                </form>
        </div>
        <script>
            function toggleRequiredLabel() {
                let checkbox = document.getElementById("confirmation");
                let label = document.getElementById("required");
                
                // Toggle required label between visible and hidden based on checkbox
                if (checkbox.checked) {
                    label.style.visibility = "visible";
                }
                else {
                    label.style.visibility = "hidden";
                }
            }

            var validFirstName = /^[A-Za-z]+$/;
            var validLastName = /^[A-Za-z]+$/;
            var validID = /^\d{4}$/;
            var validPhone = /^\d{3}[\s-]\d{3}[\s-]\d{4}\sext\s\d{3}$/;
            var validEmail = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{1,3}$/;

            function validatePassword(password) {
                // Check length
                if (password.length > 5 || password.length === 0) {
                    return false;
                }
                
                // Ensure that it starts with special character
                if (!/^[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                    return false;
                }
                
                // Check has at least one uppercase
                if (!/[A-Z]/.test(password)) {
                    return false;
                }
                
                // Check has at least one digit
                if (!/\d/.test(password)) {
                    return false;
                }
                
                return true;
            }
            
            // Validate the user input
            function validate() {
                let firstName = document.getElementById("firstName");
                let lastName = document.getElementById("lastName");
                let password = document.getElementById("password");
                let catererID = document.getElementById("catererID");
                let phoneNumber = document.getElementById("phoneNumber");
                let email = document.getElementById("email");
                let confirmation = document.getElementById("confirmation").checked;
                
                if (firstName.value == "") {
                    alert("Please make sure you enter a first name.");
                    firstName.focus();
                    return;
                }
                else if (!validFirstName.test(firstName.value)) {
                    alert("Your first name can only contain letters.");
                    firstName.value="";
                    firstName.focus();
                    return;
                }
                else if (lastName.value == "") {
                    alert("Please make sure you enter a last name.");
                    lastName.focus();
                    return;
                }
                else if (!validLastName.test(lastName.value)) {
                    alert("Your last name can only contain letters.");
                    lastName.value="";
                    lastName.focus();
                    return;
                }
                else if (password.value == "") {
                    alert("Please make sure you enter a password.");
                    password.focus();
                    return;
                }
                else if (!validatePassword(password.value)) {
                    alert("Please make sure you enter a valid password. A password must contain a max of 5 characters and have at least 1 uppercase letter, one special character, one numeric character and should start with a special character.");
                    password.value="";
                    password.focus();
                    return;
                }
                else if (catererID.value == "") {
                    alert("Please make sure you enter a Caterer ID.");
                    catererID.focus();
                    return;
                }
                else if (!validID.test(catererID.value)) {
                    alert("A valid ID should contain exactly 4 digits.");
                    catererID.value="";
                    catererID.focus();
                    return;
                }
                else if (phoneNumber.value == "") {
                    alert("Please make sure you enter a phone number.");
                    phoneNumber.focus();
                    return;
                }
                else if (!validPhone.test(phoneNumber.value)) {
                    alert("The phone number should consist of 10 digits which can be delineated either by spaces or dashes and contain the caterer’s extension number.");
                    phoneNumber.value="";
                    phoneNumber.focus();
                    return;
                }

                if (confirmation){
                    if (email.value == "") {
                        alert("Please make sure you enter an email.");
                        email.focus();
                        return;
                    }
                     else if (!validEmail.test(email.value)) {
                        alert("The email address must contain an @ followed by a period and an email domain that consists of 1 to 3 characters.");
                        email.value="";
                        email.focus();
                        return;
                    }
                }
                // Now submit to LoginHandler.php
                document.getElementById("submissionForm").submit();
            }

            function togglePassword() {
                let passwordField = document.getElementById("password");
                let toggleBtn = document.getElementById("toggleBtn");
                
                if (passwordField.type === "password") {
                    passwordField.type = "text";
                } 
                else {
                    passwordField.type = "password";
                }
            }
        </script>
    </body>
</html>