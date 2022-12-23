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
    $result["teachers"] = "read";
    $stmt = $pdo->query("SELECT * FROM teachers");
    $tmp = $pdo->query('SELECT COUNT(*) FROM teachers');
    $data_exists = ($tmp->fetchColumn() > 0) ? true : false;
    if (!$data_exists) {
        $result['error'] = true;
        $result['message'] = "No Teachers Found";
    }
    $std = array();
    while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($std, $rows);
    }
    $result["teachers"] = $std;
}
if ($action == "create") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $stmt_verify = $pdo->query("SELECT * FROM teachers WHERE email = '$email'");
    $find_stmt_verify_num_of_rows = $stmt_verify->rowCount();
    if ($find_stmt_verify_num_of_rows < 1) {
        $stmt = $pdo->query("INSERT INTO teachers (name, surname, email, phone) VALUES ('$name','$surname', '$email', '$phone')");
        if ($stmt) {
            $result["message"] = "Successfully Added This Teacher's Record => $name $surname";
        } else {
            $result["error"] = true;
            $result["message"] = "Failed to Add This Teacher's Record => $name $surname";
        }
    } else {
        $result["error"] = true;
        $result["message"] = "This Email $email Does Exist In The DB, put another!";
    }
}

if ($action == "delete") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $stmt_verify = $pdo->query("SELECT * FROM teachers WHERE id = '$id'");
    $find_stmt_verify_num_of_rows = $stmt_verify->rowCount();
    if ($find_stmt_verify_num_of_rows >= 1) {
        $stmt = $pdo->query("DELETE FROM teachers WHERE id = '$id'");
        if ($stmt) {
            $result["message"] = "Successfully Deleted This Teacher's Record => $name $surname";
        } else {
            $result["error"] = true;
            $result["message"] = "Failed to Delete This Teacher's Record => $name $surname";
        }
    } else {
        $result["error"] = true;
        $result["message"] = "This Record Does Not Exist In The DB!";
    }
}
if ($action == "update") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $stmt_verify = $pdo->query("SELECT * FROM teachers WHERE email = '$email' and id != '$id'");
    $find_stmt_verify_num_of_rows = $stmt_verify->rowCount();
    if (!$find_stmt_verify_num_of_rows >= 1) {
        $stmt = $pdo->query("UPDATE teachers 
                                        SET
                                            name = '$name',
                                            surname = '$surname',
                                            email = '$email',
                                            phone = '$phone'
                                        WHERE
                                            id = '$id'
                                        ");
        if ($stmt) {
            $result["message"] = "Successfully Updated This Teacher's Record => $name $surname";
        } else {
            $result["error"] = true;
            $result["message"] = "Failed to Update This Teacher's Record => $name $surname";
        }
    } else {
        $result["error"] = true;
        $result["message"] = "This Email $email Does Exist In The DB, put another email!";
    }
}

if ($action == "deleteAll") {
    $stmt = $pdo->query("DELETE FROM teachers");
    if ($stmt) {
        $result["message"] = "Successfully Deleted All Teachers";
    } else {
        $result["error"] = true;
        $result["message"] = "Failed to Delete All Teachers";
    }
}

echo json_encode($result);
?>
