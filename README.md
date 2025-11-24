# Culinary-Catering-Management-Website

This project is a full web application built for IT202 that allows caterers to manage clients, book events, update services, and retrieve account information through a MySQL backed PHP system. It follows a structured, multi page design and implements both front end validation and back end database verification.

Features

1. Caterer Login and Verification
Users log in with their registered Caterer information. The form validates inputs on the front end and verifies credentials against the MySQL database.

2. Search Caterer Account
Displays a complete table of all clients associated with the logged in caterer. Information includes catering details, client personal information, and additional service records. This feature relies on multiple SQL JOIN statements to assemble a full profile.

3. Book a Client’s Catering Event
Allows a caterer to verify an existing client, confirm their information, and book a new catering event with a randomly generated Catering ID. Full validation and confirmation alerts are used before inserting any data.

4. Cancel a Client’s Catering Event
Checks if a catering event exists, confirms cancellation through user alerts, and updates the database accordingly.

5. Request Additional Catering Services
Lets a caterer add extra supplies or services to an existing booking. Verification steps ensure the client and booking exist before updating the database.

6. Update Additional Catering Services
Allows updating or modifying any additional services already associated with a client’s event. Confirmation alerts prevent accidental changes.

7. Create a New Client Account
Adds new clients to the system with full validation. Once created, the user proceeds to input the client’s personal information, which is stored in a separate table.

8. Client Personal Information Management
Handles detailed personal records including address, city, state, zip code and phone number.

Technologies Used

PHP for server side logic

MySQL for data storage and querying

HTML and CSS for the user interface design

JavaScript for validation and confirmation alerts

Security Practices

Database credentials are not included in this repository.
A placeholder file (databaseLogin.sample.php) is provided instead.
The real databaseLogin.php is stored securely on the NJIT AFS server.

Project Structure

The application is divided into multiple PHP pages, each handling a specific task such as booking events, searching accounts, or updating services. A navigation bar is included on all pages except the login page to maintain consistent usability.
