# Route Manager
A System for managing Bus Routes at Denver International Airport (DIA)

## Activities
### Route Manager is designed to track, manage and report activities for the parking bus fleet at DIA.
- Daily 24/7 milage and passenger counts logging
- Route, Bus and driver details
- Fluid and Fuel Tracking
- Accident Reports
- Passenger Comments and Complaints

###

## Reporting
### Route Manager has comprehensive reporting for all Activities.

### Setup
You will need to set up a database connection.

- There is a blank database table structure you can use in the "database" folder, called "routemanager_database_mysql.sql", that you can use to create the database in your own environment. 

Set the database credentials for your environment in "api/includes.php", in the PDO Connection String.

	// Using PDO for DB Connections
	$dbconn = new PDO('mysql:host=<hostname>;dbname=<dbname>', '<username>', '<password>', array(PDO::ATTR_PERSISTENT => true
	));
	$dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);		

You will need to create a user record in the database with your email and a password.

- Create the user record in "tblusers" with a unique "id" value... doesn't really matter what it is (to be safe, be sure NOT to use the string "user" anywhere in the id, and only use letters or numbers) The system will take care of the "id" values for future records.
 
- The password in the database should be a string made up of the email appended to the password and then md5 hashed.

	md5($password . $email); // This is what you store in the database
	
- Be sure to add your userid and the value "admin" to the table "tblroles".

That should let you log in as an admin. From there, you should be able to whatever you want.
	
## See it Live!
**https://routemanager.swishersolutions.com/**
- Please Note: You will need to contact the author for credentials in order to log in.

###
	
# More Details
### Check out the Info Sheet
**https://routemanager.swishersolutions.com/routemanager_infosheet.pdf**