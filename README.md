# GridBuddy
**Phone optimised grid sheets for Marshalling, PitLane, and Grid teams.**

Designed to offer a light-weight simple alterative to paper based grid sheets for circuit races

Allows organizers to define multiple races within an event, create a list of grid officials, populate original race grids following qualifying with immediate distribution.

Marshalling teams can mark cars present or scratched, without having to communicate via radio to Grid officials

Grid officials can also mark vehicles as starting from lane (so not expected to appear on Grid)

Supports both Right and Left Pole position layouts (event by event).

Currently *very* rough proof of concept, with some of the admin functions still being very clunky.

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