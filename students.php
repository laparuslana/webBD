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

//$connect = new mysqli($host_name, $host_user, $host_pass);
//if ($connect->connect_error) {
//    die($connect->connect_error);
//} else {
//    echo "Connection successful!";
//}

//if(isset($connect) && isset($host_db)){
//    $connect_db = mysql_select_db($host_db);
//    if($connect_db){
//        $result["is_db_connected"]=true;
//        $result["connection_mag"]="Successfully Connected!";
//    }else{
//        $result["is_db_connected"]=false;
//        $result["connection_mag"]="Err: DB Name is not found!";
//    }
//}else{
//    $result["is_db_connected"]=false;
//    $result["connection_mag"]="Err: Connection setup is not right!";
//}

$action = "";
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

if ($action == "read") {
    $result["students"] = "read";
    $stmt = $pdo->query("SELECT students.id, students.name, students.email, students.phone, groups.name as 'group' 
FROM students
INNER JOIN groups on students.group_id = groups.id");
    $tmp = $pdo->query('SELECT COUNT(*) FROM students');
    $data_exists = ($tmp->fetchColumn() > 0) ? true : false;
    if (!$data_exists) {
        $result['error'] = true;
        $result['message'] = "No Students Found";
    }
    $std = array();
    while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($std, $rows);
    }
    $result["students"] = $std;
}
if ($action == "create") {
    $id = $_POST["id"];
    $group = $_POST["group"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $stmt_verify = $pdo->query("SELECT * FROM students WHERE email = '$email'");
    $find_stmt_verify_num_of_rows = $stmt_verify->rowCount();
    if ($find_stmt_verify_num_of_rows < 1) {
        $stmt = $pdo->query("INSERT INTO students (group_id, name, email, phone) 
VALUES ((SELECT g.id FROM groups g WHERE g.name ='$group'),'$name', '$email', '$phone')");
        if ($stmt) {
            $result["message"] = "Successfully Added This Student's Record => $name";
        } else {
            $result["error"] = true;
            $result["message"] = "Failed to Add This Student's Record => $name";
        }
    } else {
        $result["error"] = true;
        $result["message"] = "This Email $email Does Exist In The DB, put another!";
    }
}

if ($action == "deleteAll") {
    $stmt = $pdo->query("DELETE FROM students");
    if ($stmt) {
        $result["message"] = "Successfully Deleted All Students";
    } else {
        $result["error"] = true;
        $result["message"] = "Failed to Delete All Students";
    }
}
if ($action == "delete") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $stmt_verify = $pdo->query("SELECT * FROM students WHERE id = '$id'");
    $find_stmt_verify_num_of_rows = $stmt_verify->rowCount();
    if ($find_stmt_verify_num_of_rows >= 1) {
        $stmt = $pdo->query("DELETE FROM students WHERE id = '$id'");
        if ($stmt) {
            $result["message"] = "Successfully Deleted This Student's Record => $name";
        } else {
            $result["error"] = true;
            $result["message"] = "Failed to Delete This Student's Record => $name";
        }
    } else {
        $result["error"] = true;
        $result["message"] = "This Record Does Not Exist In The DB!";
    }
}
if ($action == "update") {
    $id = $_POST["id"];
    $group = $_POST["group"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $stmt_verify = $pdo->query("SELECT * FROM students WHERE email = '$email' and id != '$id'");
    $find_stmt_verify_num_of_rows = $stmt_verify->rowCount();
    if (!$find_stmt_verify_num_of_rows >= 1) {
        $stmt = $pdo->query("UPDATE students 
                                        SET
                                            group_id = (SELECT g.id FROM groups g WHERE g.name ='$group'),
                                            name = '$name',
                                            email = '$email',
                                            phone = '$phone'
                                        WHERE
                                            id = '$id'
                                        ");
        if ($stmt) {
            $result["message"] = "Successfully Updated This Student's Record => $name";
        } else {
            $result["error"] = true;
            $result["message"] = "Failed to Update This Student's Record => $name";
        }
    } else {
        $result["error"] = true;
        $result["message"] = "This Email $email Does Exist In The DB, put another email!";
    }
}

if ($action == "deleteAll") {
    $stmt = $pdo->query("DELETE FROM students");
    if ($stmt) {
        $result["message"] = "Successfully Deleted All Students";
    } else {
        $result["error"] = true;
        $result["message"] = "Failed to Delete All Students";
    }
}


echo json_encode($result);
?>
