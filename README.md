# GridBuddy
**Phone optimised grid sheets for Marshalling, PitLane, and Grid teams.**

Designed to offer a light-weight simple alterative to paper based grid sheets for circuit races

Allows organizers to define multiple races within an event, create a list of grid officials, populate original race grids following qualifying with immediate distribution.

Once a grid has been created, the Grid Chief can assign team members to specific rows (and green flag)

Marshalling teams can mark cars present or scratched, without having to communicate via radio to Grid officials

Grid officials can mark vehicles as starting from lane (so not expected to appear on Grid)

Supports both Right and Left Pole position layouts (event by event).

Currently *very* rough proof of concept, with some of the admin functions still being clunky, and in some cases just stubs!

You will need to add your own db_connect.php with credentials for the database:
```
<?php
$servername = "";
$username = "";
$password = "";
$dbname = "";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
```

`gridbuddy.sql` contains MySQL database layout with sample event data

To Do
---
Many things still need doing, including a lot fo UX tidy-up, workflow improvement, and performance tweaks.

- Import automatically from race admin software (eg [NatSoft](http://racing.natsoft.com.au/))
- Support Marshalling/Grid teams moving a car to a rear-of-grid space