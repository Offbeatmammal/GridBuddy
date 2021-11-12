<?php
if (!isset($_COOKIE["event"]) || !isset($_REQUEST["race"])) {
    header('Location: /login.php');
    exit;
}

include "db_connect.php";
$event = $_COOKIE["event"];
$race = $_REQUEST["race"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    for ($i=1; $i <= 60; $i++) {
        $id = $_POST["id_$i"];
        $car = trim($_POST["car_$i"]);
        if ($id == 0 && $car != "") { // insert
            $sql = mysqli_prepare($conn, "insert into grid (race_id, pos, car) values (?,?,?)");
            mysqli_stmt_bind_param($sql, "sss", $race, $i, $car);
        }
        if ($car == "") { // delete
            $sql = mysqli_prepare($conn, "delete from grid where id=? and race_id=?");
            mysqli_stmt_bind_param($sql, "ss", $id, $race);
        }
        if ($id != 0 && $car != "") { // update
            $sql = mysqli_prepare($conn, "update grid set car = ? where id=? and race_id=?");
            mysqli_stmt_bind_param($sql, "sss", $car, $id, $race);
        }
        mysqli_stmt_execute($sql);
    }
}
    
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
$sql = mysqli_prepare($conn, "select ref, name, start_time from race where event_id=? and id=?");
mysqli_stmt_bind_param($sql, "ss", $event, $race);
if (mysqli_stmt_execute($sql)) {
    $result = mysqli_stmt_get_result($sql);
    if (mysqli_num_rows($result)==1) {
        $row = mysqli_fetch_assoc($result);
        $r_r = $row['ref'];
        $r_n = $row['name'];
        $r_st = $row['start_time'];
    }
}


?>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GridBuddy - Event Admin</title>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/68f3085cec.js" crossorigin="anonymous"></script>
</head>
<body>

<p>Welcome to <a href="index.html">GridBuddy</a> <a href="admin.php">Race Admin</a>.</p>
<p><?php echo "$e_n at $e_c from $e_sd."; ?> <br> <?php echo "$r_r $r_n starting at $r_st."; ?>
<p>Use this screen to edit grid positions for vehicles</p>
<form method="post">
<table id='races'>
    <thead>
    <tr><td>Pos<td>Car#<td><td>Pos<td>Car#</tr>
    </thead>
    <tbody>
<?php
for ($i=1; $i <= 60; $i++) {
    $found = false;
    $sql = mysqli_prepare($conn, "SELECT id, car, driver from `grid` WHERE pos = ? and race_id = ?");
    mysqli_stmt_bind_param($sql, "ss", $i, $race);
    if (mysqli_stmt_execute($sql)) {
        $result = mysqli_stmt_get_result($sql);
        if (mysqli_num_rows($result)==1) {
            $row = mysqli_fetch_assoc($result);
            $found = true;
        }
    }
    if (($i % 2) != 0) {
        echo "<tr>";
    } else {
        echo "<td>&nbsp;&nbsp;&nbsp;";
    }
    if ($found) {
        echo "<td><input type='hidden' name='id_$i' value='".$row['id']."'>$i<td><input name='car_$i' type='text' size=5 maxlength=5 value='".$row['car']."'>";
    } else {
        echo "<td><input type='hidden' name='id_$i' value='0'>$i<td><input name='car_$i' type='text' size=5 maxlength=5 value=''>";
    }
}
?>
    </tbody>
</table>
<input type="submit" value="save">
</form>

<script>

function save_r(race_id) {

    if (race_id==0) {
        what = "ins_race"
    } else {
        what = "upd_race"
    }
    $.post("grid_data.php",
        {
        "what": what,
        "event_id": <?php echo $event; ?>,
        "race_id": race_id,
        "ref": $("#er"+race_id).val(),
        "name": $("#en"+race_id).val(),
        "start_time": $("#est"+race_id).val(),
        "completed": $("#ec"+race_id).prop('checked')?"1":"0"
        },
        function(data, status){
            if (isNaN(data)) {
                console.log(data);
            } else {
                // copy the row and use the supplied ID
                out = "<tr><td>"+data+"<td><input type='text' size=5 maxlength=5 id='er"+data+"' value='"+$("#er0").val()+"'>";
                out += "<td><input type='text' size=20 maxlength=100 id='en"+data+"' value='"+$("#en0").val()+"'>";
                out += "<td><input type='time' id='est"+data+"' value='"+$("#est0").val()+"'>";
                out += "<td><input type='checkbox' id='ec"+data+"'"+($("#ec0").prop("checked")?" checked":"")+">";
                out += "<td><button onclick='save_r("+data+")'><i class='fas fa-save'></i></button>";
                out += "<td><button onclick='edit_r("+data+")'><i class='fas fa-table'></i></button>";
                out += "<td><button onclick='del_r("+data+")'><i class='fa fa-trash'></i></button>";
                out += "</tr>";
                $("#races tbody").append(out);
                $("#er0").val("");
                $("#en0").val("");
                $("#est0").val("");
                $("#ec0").prop('checked',false);
            }
    });

}

</script>

</body>
</html>
<?php
mysqli_close($conn);
?>