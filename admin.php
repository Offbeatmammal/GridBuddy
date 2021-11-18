<?php
if (!isset($_COOKIE["event"])) {
    header('Location: /login.php');
    exit;
}

include "db_connect.php";
$event = $_COOKIE["event"];
$msg="";
//if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
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
<title>GridBuddy - Event Admin</title>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/68f3085cec.js" crossorigin="anonymous"></script>
</head>
<body>

<p>Welcome to <a href="index.html">GridBuddy</a> Event Admin.</p>
<p><?php echo "$e_n at $e_c from $e_sd."; ?> 
<p>Use this screen to edit available officials or update races</p>
<table id="officials">
    <thead>
    <tr><td><td>Initials</tr>
    </thead>
    <tbody>
<?php
echo "<tr><td><td><input type='text' size=10 maxlength=10 id='o0' value=''>";
echo "<td><button onclick='save_o(0)'><i class='fas fa-save'></i></button>";
echo "<td></td></tr>";
$sql = mysqli_prepare($conn, "SELECT race.id, officials.id, initials, count(distinct race.id) as races 
                                FROM `officials`
                                left join `rows` on official_id = officials.id
                                left join race on race.id = `rows`.race_id and race.event_id = ?
                                group by officials.id");
mysqli_stmt_bind_param($sql, "s", $event);
if (mysqli_stmt_execute($sql)) {
    $result = mysqli_stmt_get_result($sql);
    while ($row = mysqli_fetch_assoc($result) ) {
        echo "<tr><td>".$row['id']."<td><input type='text' size=10 maxlength=10 id='o".$row['id']."' value='".$row['initials']."'>";
        echo "<td><button onclick='save_o(".$row['id'].")'><i class='fas fa-save'></i></button>";
        if ($row['races']==0) {
            echo "<td><button onclick='del_o(".$row['id'].")'><i class='fa fa-trash'></i></button>";
        } else {
            echo "<td>(".$row['races']. " races)";
        }
        echo "</tr>";
    }
}
?>
</tbody>
</table>
<hr>
<table id='races'>
    <thead>
    <tr><td><td>Ref<td>Name<td>Start<td>complete</tr>
    </thead>
    <tbody>
<?php
echo "<tr><td><td><input type='text' size=5 maxlength=5 id='er0' value=''>";
echo "<td><input type='text' size=20 maxlength=100 id='en0' value=''>";
echo "<td><input type='time' id='est0' value=''>";
echo "<td><input type='checkbox' id='ec0'>";
echo "<td><button onclick='save_r(0)'><i class='fas fa-save'></i></button>";
echo "<td></td></tr>";
$sql = mysqli_prepare($conn, "SELECT id, ref, `name`, start_time, completed
                                FROM `race` WHERE event_id = ?
                                ORDER BY ref, start_time");
mysqli_stmt_bind_param($sql, "s", $event);
if (mysqli_stmt_execute($sql)) {
    $result = mysqli_stmt_get_result($sql);
    while ($row = mysqli_fetch_assoc($result) ) {
        echo "<tr><td>".$row['id']."<td><input type='text' size=5 maxlength=5 id='er".$row['id']."' value='".$row['ref']."'>";
        echo "<td><input type='text' size=20 maxlength=100 id='en".$row['id']."' value='".$row['name']."'>";
        echo "<td><input type='time' id='est".$row['id']."' value='".$row['start_time']."'>";
        echo "<td><input type='checkbox' id='ec".$row['id']."'".($row['completed']==1?" checked":"").">";
        echo "<td><button onclick='save_r(".$row['id'].")'><i class='fas fa-save'></i></button>";
        echo "<td><button onclick='edit_r(".$row['id'].")'><i class='fas fa-table'></i></button>";
        echo "<td><button onclick='del_r(".$row['id'].")'><i class='fa fa-trash'></i></button>";
        echo "</tr>";
    }
}
?>
    </tbody>
</table>

<script>
    function del_o(o_id) {
    $.post("grid_data.php",
        {
        "what": "del_official",
        "event_id": <?php echo $event; ?>,
        "official_id": o_id
        },
        function(data, status){
            if (isNaN(data)) {
                console.log(data);
            } else {
                // remove that row
                $('#officials tr').each(function(){
                    if($(this).find('td').eq(0).text() == data){
                        $(this).remove();
                    }
                });
            }
    });
}

function save_o(o_id) {
    if (o_id==0) {
        what = "ins_official"
    } else {
        what = "upd_official"
    }
    $.post("grid_data.php",
        {
        "what": what,
        "event_id": <?php echo $event; ?>,
        "official_id": o_id,
        "initials": $("#o"+o_id).val()
        },
        function(data, status){
            if (data != "") {
                if (isNaN(data)) {
                    console.log(data);
                } else {
                    // copy the row and use the supplied ID
                    out = "<tr><td>"+data+"<td><input type='text' size=10 maxlength=10 id='o"+data+"' value='"+$("#o0").val()+"'>";
                    out += "<td><button onclick='save_o("+data+")'><i class='fas fa-save'></i></button>";
                    out += "<td><button onclick='del_o("+data+")'><i class='fa fa-trash'></i></button>";
                    out += "</tr>";
                    $("#officials tbody").append(out);
                    $("#o0").val("");
                }
            }
    });
}

function edit_r(race_id) {
    window.location="race.php?race="+race_id
}

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
            if (data != "") {
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
            }
    });

}

</script>

</body>
</html>
<?php
mysqli_close($conn);
?>