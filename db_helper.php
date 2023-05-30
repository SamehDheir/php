<?php
class DbHelper
{


    private $conn;
    //    public function __construct()
    //    {
    //        createDbConnection();
    //    }

    function createDbConnection()
    {
        try {
            $this->conn = new mysqli("localhost", "root", "", "iugphp");
        } catch (Exception $error) {
            echo $error->getMessage();
        }
    }
    function insertNewStudent($name, $email, $password, $age)
    {
        try {
            $sql = "INSERT INTO students (name, email, password, age) VALUES ('$name', '$email', '$password', '$age')";
            $result = $this->conn->query($sql);
    
            if ($result == true) {
                $response = $this->createStudentResponse(
                    $this->conn->insert_id,
                    $name,
                    $email,
                    $password,
                    $age
                );
                $this->createResponse(true, $response);
            } else {
                $this->createResponse(false, "Data has not been inserted");
            }
        } catch (Exception $error) {
            $this->createResponse(false, $error->getMessage());
        }
    }
    function getAllStudents()
    {
        try {
            $sql = "select * from students";
            $result = $this->conn->query($sql);

            $count =  $result->num_rows;
            if ($count > 0) {
                $all_students_array = array();
                while ($row = $result->fetch_assoc()) {
                    $id = $row["id"];
                    $name = $row["name"];
                    $email = $row["email"];
                    $password = $row["password"];
                    $age = $row["age"];
                    // create associative array for the student
                    $student_array = $this->createStudentResponse($id, $name, $email, $password, $age);
                    array_push($all_students_array, $student_array);
                }
                $this->createResponse(true, $count, $all_students_array);
            } else {
                // throw Exception("No Data Found");
            }
        } catch (Exception $exception) {
            $this->createResponse(false, 0, array("error" => $exception->getMessage()));
        }
    }
    function getStudentById($id)
    {
        $sql = "select * from students where id = $id";
        $result = $this->conn->query($sql);
        try {
            if ($result->num_rows == 0) {
                throw new Exception("there are no students with the passed id");
            } else {
                $row =   $result->fetch_assoc();
                $id = $row["id"];
                $name = $row["name"];
                $email = $row["email"];
                $password = $row["password"];
                $age = $row["age"];
                // create associative array for the student
                $student_array = $this->createStudentResponse($id, $name, $email, $password, $age);
                $this->createResponse(true, 1, $student_array);
            }
        } catch (Exception $exception) {
            http_response_code(400);
            $this->createResponse(false, 0, array("error" => $exception->getMessage()));
        }
    }
    function deleteStudent($id)
    {
        try {
            $sql = "delete from students where id = $id";
            $result = $this->conn->query($sql);

            if (mysqli_affected_rows($this->conn) > 0) {
                $this->createResponse(true, 1, array("data" => "student has been deleted"));
            } else {
                throw new Exception("There are no students with the passed id");
            }
        } catch (Exception $exception) {
            $this->createResponse(false, 0, array("error" => $exception->getMessage()));
        }
    }
    function updateStudent($id, $name, $email, $password, $age)
    {
        try {
        } catch (Exception $exception) {
            $this->createResponse(false, 0, array("error" => $exception->getMessage()));
        }
    }
    function createResponse($isSuccess, $count, $data)
    {
        echo json_encode(array(
            "success" => $isSuccess,
            "count" => $count,
            "data" => $data
        ));
    }
    function createStudentResponse($id, $name, $email, $password, $age)
    {
        return array(
            "id" => $id,
            "name" => $name,
            "email" => $email,
            "password" => $password,
            "age" => $age
        );
    }
}
