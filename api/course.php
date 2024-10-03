<?php
// Set headers for CORS and content type
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Database connection
$link = mysqli_connect('localhost', 'root', '', 'student_db'); // Change 'localhost' if necessary
mysqli_set_charset($link, 'utf8');

// Get request method
$requestMethod = $_SERVER["REQUEST_METHOD"];

switch ($requestMethod) {
    case 'GET':
        if (isset($_GET['course_code']) && !empty($_GET['course_code'])) {
            $course_code = mysqli_real_escape_string($link, $_GET['course_code']);
            $sql = "SELECT * FROM course WHERE course_code = '$course_code'";
        } else {
            $sql = "SELECT * FROM course";
        }
        
        $result = mysqli_query($link, $sql);
        
        if ($result) {
            $courses = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $courses[] = $row;
            }
            echo json_encode($courses);
        } else {
            echo json_encode(array("message" => "Failed to retrieve courses."));
        }
        break;

        case 'POST':
            $input = json_decode(file_get_contents("php://input"), true);
            
            if (isset($input['course_code']) && isset($input['course_name']) && isset($input['credit'])) {
                $course_code = mysqli_real_escape_string($link, $input['course_code']);
                $course_name = mysqli_real_escape_string($link, $input['course_name']);
                $credit = mysqli_real_escape_string($link, $input['credit']);
                
                $sql = "INSERT INTO course (course_code, course_name, credit) VALUES ('$course_code', '$course_name', '$credit')";
                
                if (mysqli_query($link, $sql)) {
                    echo json_encode(array("message" => "Course created successfully."));
                } else {
                    echo json_encode(array("message" => "Failed to create course."));
                }
            } else {
                echo json_encode(array("message" => "Invalid input."));
            }
            break;
        
            case 'PUT':
                // Parse the input data
                $input = json_decode(file_get_contents("php://input"), true);
                
                // Check if all required fields are present
                if (isset($input['course_code']) && isset($input['course_name']) && isset($input['credit'])) {
                    $course_code = $input['course_code'];
                    $course_name = $input['course_name'];
                    $credit = $input['credit'];
                    
                    // Prepare SQL statement
                    $sql = "UPDATE course SET course_name = ?, credit = ? WHERE course_code = ?";
                    
                    if ($stmt = mysqli_prepare($link, $sql)) {
                        // Bind parameters to the SQL query
                        mysqli_stmt_bind_param($stmt, "sis", $course_name, $credit, $course_code);
                        
                        // Execute the query
                        if (mysqli_stmt_execute($stmt)) {
                            echo json_encode(array("message" => "Course updated successfully."));
                        } else {
                            echo json_encode(array("message" => "Failed to update course."));
                        }
                        
                        // Close the statement
                        mysqli_stmt_close($stmt);
                    } else {
                        echo json_encode(array("message" => "Failed to prepare SQL statement."));
                    }
                } else {
                    echo json_encode(array("message" => "Invalid input."));
                }
                break;
            

        case 'DELETE':
            // Get course_code from query parameter
            if (isset($_GET['course_code']) && !empty($_GET['course_code'])) {
                $course_code = mysqli_real_escape_string($link, $_GET['course_code']);
                
                $sql = "DELETE FROM course WHERE course_code = '$course_code'";
                
                if (mysqli_query($link, $sql)) {
                    echo json_encode(array("message" => "Course deleted successfully."));
                } else {
                    echo json_encode(array("message" => "Failed to delete course."));
                }
            } else {
                echo json_encode(array("message" => "Course code not provided."));
            }
            break;
    
        default:
            echo json_encode(array("message" => "Invalid request method."));
            break;
}


mysqli_close($link);
?>
