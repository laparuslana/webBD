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
    $result["schedule"] = "read";
    $stmt = $pdo->query("SELECT schedule.id, groups.name as 'group', teachers.surname as 'teacher', disciplines.name as 'discipline', 
       schedule.name, schedule.time,schedule.classroom FROM schedule
       INNER JOIN teachers on schedule.teacher_id = teachers.id
       INNER JOIN disciplines on schedule.discipline_id = disciplines.id
       ");
    $tmp = $pdo->query('SELECT COUNT(*) FROM schedule');
    $data_exists = ($tmp->fetchColumn() > 0) ? true : false;
    if (!$data_exists) {
        $result['error'] = true;
        $result['message'] = "No schedule Found";
    }
    $std = array();
    while ($rows = $stmt->fetch(PDO::FETCH_ASSOC)) {
        array_push($std, $rows);
    }
    $result["schedule"] = $std;
}
if ($action == "create") {
    $id = $_POST["id"];
    $teacher = $_POST["teacher"];
    $discipline = $_POST["discipline"];
    $classroom = $_POST["classroom"];
    $time = $_POST["time"];
    $stmt_verify = $pdo->query("SELECT * FROM schedule WHERE classroom = '$classroom'");
    $find_stmt_verify_num_of_rows = $stmt_verify->rowCount();
    if ($find_stmt_verify_num_of_rows < 1) {
        $stmt = $pdo->query("INSERT INTO schedule (teacher_id, discipline_id, classroom, time) 
VALUES ((SELECT t.id FROM teachers t WHERE t.surname ='$teacher'), 
        (SELECT d.id FROM disciplines d WHERE d.name ='$discipline'), '$classroom', '$time')");
        if ($stmt) {
            $result["message"] = "Successfully Added This Student's Record => $name";
        } else {
            $result["error"] = true;
            $result["message"] = "Failed to Add This Student's Record => $name";
        }
    } else {
        $result["error"] = true;
        $result["message"] = "This classroom $classroom Does Exist In The DB, put another!";
    }
}

if ($action == "deleteAll") {
    $stmt = $pdo->query("DELETE FROM schedule");
    if ($stmt) {
        $result["message"] = "Successfully Deleted All schedule";
    } else {
        $result["error"] = true;
        $result["message"] = "Failed to Delete All schedule";
    }
}
if ($action == "delete") {
    $id = $_POST["id"];
    $classroom = $_POST["classroom"];
    $stmt_verify = $pdo->query("SELECT * FROM schedule WHERE id = '$id'");
    $find_stmt_verify_num_of_rows = $stmt_verify->rowCount();
    if ($find_stmt_verify_num_of_rows >= 1) {
        $stmt = $pdo->query("DELETE FROM schedule WHERE id = '$id'");
        if ($stmt) {
            $result["message"] = "Successfully Deleted This Student's Record => $classroom";
        } else {
            $result["error"] = true;
            $result["message"] = "Failed to Delete This Student's Record => $classroom";
        }
    } else {
        $result["error"] = true;
        $result["message"] = "This Record Does Not Exist In The DB!";
    }
}
if ($action == "update") {
    $id = $_POST["id"];
    $teacher = $_POST["teacher"];
    $discipline = $_POST["discipline"];
    $classroom = $_POST["classroom"];
    $time = $_POST["time"];
    $stmt_verify = $pdo->query("SELECT * FROM schedule WHERE classroom = '$classroom' and id != '$id'");
    $find_stmt_verify_num_of_rows = $stmt_verify->rowCount();
    if (!$find_stmt_verify_num_of_rows >= 1) {
        $stmt = $pdo->query("UPDATE schedule 
                                        SET
                                            teacher_id = (SELECT t.id FROM teachers t WHERE t.surname ='$teacher'),
                                            discipline_id = (SELECT d.id FROM disciplines d WHERE d.name ='$discipline'),
                                            classroom = '$classroom',
                                            time = '$time'
                                        WHERE
                                            id = '$id'
                                        ");
        if ($stmt) {
            $result["message"] = "Successfully Updated This Student's Record => $classroom";
        } else {
            $result["error"] = true;
            $result["message"] = "Failed to Update This Student's Record => $classroom";
        }
    } else {
        $result["error"] = true;
        $result["message"] = "This classroom $classroom Does Exist In The DB, put another classroom!";
    }
}

if ($action == "deleteAll") {
    $stmt = $pdo->query("DELETE FROM schedule");
    if ($stmt) {
        $result["message"] = "Successfully Deleted All schedule";
    } else {
        $result["error"] = true;
        $result["message"] = "Failed to Delete All schedule";
    }
}


echo json_encode($result);
?>
