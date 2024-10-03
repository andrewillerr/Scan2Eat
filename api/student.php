<?php
// Set headers to allow cross-origin requests and specify content type
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Set up database connection
$link = mysqli_connect('localhost', 'root', '', 'student_db');
mysqli_set_charset($link, 'utf8');

// Get the request method
$requestMethod = $_SERVER["REQUEST_METHOD"];

// Handle GET request
if ($requestMethod == 'GET') {
    if (isset($_GET['student_code']) && !empty($_GET['student_code'])) {
        $student_code = mysqli_real_escape_string($link, $_GET['student_code']);
        $sql = "SELECT * FROM student WHERE student_code = '$student_code'";
    } else {
        $sql = "SELECT * FROM student";
    }
    
    $result = mysqli_query($link, $sql);
    $arr = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $arr[] = $row;
    }
    echo json_encode($arr);
}

// Handle POST request
if ($requestMethod == 'POST') {
    $data = file_get_contents('php://input');
    $result = json_decode($data, true);

    if (!empty($result)) {
        $student_code = mysqli_real_escape_string($link, $result['student_code']);
        $student_name = mysqli_real_escape_string($link, $result['student_name']);
        $gender = mysqli_real_escape_string($link, $result['gender']);

        // SQL command to insert new student record
        $sql = "INSERT INTO student (student_code, student_name, gender) VALUES ('$student_code', '$student_name', '$gender')";
        $insert_result = mysqli_query($link, $sql);

        if ($insert_result) {
            echo json_encode(['status' => 'ok', 'message' => 'Student added successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error adding student']);
        }
    }
}

// Handle PUT request
if ($requestMethod == 'PUT') {
    $data = file_get_contents('php://input');
    $result = json_decode($data, true);

    if (!empty($result)) {
        $student_code = mysqli_real_escape_string($link, $result['student_code']);
        $student_name = mysqli_real_escape_string($link, $result['student_name']);
        $gender = mysqli_real_escape_string($link, $result['gender']);

        $sql = "UPDATE student SET student_name = '$student_name', gender = '$gender' WHERE student_code = '$student_code'";
        $update_result = mysqli_query($link, $sql);

        if ($update_result) {
            echo json_encode(['status' => 'ok', 'message' => 'Update Data Complete']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error']);
        }
    }
}

// Handle DELETE request
if ($requestMethod == 'DELETE') {
    if (isset($_GET['student_code']) && !empty($_GET['student_code'])) {
        $student_code = mysqli_real_escape_string($link, $_GET['student_code']);
        $sql = "DELETE FROM student WHERE student_code = '$student_code'";
        $delete_result = mysqli_query($link, $sql);

        if ($delete_result) {
            http_response_code(200); // Set HTTP response code to 200 OK
            echo json_encode(['status' => 'ok', 'message' => 'Delete Data Complete']);
        } else {
            http_response_code(500); // Set HTTP response code to 500 Internal Server Error
            echo json_encode(['status' => 'error', 'message' => 'Error deleting student']);
        }
    } else {
        http_response_code(400); // Set HTTP response code to 400 Bad Request
        echo json_encode(['status' => 'error', 'message' => 'Student code not provided']);
    }
}

// Close the database connection
mysqli_close($link);
?>
