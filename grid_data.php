<?php
include "db_connect.php";

$what = $_POST['what'];

switch ($what) {
    case "ins_race":
        //$race_id = $_POST['race_id'];
        $event_id = $_POST['event_id'];
        $r_r = $_POST['ref'];
        $r_n = $_POST['name'];
        $r_st = $_POST['start_time'];
        $r_c = $_POST['completed'];
        $sql = mysqli_prepare($conn, "insert into race (event_id, ref, `name`, start_time, completed) values (?,?,?,?,?)");
        mysqli_stmt_bind_param($sql, "sssss", $event_id, $r_r, $r_n, $r_st, $r_c);
        if (mysqli_stmt_execute($sql)) {
            echo mysqli_insert_id($conn);
        } else {
            echo "Error inserting record: " . mysqli_error($conn);
        }
        break;
    case "upd_race":
        $race_id = $_POST['race_id'];
        $event_id = $_POST['event_id'];
        $r_r = $_POST['ref'];
        $r_n = $_POST['name'];
        $r_st = $_POST['start_time'];
        $r_c = $_POST['completed'];
        $sql = mysqli_prepare($conn, "update race set ref=?, `name` = ?, start_time=?, completed=? where id=? and event_id=?");
        mysqli_stmt_bind_param($sql, "ssssss", $r_r, $r_n, $r_st, $r_c, $race_id, $event_id);
        if (mysqli_stmt_execute($sql)) {
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
        break;
    case "upd_grid":
        $race_id = $_POST['race_id'];
        $pos = $_POST['pos'];
        $grid_status = $_POST['grid_status'];
        $sql = mysqli_prepare($conn, "update grid set grid_status = ? where race_id=? and pos=?");
        mysqli_stmt_bind_param($sql, "iii", $grid_status,$race_id, $pos);
        if (mysqli_stmt_execute($sql)) {
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
        break;
    case "upd_row":
        $race_id = $_POST['race_id'];
        $row = $_POST['row'];
        $official_id = $_POST['official_id'];
        $green_flag = 0;
        $sql = mysqli_prepare($conn, "INSERT INTO `rows` (race_id, `row`, official_id, green_flag) 
                                        VALUES(?,?,?,?)
                                        ON DUPLICATE KEY UPDATE official_id=?, green_flag=?");
        mysqli_stmt_bind_param($sql, "iiiiii", $race_id, $row, $official_id, $green_flag, $official_id, $green_flag);
        if (mysqli_stmt_execute($sql)) {
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
        break;
    case "get_data":
        // get race details
        // get officials
        // get grid - add max pos to race details (save calc)
        // get rows

        $race_id = $_POST['race_id'];
        $sql = mysqli_prepare($conn, "select event_id, ref, race.name as name, date_format(start_time,'%H:%i') as start_time, pole_lr from race inner join event on event_id = event.id where race.id=?");
        mysqli_stmt_bind_param($sql, "i", $race_id);
        if (mysqli_stmt_execute($sql)) {
            $result = mysqli_stmt_get_result($sql);
            $row = mysqli_fetch_assoc($result);
            $race = '{"grp":"race", "data":[{"name":"' . $row['name'] .'",
                "ref":"' . $row['ref'] .'",
                "start_time":"' . $row['start_time'] .'",
                "pole_lr":"' . $row['pole_lr'] .'",
                "max_pos":"##maxpos##"}]}';  // will substitute later
            $event_id = $row['event_id'];
        } else {
            $race = "Error reading record: " . mysqli_error($conn);
        }

        $sql = mysqli_prepare($conn, "select id, initials from officials where event_id=?");
        mysqli_stmt_bind_param($sql, "i", $event_id);
        if (mysqli_stmt_execute($sql)) {
            $result = mysqli_stmt_get_result($sql);
            $officials = "";
            while ($row = mysqli_fetch_assoc($result)) {
                if ($officials == "") {
                    $officials = '{"grp":"officials", "data":[';
                } else {
                    $officials = $officials . ",";
                }
                $officials = $officials . '{"id":' . $row['id'] . ',"initials":"' . $row['initials'] . '"}';
            }
            $officials = $officials . "]}";
        } else {
            $officials = "Error reading record: " . mysqli_error($conn);
        }

        $sql = mysqli_prepare($conn, "select pos, car, grid_status from grid where race_id=?");
        mysqli_stmt_bind_param($sql, "i", $race_id);
        if (mysqli_stmt_execute($sql)) {
            $result = mysqli_stmt_get_result($sql);
            $grid = "";
            $mp = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                if ($grid == "") {
                    $grid = '{"grp":"grid", "data":[';
                } else {
                    $grid = $grid . ",";
                }
                $grid = $grid . '{"pos":' . $row['pos'] .',
                        "car":"' . $row['car'] .'",
                        "status":' . $row['grid_status'] . '}';
                if ($row['pos'] > $mp) {
                    $mp = $row['pos'];
                }
            }
            $grid = $grid . "]}";
            $race = str_replace("##maxpos##",$mp, $race);
        } else {
            $grid = "Error reading record: " . mysqli_error($conn);
        }

        $sql = mysqli_prepare($conn, "select row, official_id, green_flag from `rows` where race_id=?");
        mysqli_stmt_bind_param($sql, "i", $race_id);
        if (mysqli_stmt_execute($sql)) {
            $result = mysqli_stmt_get_result($sql);
            $rows = "";
            while ($row = mysqli_fetch_assoc($result)) {
                if ($rows == "") {
                    $rows = '{"grp":"rows", "data":[';
                } else {
                    $rows = $rows . ",";
                }
                $rows = $rows . '{"row":' . $row['row'] . ', "official_id":' . $row['official_id'] . ',"green_flag":"' . $row['green_flag'] . '"}';
            }
            if ($rows <> "") {
                $rows = $rows . "]}";
            }
        } else {
            $rows = "Error reading record: " . mysqli_error($conn);
        }

        if ($rows == "") {
            $rows = '{"grp":"rows", "data":[]}';
        }
        echo "[" . $race . "," . $officials . "," . $grid . "," . $rows . "]";

        break;
    case "upd_user":
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $status = $_POST['status'];
        $edit_user = $_POST['edit_user'];
        $edit_docs = $_POST['edit_docs'];

        $sql = mysqli_prepare($conn, "update users set name=?, email=?, active=?, edit_user=?, edit_docs=? where id=?");
        mysqli_stmt_bind_param($sql, "ssssss", $name, $email, $status, $edit_user, $edit_docs, $id);
        mysqli_stmt_execute($sql);

        echo '[{"id":'.$id.'}]';
        break;
}

mysqli_close($conn);
?>