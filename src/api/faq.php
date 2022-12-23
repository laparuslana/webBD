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
    $result["faq"] = "read";
    $stmt = $pdo->query("SELECT * FROM faq");
    $tmp = $pdo->query('SELECT COUNT(*) FROM faq');
    $data_exists = ($tmp->fetchColumn() > 0) ? true : false;
    if (!$data_exists) {
        $result['error'] = true;
        $result['message'] = "No Faq Found";
    }
    $std = array();
    while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($std, $rows);
    }
    $result["faq"] = $std;
}
if ($action == "create") {
    $id = $_POST["id"];
    $question = $_POST["question"];
    $answer = $_POST["answer"];
    $stmt_verify = $pdo->query("SELECT * FROM faq WHERE question = '$question'");
    $find_stmt_verify_num_of_rows = $stmt_verify->rowCount();
    if ($find_stmt_verify_num_of_rows < 1) {
        $stmt = $pdo->query("INSERT INTO faq (question, answer) VALUES ('$question','$answer')");
        if ($stmt) {
            $result["message"] = "Successfully Added This faq Record => $question $answer";
        } else {
            $result["error"] = true;
            $result["message"] = "Failed to Add This faq Record => $question $answer";
        }
    } else {
        $result["error"] = true;
        $result["message"] = "This question Does Exist In The DB, put another!";
    }
}

if ($action == "delete") {
    $id = $_POST["id"];
    $question = $_POST["question"];
    $answer = $_POST["answer"];
    $stmt_verify = $pdo->query("SELECT * FROM faq WHERE id = '$id'");
    $find_stmt_verify_num_of_rows = $stmt_verify->rowCount();
    if ($find_stmt_verify_num_of_rows >= 1) {
        $stmt = $pdo->query("DELETE FROM faq WHERE id = '$id'");
        if ($stmt) {
            $result["message"] = "Successfully Deleted This faq Record => $question $answer";
        } else {
            $result["error"] = true;
            $result["message"] = "Failed to Delete This faq Record => $question $answer";
        }
    } else {
        $result["error"] = true;
        $result["message"] = "This Record Does Not Exist In The DB!";
    }
}
if ($action == "update") {
    $id = $_POST["id"];
    $question = $_POST["question"];
    $answer = $_POST["answer"];
    $stmt_verify = $pdo->query("SELECT * FROM faq WHERE question = '$question' and id != '$id'");
    $find_stmt_verify_num_of_rows = $stmt_verify->rowCount();
    if (!$find_stmt_verify_num_of_rows >= 1) {
        $stmt = $pdo->query("UPDATE faq 
                                        SET
                                            question = '$question',
                                            answer = '$answer'
                                        WHERE
                                            id = '$id'
                                        ");
        if ($stmt) {
            $result["message"] = "Successfully Updated This faq Record => $question $answer";
        } else {
            $result["error"] = true;
            $result["message"] = "Failed to Update This faq Record => $question $answer";
        }
    } else {
        $result["error"] = true;
        $result["message"] = "This question $question Does Exist In The DB, put another question!";
    }
}

if ($action == "deleteAll") {
    $stmt = $pdo->query("DELETE FROM faq");
    if ($stmt) {
        $result["message"] = "Successfully Deleted All faq";
    } else {
        $result["error"] = true;
        $result["message"] = "Failed to Delete All faq";
    }
}

echo json_encode($result);
?>
