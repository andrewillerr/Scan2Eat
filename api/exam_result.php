<?php 
// กำหนดค่า Access-Control-Allow-Origin เพื่อให้เครื่องอื่น ๆ สามารถเรียกใช้งานหน้านี้ได้
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// ตั้งค่าการเชื่อมต่อฐานข้อมูล
$link = mysqli_connect('127.0.0.1', 'root', '', 'student_db'); 
mysqli_set_charset($link, 'utf8');

// รับค่าของ request method
$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod == 'GET') {
    // ตรวจสอบการส่งค่า course_code
    if (isset($_GET['course_code']) && !empty($_GET['course_code'])) {
        $course_code = $_GET['course_code'];
        // คำสั่ง SQL แสดงเฉพาะข้อมูลของ course_code นั้น
        $sql = "SELECT * FROM exam_result WHERE course_code = '$course_code'";
    } else {
        // คำสั่ง SQL แสดงข้อมูลทั้งหมด
        $sql = "SELECT * FROM exam_result";
    }
    
    // สร้างตัวแปรเพื่อเก็บผลลัพธ์จากการ query
    $result = mysqli_query($link, $sql);
    
    // เก็บข้อมูลที่ได้ใน array
    $arr = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $arr[] = $row;
    }
    
    // แสดงผลลัพธ์ในรูปแบบ JSON
    echo json_encode($arr);

} 

if ($requestMethod == 'POST') {
    // รับค่าที่ส่งเข้ามาในรูปแบบ JSON
    $data = json_decode(file_get_contents("php://input"), true);

    // ตรวจสอบการรับค่าที่จำเป็น
    if (isset($data['student_code']) && isset($data['course_code']) && isset($data['point'])) {
        $student_code = $data['student_code'];
        $course_code = $data['course_code'];
        $point = $data['point'];

        // สร้างคำสั่ง SQL เพื่อเพิ่มข้อมูลใหม่
        $sql = "INSERT INTO exam_result (student_code, course_code, point) VALUES ('$student_code', '$course_code', '$point')";

        // ตรวจสอบการเพิ่มข้อมูล
        if (mysqli_query($link, $sql)) {
            http_response_code(200);
            echo json_encode(array("message" => "Exam result inserted successfully."));
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Failed to insert exam result."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Incomplete data. 'student_code', 'course_code', and 'point' are required."));
    }
}

if ($requestMethod == 'DELETE') {
    // ตรวจสอบการส่งค่า student_code และ course_code
    if (isset($_GET['student_code']) && isset($_GET['course_code'])) {
        $student_code = $_GET['student_code'];
        $course_code = $_GET['course_code'];

        // สร้างคำสั่ง SQL เพื่อลบข้อมูลตาม student_code และ course_code
        $sql = "DELETE FROM exam_result WHERE student_code = '$student_code' AND course_code = '$course_code'";

        // ตรวจสอบการลบข้อมูล
        if (mysqli_query($link, $sql)) {
            // กรณีลบสำเร็จ
            http_response_code(200);
            echo json_encode(array("message" => "Exam result deleted successfully."));
        } else {
            // กรณีลบไม่สำเร็จ
            http_response_code(500);
            echo json_encode(array("message" => "Failed to delete exam result."));
        }
    } else {
        // กรณีข้อมูลไม่ครบ
        http_response_code(400);
        echo json_encode(array("message" => "Incomplete data. 'student_code' and 'course_code' are required."));
    }
}

if ($requestMethod == 'PUT') {
    // รับข้อมูล JSON ที่ถูกส่งมาและแปลงเป็น array
    $data = json_decode(file_get_contents("php://input"), true);
    
    // ตรวจสอบการส่งค่า student_code, course_code และ point
    if (isset($data['student_code']) && isset($data['course_code']) && isset($data['point'])) {
        $student_code = $data['student_code'];
        $course_code = $data['course_code'];
        $point = $data['point'];

        // สร้างคำสั่ง SQL เพื่ออัปเดตข้อมูล
        $sql = "UPDATE exam_result SET point = '$point' WHERE student_code = '$student_code' AND course_code = '$course_code'";

        // ตรวจสอบการอัปเดตข้อมูล
        if (mysqli_query($link, $sql)) {
            // กรณีอัปเดตสำเร็จ
            http_response_code(200);
            echo json_encode(array("message" => "Exam result updated successfully."));
        } else {
            // กรณีอัปเดตไม่สำเร็จ
            http_response_code(500);
            echo json_encode(array("message" => "Failed to update exam result."));
        }
    } else {
        // กรณีข้อมูลไม่ครบ
        http_response_code(400);
        echo json_encode(array("message" => "Incomplete data. 'student_code', 'course_code', and 'point' are required."));
    }
}
?>
