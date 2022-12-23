<?php
header('Access-Control-Allow-Origin: *');
$result = array();
$result["error"] = false;
$result['message'] = "";

//connect to my db
$host_name = "localhost";
$host_user = "root";
$host_pass = "";
$host_db = "webBD";

try {
    $pdo = new PDO("mysql:host=$host_name; dbname=$host_db", $host_user, $host_pass);
    $result["is_db_connected"] = true;
    $result["connection_msg"] = "Successfully connected";
} catch (PDOException $e) {
    $result["is_db_connected"] = false;
    $result["connection_msg"] = "бляяяяяяяяяяяяяяяяяяяяяяя " . $e->getMessage();
}

$action = "";
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

if ($action == "read") {
    $result["departaments"] = "read";
    $stmt = $pdo->query("SELECT departaments.id,departaments.name,departaments.short_name, faculties.name as 'faculty' 
FROM departaments INNER JOIN faculties on departaments.faculty_id = faculties.id");
    $tmp = $pdo->query('SELECT COUNT(*) FROM departaments');
    $data_exists = ($tmp->fetchColumn() > 0) ? true : false;
    if (!$data_exists) {
        $result['error'] = true;
        $result['message'] = "No Departaments Found";
    }
    $std = array();
    while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($std, $rows);
    }
    $result["departaments"] = $std;
}
if ($action == "create") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $short = $_POST["short_name"];
    $faculty = $_POST["faculty"];
    $stmt_verify = $pdo->query("SELECT * FROM departaments WHERE name = '$name'");
    $find_stmt_verify_num_of_rows = $stmt_verify->rowCount();
    if ($find_stmt_verify_num_of_rows < 1) {
        $stmt = $pdo->query("INSERT INTO departaments (faculty_id, name, short_name) 
        VALUES ((SELECT f.id FROM faculties f WHERE f.short_name ='$faculty'), '$name','$short')");
        if ($stmt) {
            $result["message"] = "Successfully Added This Departaments Record => $name $short";
        } else {
            $result["error"] = true;
            $result["message"] = "Failed to Add This Departaments Record => $name $short";
        }
    } else {
        $result["error"] = true;
        $result["message"] = "This Name Does Exist In The DB, put another!";
    }
}
if ($action == "delete") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $short = $_POST["short"];
    $stmt_verify = $pdo->query("SELECT * FROM departaments WHERE id = '$id'");
    $find_stmt_verify_num_of_rows = $stmt_verify->rowCount();
    if ($find_stmt_verify_num_of_rows >= 1) {
        $stmt = $pdo->query("DELETE FROM departaments WHERE id = '$id'");
        if ($stmt) {
            $result["message"] = "Successfully Deleted This Departaments Record => $name $short";
        } else {
            $result["error"] = true;
            $result["message"] = "Failed to Delete This Departaments Record => $name $short";
        }
    } else {
        $result["error"] = true;
        $result["message"] = "This Record Does Not Exist In The DB!";
    }
}
if ($action == "update") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $short = $_POST["short_name"];
    $faculty = $_POST["faculty"];
    $stmt_verify = $pdo->query("SELECT * FROM departaments WHERE name = '$name' and id != '$id'");
    $find_stmt_verify_num_of_rows = $stmt_verify->rowCount();
    if (!$find_stmt_verify_num_of_rows >= 1) {
        $stmt = $pdo->query("UPDATE departaments 
                                        SET
                                            faculty_id = (SELECT f.id FROM faculties f WHERE f.name ='$faculty'),
                                            name = '$name',
                                            short_name = '$short'
                                        WHERE
                                            id = '$id'
                                        ");
        if ($stmt) {
            $result["message"] = "Successfully Updated This Departaments Record => $name $short";
        } else {
            $result["error"] = true;
            $result["message"] = "Failed to Update This Departaments Record => $name $short";
        }
    } else {
        $result["error"] = true;
        $result["message"] = "This Name $name Does Exist In The DB, put another name!";
    }
}

if ($action == "deleteAll") {
    $stmt = $pdo->query("DELETE FROM departaments");
    if ($stmt) {
        $result["message"] = "Successfully Deleted All Departaments";
    } else {
        $result["error"] = true;
        $result["message"] = "Failed to Delete All Departaments";
    }
}

echo json_encode($result);
?>
