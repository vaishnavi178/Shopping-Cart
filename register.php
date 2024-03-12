<?php
session_start();
include "db.php";

if (isset($_POST["f_name"])) {
    $f_name = mysqli_real_escape_string($con, $_POST['f_name']);
    $l_name = mysqli_real_escape_string($con, $_POST['l_name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];
    $mobile = mysqli_real_escape_string($con, $_POST['mobile']);
    $address1 = mysqli_real_escape_string($con, $_POST['address1']);
    $address2 = mysqli_real_escape_string($con, $_POST['address2']);

    // Add password hashing
   // $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Your validation and checks here...

    // Existing email address check
    $sql = "SELECT user_id FROM user_info WHERE email = '$email' LIMIT 1";
    $check_query = mysqli_query($con, $sql);
    $count_email = mysqli_num_rows($check_query);

    if ($count_email > 0) {
        echo "<div class='alert alert-danger'>
                <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                <b>Email Address is already available. Try another email address.</b>
              </div>";
        exit();
    } else {
        $sql = "INSERT INTO user_info ( first_name, last_name, email, password, mobile, address1, address2) 
                VALUES ( '$f_name', '$l_name', '$email', '$password', '$mobile', '$address1', '$address2')";
        $run_query = mysqli_query($con, $sql);

        if ($run_query) {
            $_SESSION["uid"] = mysqli_insert_id($con);
            $_SESSION["name"] = $f_name;
            $ip_add = getenv("REMOTE_ADDR");
            $sql = "UPDATE cart SET user_id = '$_SESSION[uid]' WHERE ip_add='$ip_add' AND user_id = -1";
            if (mysqli_query($con, $sql)) {
                echo "register_success";
                echo "<script> location.href='store.php'; </script>";
                exit;
            }
        } else {
            echo "<div class='alert alert-danger'>
                    <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    <b>Error: " . mysqli_error($con) . "</b>
                  </div>";
            exit();
        }
    }
}
?>
