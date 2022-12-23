<?php
header('Access-Control-Allow-Origin: *');
$result = array();
$result["error"] = false;
$result['message'] = "";

//connect to my db
$host_name = "localhost";
$host_user = "root";
$host_pass = "";
$host_db = "vue-spa-master";

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
    $result["faculty"] = "read";
    $stmt = $pdo->query("SELECT * FROM faculty");
    $tmp = $pdo->query('SELECT COUNT(*) FROM faculty');
    $data_exists = ($tmp->fetchColumn() > 0) ? true : false;
    if (!$data_exists) {
        $result['error'] = true;
        $result['message'] = "No Faculties Found";
    }
    $std = array();
    while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($std, $rows);
    }
    $result["faculties"] = $std;
}
if ($action == "create") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $short_name = $_POST["short_name"];
    $stmt_verify = $pdo->query("SELECT * FROM faculty WHERE name = '$name'");
    $find_stmt_verify_num_of_rows = $stmt_verify->rowCount();
    if ($find_stmt_verify_num_of_rows < 1) {
        $stmt = $pdo->query("INSERT INTO faculty (name, short_name) VALUES ('$name','$short_name')");
        if ($stmt) {
            $result["message"] = "Successfully Added This Faculty Record => $name $short_name";
        } else {
            $result["error"] = true;
            $result["message"] = "Failed to Add This Faculty Record => $name $short_name";
        }
    } else {
        $result["error"] = true;
        $result["message"] = "This Name Does Exist In The DB, put another!";
    }
}

if ($action == "delete") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $short_name = $_POST["short_name"];
    $stmt_verify = $pdo->query("SELECT * FROM faculty WHERE id = '$id'");
    $find_stmt_verify_num_of_rows = $stmt_verify->rowCount();
    if ($find_stmt_verify_num_of_rows >= 1) {
        $stmt = $pdo->query("DELETE FROM faculty WHERE id = '$id'");
        if ($stmt) {
            $result["message"] = "Successfully Deleted This Faculty Record => $name $short_name";
        } else {
            $result["error"] = true;
            $result["message"] = "Failed to Delete This Faculty Record => $name $short_name";
        }
    } else {
        $result["error"] = true;
        $result["message"] = "This Record Does Not Exist In The DB!";
    }
}
if ($action == "update") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $short_name = $_POST["short_name"];
    $stmt_verify = $pdo->query("SELECT * FROM faculty WHERE name = '$name' and id != '$id'");
    $find_stmt_verify_num_of_rows = $stmt_verify->rowCount();
    if (!$find_stmt_verify_num_of_rows >= 1) {
        $stmt = $pdo->query("UPDATE faculty 
                                        SET
                                            name = '$name',
                                            short_name = '$short_name'
                                        WHERE
                                            id = '$id'
                                        ");
        if ($stmt) {
            $result["message"] = "Successfully Updated This Faculty Record => $name $short_name";
        } else {
            $result["error"] = true;
            $result["message"] = "Failed to Update This Faculty Record => $name $short_name";
        }
    } else {
        $result["error"] = true;
        $result["message"] = "This Name $name Does Exist In The DB, put another name!";
    }
}

if ($action == "deleteAll") {
    $stmt = $pdo->query("DELETE FROM faculty");
    if ($stmt) {
        $result["message"] = "Successfully Deleted All Faculties";
    } else {
        $result["error"] = true;
        $result["message"] = "Failed to Delete All Faculties";
    }
}

echo json_encode($result);
?>
