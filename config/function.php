<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'dbcon.php'; // Using require_once to avoid multiple inclusions

if (!function_exists('validate')) {
    function validate($inputData) {
        global $conn;
        $validatedData = mysqli_real_escape_string($conn, $inputData);
        return trim($validatedData);
    }
}

if (!function_exists('redirect')) {
    function redirect($url, $status) {
        $_SESSION['status'] = $status;
        header('Location: '.$url);
        exit(0);
    }
}

if (!function_exists('alertMessage')) {
    function alertMessage() {
        if (isset($_SESSION['status'])) {
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <h6>'.$_SESSION['status'].'</h6>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            unset($_SESSION['status']);
        }
    }
}

if (!function_exists('insert')) {
    function insert($tableName, $data) {
        global $conn;
        $table = validate($tableName);
        $columns = array_keys($data);
        $values = array_values($data);
        $finalColumn = implode(',', $columns);
        $finalValues = "'".implode("', '", $values)."'";
        $query = "INSERT INTO $table ($finalColumn) VALUES ($finalValues)";
        $result = mysqli_query($conn, $query);
        return $result;
    }
}

if (!function_exists('update')) {
    function update($tableName, $id, $data) {
        global $conn;
        $table = validate($tableName);
        $id = validate($id);
        $updateDataString = "";
        foreach ($data as $column => $value) {
            $updateDataString .= $column.'='."'$value',";
        }
        $finalUpdateData = substr(trim($updateDataString), 0, -1);
        $query = "UPDATE $table SET $finalUpdateData WHERE id='$id'";
        $result = mysqli_query($conn, $query);
        return $result;
    }
}

if (!function_exists('getAll')) {
    function getAll($tableName, $status = NULL) {
        global $conn;
        $table = validate($tableName);
        $status = validate($status);
        if ($status == 'status') {
            $query = "SELECT * FROM $table WHERE $status='0'";
        } else {
            $query = "SELECT * FROM $table";
        }
        return mysqli_query($conn, $query);
    }
}

if (!function_exists('getById')) {
    function getById($tableName, $id) {
        global $conn;
        $table = validate($tableName);
        $id = validate($id);
        $query = "SELECT * FROM $table WHERE id='$id' LIMIT 1";
        $result = mysqli_query($conn, $query);
        if ($result) {
            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
                $response = [
                    'status' => 200,
                    'data' => $row,
                    'message' => 'Record Found'
                ];
                return $response;
            } else {
                $response = [
                    'status' => 404,
                    'message' => 'No Data Found'
                ];
                return $response;
            }
        } else {
            $response = [
                'status' => 500,
                'message' => 'Something Went Wrong'
            ];
            return $response;
        }
    }
}

if (!function_exists('delete')) {
    function delete($tableName, $id) {
        global $conn;
        $table = validate($tableName);
        $id = validate($id);
        $query = "DELETE FROM $table WHERE id='$id' LIMIT 1";
        $result = mysqli_query($conn, $query);
        return $result;
    }
}

if (!function_exists('checkParamId')) {
    function checkParamId($type) {
        if (isset($_GET[$type])) {
            if ($_GET[$type] != '') {
                return $_GET[$type];
            } else {
                return '<h5>No Id Found</h5>';
            }
        } else {
            return '<h5>No Id Given</h5>';
        }
    }
}

if (!function_exists('logoutSession')) {
    function logoutSession() {
        // Unset the admin login session
        unset($_SESSION['loggedIn']);
        unset($_SESSION['loggedInUser']);

        // Clear the cart session data
        unset($_SESSION['menuItems']);
        unset($_SESSION['menuItemsIds']);
    }
}

if (!function_exists('customerLogoutSession')) {
    function customerLogoutSession() {
        // Unset the customer login session
        unset($_SESSION['customerLoggedIn']);
        unset($_SESSION['customerUser']);

        // Clear the cart session data
        unset($_SESSION['menuItems']);
        unset($_SESSION['menuItemsIds']);
    }
}

?>
