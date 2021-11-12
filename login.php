<?php
include "db_connect.php";

$event = "";
$pin = "";
$msg="";
setcookie("event", "", 1, "/");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event = $_POST['event'];
    $pin = $_POST['pin'];
    $msg = "PIN Incorrect for event.";
    $sql = mysqli_prepare($conn, "select id from event where id=? and pin=?");
    mysqli_stmt_bind_param($sql, "ss", $event, $pin);
    if (mysqli_stmt_execute($sql)) {
        $result = mysqli_stmt_get_result($sql);
        if (mysqli_num_rows($result)==1) {
            $row = mysqli_fetch_assoc($result);
            // all good
            setcookie("event", $event, time() + (86400 * 5), "/");
            header('Location: /index.html');
        }
    }
}
?>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GridBuddy - Event Selector</title>
</head>
<body>

<p>Welcome to GridBuddy.</p>
<p>Please select an event, and enter the PIN to log in:</p>
<form method="post">
<select name="event" id="event">
    <?php
    $sql = mysqli_prepare($conn, "select id, circuit, name from event where start_date > now() order by start_date, circuit;");
    //mysqli_stmt_bind_param($sql, "i", $event_id);
    if (mysqli_stmt_execute($sql)) {
        $result = mysqli_stmt_get_result($sql);
        $officials = "";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='".$row['id']."'";
            if ($event == $row['id']) {
                echo " selected";
            }
            echo ">[".$row['circuit']."] ".$row['name']."</option>";
        }
    }
    ?>
</select>
<input type="number" min=0 max=9999 size="4" id="pin" name="pin" required>
<input type="submit" value="Login...">
<?php echo($msg); ?>
</form>

</body>
</html>
<?php
mysqli_close($conn);
?>