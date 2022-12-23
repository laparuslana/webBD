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
    $result["disciplines"] = "read";
    $stmt = $pdo->query("SELECT * FROM disciplines");
    $tmp = $pdo->query('SELECT COUNT(*) FROM disciplines');
    $data_exists = ($tmp->fetchColumn() > 0) ? true : false;
    if (!$data_exists) {
        $result['error'] = true;
        $result['message'] = "No Disciplines Found";
    }
    $std = array();
    while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($std, $rows);
    }
    $result["disciplines"] = $std;
}
if ($action == "create") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $stmt_verify = $pdo->query("SELECT * FROM disciplines WHERE name = '$name'");
    $find_stmt_verify_num_of_rows = $stmt_verify->rowCount();
    if ($find_stmt_verify_num_of_rows < 1) {
        $stmt = $pdo->query("INSERT INTO disciplines (name) VALUES ('$name')");
        if ($stmt) {
            $result["message"] = "Successfully Added This Faculty Record => $name";
        } else {
            $result["error"] = true;
            $result["message"] = "Failed to Add This Faculty Record => $name";
        }
    } else {
        $result["error"] = true;
        $result["message"] = "This Name Does Exist In The DB, put another!";
    }
}

if ($action == "delete") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $stmt_verify = $pdo->query("SELECT * FROM disciplines WHERE id = '$id'");
    $find_stmt_verify_num_of_rows = $stmt_verify->rowCount();
    if ($find_stmt_verify_num_of_rows >= 1) {
        $stmt = $pdo->query("DELETE FROM disciplines WHERE id = '$id'");
        if ($stmt) {
            $result["message"] = "Successfully Deleted This Faculty Record => $name";
        } else {
            $result["error"] = true;
            $result["message"] = "Failed to Delete This Faculty Record => $name";
        }
    } else {
        $result["error"] = true;
        $result["message"] = "This Record Does Not Exist In The DB!";
    }
}
if ($action == "update") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $stmt_verify = $pdo->query("SELECT * FROM disciplines WHERE name = '$name' and id != '$id'");
    $find_stmt_verify_num_of_rows = $stmt_verify->rowCount();
    if (!$find_stmt_verify_num_of_rows >= 1) {
        $stmt = $pdo->query("UPDATE disciplines 
                                        SET
                                            name = '$name'
                                        WHERE
                                            id = '$id'
                                        ");
        if ($stmt) {
            $result["message"] = "Successfully Updated This Faculty Record => $name";
        } else {
            $result["error"] = true;
            $result["message"] = "Failed to Update This Faculty Record => $name";
        }
    } else {
        $result["error"] = true;
        $result["message"] = "This Name $name Does Exist In The DB, put another name!";
    }
}

if ($action == "deleteAll") {
    $stmt = $pdo->query("DELETE FROM disciplines");
    if ($stmt) {
        $result["message"] = "Successfully Deleted All Disciplines";
    } else {
        $result["error"] = true;
        $result["message"] = "Failed to Delete All Disciplines";
    }
}

echo json_encode($result);
?>
