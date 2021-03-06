# GridBuddy
**Phone optimised grid sheets for Marshalling, PitLane, and Grid teams.**

Designed to offer a light-weight simple alterative to paper based grid sheets for circuit races

Allows organizers to define multiple races within an event, create a list of grid officials, populate original race grids following qualifying with immediate distribution.

Grids are currently entered using a bulk form (which, similar to grid sheet layouts, orients to match grid sheets for the event, and set to a default number of rows per event), Ideally this would be an automated input from Natsoft etc.

Once a grid has been created, the Grid Chief can assign team members to specific rows (and green flag)

Marshalling teams can mark cars present or scratched simply by tapping on the car, without having to communicate via radio to Grid officials.

Grid officials can mark vehicles as scratched or starting from lane (so not expected to appear on Grid).

Grid officials can also tap the flags at the end of the grid and nominate cars to start from Rear of Grid - entering the number of an existing car will leave a 'shadow' in its original position and a duplicate shown at the end of the field. A tap on the original position will prompt to return the car to that place on the grid (rather than change status).
(Note: you cannot change the status of a car that's not in it's original grid position)

Supports both Right and Left Pole position layouts (event by event).

Currently *very* rough proof of concept, with some of the admin functions still being clunky, and in some cases just stubs!

Android users: for phones that support it (assuming Chrome) screen keep-alive is used when on the Gridsheet view (admin modes do not activate this) - please be aware as this may impact battery life if the user does not switch away from the browser or manually sleep their phone.

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
Many things still need doing, including a lot of UX tidy-up, workflow improvement, and performance tweaks.

- Import automatically from race admin software (eg [NatSoft](http://racing.natsoft.com.au/))
- Optimize the code around left/right hand Pole position layouts
- Double check that all functionality is duplicated for L/R layouts
- move to more ajax/DOM manipulation vs full page refresh (especially for things like RoG change)
- Need to disable save/add buttons if text fields are empty (mostly in Admin) 
- Indicate what revision is being viewed (and prompt to update if old)
- move to realtime messaging vs refresh for all team displays (though need to address the issue of, eg, a message being received and changing status as the receiving user taps, toggling the result!t)