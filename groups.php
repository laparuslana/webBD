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
    $result["groups"] = "read";
    $stmt = $pdo->query("SELECT groups.id, groups.name, groups.course, departaments.name as 'departament' 
        FROM groups
       INNER JOIN departaments on groups.departament_id = departaments.id
       ");
    $tmp = $pdo->query('SELECT COUNT(*) FROM groups');
    $data_exists = ($tmp->fetchColumn() > 0) ? true : false;
    if (!$data_exists) {
        $result['error'] = true;
        $result['message'] = "No groups Found";
    }
    $std = array();
    while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($std, $rows);
    }
    $result["groups"] = $std;
}

if ($action == "create") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $course = $_POST["course"];
    $departament = $_POST["departament"];
    $stmt_verify = $pdo->query("SELECT * FROM groups WHERE name = '$name'");
    $find_stmt_verify_num_of_rows = $stmt_verify->rowCount();
    if ($find_stmt_verify_num_of_rows < 1) {
        $stmt = $pdo->query("INSERT INTO groups (departament_id, name, course) 
        VALUES ((SELECT d.id FROM departaments d WHERE d.short_name ='$departament'), '$name','$course')");
        if ($stmt) {
            $result["message"] = "Successfully Added This groups Record => $name";
        } else {
            $result["error"] = true;
            $result["message"] = "Failed to Add This groups Record => $name";
        }
    } else {
        $result["error"] = true;
        $result["message"] = "This Name Does Exist In The DB, put another!";
    }
}
if ($action == "delete") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $course = $_POST["course"];
    $stmt_verify = $pdo->query("SELECT * FROM groups WHERE id = '$id'");
    $find_stmt_verify_num_of_rows = $stmt_verify->rowCount();
    if ($find_stmt_verify_num_of_rows >= 1) {
        $stmt = $pdo->query("DELETE FROM groups WHERE id = '$id'");
        if ($stmt) {
            $result["message"] = "Successfully Deleted This groups Record => $name";
        } else {
            $result["error"] = true;
            $result["message"] = "Failed to Delete This groups Record => $name";
        }
    } else {
        $result["error"] = true;
        $result["message"] = "This Record Does Not Exist In The DB!";
    }
}
if ($action == "update") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $course = $_POST["course"];
    $departament = $_POST["departament"];
    $stmt_verify = $pdo->query("SELECT * FROM groups WHERE name = '$name' and id != '$id'");
    $find_stmt_verify_num_of_rows = $stmt_verify->rowCount();
    if (!$find_stmt_verify_num_of_rows >= 1) {
        $stmt = $pdo->query("UPDATE groups 
                                        SET
                                            departament_id = (SELECT d.id FROM departaments d WHERE d.short_name ='$departament'),
                                            name = '$name',
                                            course = '$course'
                                        WHERE
                                            id = '$id'
                                        ");
        if ($stmt) {
            $result["message"] = "Successfully Updated This groups Record => $name ";
        } else {
            $result["error"] = true;
            $result["message"] = "Failed to Update This groups Record => $name ";
        }
    } else {
        $result["error"] = true;
        $result["message"] = "This Name $name Does Exist In The DB, put another name!";
    }
}

if ($action == "deleteAll") {
    $stmt = $pdo->query("DELETE FROM groups");
    if ($stmt) {
        $result["message"] = "Successfully Deleted All groups";
    } else {
        $result["error"] = true;
        $result["message"] = "Failed to Delete All groups";
    }
}

echo json_encode($result);
?>
