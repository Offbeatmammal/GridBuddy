<?php
if (!isset($_COOKIE["event"])) {
    header('Location: /login.php');
    exit;
}

include "db_connect.php";
$event = $_COOKIE["event"];
$race = "";
if (isset($_COOKIE["race_id"])) {
    $race = $_COOKIE["race_id"];
}
$msg="";

$sql = mysqli_prepare($conn, "select circuit, name, start_date from event where id=?");
mysqli_stmt_bind_param($sql, "s", $event);
if (mysqli_stmt_execute($sql)) {
    $result = mysqli_stmt_get_result($sql);
    if (mysqli_num_rows($result)==1) {
        $row = mysqli_fetch_assoc($result);
        $e_n = $row['name'];
        $e_c = $row['circuit'];
        $e_sd = $row['start_date'];
    }
}

?>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GridBuddy - Race Selection</title>
<script src="common.js"></script>
<style>
    table {
        border-collapse: collapse;
    }
    td {
        padding-left: 1vw;
        padding-right: 1vw;
        padding-bottom: 2.5vw;
    }
</style>
</head>
<body>

<p>GridBuddy</a> Race Selection.</p>
<p><?php echo "$e_n at $e_c from $e_sd."; ?> 
<p>Please select a race:</p>
<table>
<?php
$sql = mysqli_prepare($conn, "SELECT id, ref, `name`, start_time from race where event_id=? and completed=0 order by ref, start_time");
mysqli_stmt_bind_param($sql, "s", $event);
if (mysqli_stmt_execute($sql)) {
    $result = mysqli_stmt_get_result($sql);
    while ($row = mysqli_fetch_assoc($result) ) {
        echo "<tr onclick='race(".$row['id'].")'><td>".$row['ref']."</td><td>".$row['name']."</td><td>".$row['start_time']."</td></tr>";
   }
}
?>
</table>
<script>
function race(race_id) {
    setCookie("race_id",race_id,3)
    window.location="index.html"
}

</script>

</body>
</html>
<?php
mysqli_close($conn);
?>