<?php
session_start();
include 'config.php'; // Include MySQLi connection
require 'smtp/PHPMailerAutoload.php'; // Include PHPMailer (or use Composer for autoloading)

// Function to send OTP to user's email
function sendOTP($email, $otp)
{
    // Send OTP via email using PHPMailer
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com';  // Set your SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'programhost984@gmail.com'; // SMTP username
    $mail->Password = 'ifvepnaoaeoxbaus'; // SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('programhost984@gmail.com', 'Admin');
    $mail->addAddress($email); // User's email
    $mail->Subject = 'OTP for Password Recovery';
    $mail->Body = "Your OTP is: $otp";

    if (!$mail->send()) {
        return false; // Return false if sending fails
    }
    return true; // Return true if sending succeeds
}

// Handle login request
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user by email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Check if user exists and password matches
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php"); // Redirect to dashboard/homepage
        exit();
    } else {
        echo "Invalid email or password!";
    }
}

// Handle signup request
if (isset($_POST['signup'])) {
    $email = $_POST['signup_email'];
    $password = password_hash($_POST['signup_password'], PASSWORD_DEFAULT); // Hash password

    // Check if email is already registered
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Email is already registered!";
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $password);
        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;
            $_SESSION['user_id'] = $user_id; // Automatically log in the new user
            header("Location: index.php"); // Redirect to homepage/dashboard
            exit();
        } else {
            echo "Signup failed. Please try again!";
        }
    }
}

// Handle forgot password request
if (isset($_POST['forgot_password'])) {
    $email = $_POST['forgot_email'];

    // Check if email exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Generate OTP and store it in session
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['otp_email'] = $email;

        // Send OTP to user's email
        if (sendOTP($email, $otp)) {
            echo "OTP has been sent to your email!";
        } else {
            echo "Could not send OTP. Please try again.";
        }
    } else {
        echo "Email not registered!";
    }
}

// Handle OTP verification
if (isset($_POST['verify_otp'])) {
    $entered_otp = $_POST['otp'];

    // Check if OTP matches the one stored in session
    if (isset($_SESSION['otp']) && $_SESSION['otp'] == $entered_otp) {
        $_SESSION['otp_verified'] = true;
        echo "OTP verified! You can now reset your password.";
    } else {
        echo "Invalid OTP!";
    }
}

// Handle password reset request
if (isset($_POST['reset_password']) && isset($_SESSION['otp_verified']) && $_SESSION['otp_verified'] == true) {
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    $email = $_SESSION['otp_email'];

    // Update user's password in the database
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $new_password, $email);
    if ($stmt->execute()) {
        echo "Password reset successful! You can now login.";
        unset($_SESSION['otp_verified'], $_SESSION['otp'], $_SESSION['otp_email']); // Clear session data
    } else {
        echo "Password reset failed. Please try again!";
    }
}
?>

<!-- HTML for Login, Signup, Forgot Password, OTP Verification, and Reset Password forms -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login, Signup & Password Reset</title>
    <style>
        * {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        body {
            margin: 0;
        }

        .wrapper {
            width: 100%;
            height: 100vh;
            background: #1c1c1c;
            display: table;
            vertical-align: middle;
            font-family: 'Dosis', sans-serif;
        }

        .sign-panels {
            width: 450px;
            background: #262323;
            padding: 40px 40px;
            margin: 150px auto;
            border-radius: 20px;
            text-align: center;
            border: 3px solid red;
            box-shadow: rgba(136, 165, 191, 0.48) 6px 2px 16px 0px, rgba(255, 255, 255, 0.8) -6px -2px 16px 0px;
        }

        .sign-panels:hover {
            box-shadow: rgba(0, 0, 0, 0.4) 0px 30px 90px;
        }

        .login,
        .signup,
        .recover-password,
        .verify-otp,
        .reset-password {
            position: relative;
        }

        .title {
            color: #9f9f9f;
        }

        .title span {
            display: block;
            font-size: 46px;
            font-weight: bold;
            padding: 20px 0;
        }

        .title p {
            font-size: 20px;
            font-weight: 500;
        }

        .btn-face,
        .btn-twitter {
            color: #fff;
            display: inline-block;
            width: 200px;
            font-size: 20px;
            height: 50px;
            border-radius: 50px;
            text-decoration: none;
            padding: 11px 0;
            font-weight: 500;
            margin-right: 25px;
        }

        .btn-face {
            background: #5397d7;
        }

        .btn-twitter {
            background: #40b9e0;
        }

        .or {
            margin: 35px 0;
            font-weight: 600;
            color: #9f9f9f;
            position: relative;
        }

        .or:after {
            content: '';
            display: block;
            width: 100%;
            height: 1px;
            background: #cecece;
            position: absolute;
            top: 50%;
            left: 0;
        }

        .or span {
            display: block;
            background: #fff;
            width: 50px;
            margin: auto;
            position: relative;
            z-index: 2;
        }

        .sign-panels input {
            width: 100%;
            display: block;
            margin-bottom: 15px;
            height: 50px;
            border-radius: 50px;
            border: none;
            background: #ededed;
            text-align: center;
            padding: 10px;
            font-size: 15px;
            color: #7c7c7c;
            font-weight: 500;
        }

        .sign-panels input:focus {
            outline: none;
        }

        .sign-panels input[type="checkbox"] {
            display: none;
        }

        .sign-panels input[type="checkbox"]+label {
            display: block;
            width: 50%;
            text-align: left;
            padding-left: 60px;
            cursor: pointer;
            color: #828282;
            font-weight: 500;
            margin-top: 10px;
            float: left;
            height: 50px;
            padding-top: 15px;
        }

        .sign-panels input[type="checkbox"]+label:before {
            content: '';
            display: block;
            width: 15px;
            height: 15px;
            background: #dbdbdb;
            position: absolute;
            left: 30px;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 0 0 5px #ededed;
            transition: all .3s ease;
        }

        .sign-panels input[type="checkbox"]:checked+label:before {
            background: #FF5722;
            box-shadow: 0 0 0 5px #FF5722;
            transition: all .3s ease;
        }

        .btn-signin,
        .btn-signup,
        .btn-submit {
            display: inline-block;
            width: 50%;
            margin-top: 10px;
            height: 50px;
            background: #ec581e;
            border-radius: 50px;
            padding: 11px;
            font-size: 20px;
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            border: none;
            cursor: pointer;
        }

        .btn-reset,
        .btn-member,
        .btn-fade {
            font-size: 17px;
            font-weight: 500;
            color: #9f9f9f;
            display: block;
            margin: 30px 0;
            text-decoration: none;
        }

        .btn-member {
            margin-top: 31px;
        }

        .notification p {
            font-size: 20px;
            font-weight: 600;
            color: #9f9f9f;
        }

        .notification span {
            color: #ec581e;
        }

        .error {
            display: block;
            color: #ec581e;
            font-size: 20px;
            font-weight: 600;
            margin: 15px 0;
        }

        @media screen and (max-width: 768px) {
            .sign-panels {
                width: 90%;
                padding: 40px;
            }
        }

        @media screen and (max-width: 570px) {
            .sign-panels {
                padding: 40px 20px;
            }

            .btn-face,
            .btn-twitter {
                width: 100%;
                margin-bottom: 25px;
            }
        }

        @media screen and (max-width: 480px) {
            .sign-panels input[type="checkbox"]+label {
                width: 100%;
            }

            .btn-signin,
            .btn-signup,
            .btn-submit {
                width: 80%;
            }

            .title span {
                font-size: 36px;
            }
        }

        .vipin {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="sign-panels login">
            <div class="title">
                <span>Login </span>

            </div>
            <form action="" method="POST">
                <input type="email" id="email" name="email" placeholder="Email" required><br>
                <input type="password" id="password" name="password" placeholder="Password" required>
                <button type="submit" name="login" class="btn-signin">Sign In</button>
            </form>

            <!-- <div class="vipin">
                <a href="#" class="btn-reset">Forgot Password?</a>
                <a href="#" class="btn-member">Create Account</a>
            </div> -->
        </div>

        <div class="sign-panels signup">
            <div class="title">
                <span>Sign Up</span>
                <p>To create an account</p>
            </div>
            <form action="" method="POST">
                <input type="email" id="signup_email" name="signup_email" placeholder="Email" required><br>
                <input type="password" id="signup_password" name="signup_password" placeholder="Password" required><br>
                <button type="submit" name="signup" class="btn-signup">Sign Up</button>
            </form><br>
            <a href="#" class="btn-login">Back to Login</a>
        </div>

        <div class="sign-panels recover-password">
            <div class="title">
                <span>Forgot Password</span>
                <p>Enter your email to reset password</p>
            </div>
            <form id="forgotPasswordForm" action="send_otp.php" method="POST">
                <input type="email" id="forgot_email" name="email" placeholder="Email" required><br>
                <button type="submit" name="forgot_password" class="btn-submit">Send OTP</button>
            </form><br>
            <a href="#" class="btn-login">Back to Login</a>
            <div class="notification" style="display:none;">
                <p>Please wait... <span>we are sending you an OTP.</span></p>
            </div>
        </div>

        <div class="sign-panels verify-otp">
            <div class="title">
                <span>Verify OTP</span>
                <p>Enter the OTP sent to your email</p>
            </div>
            <form action="" method="POST">
                <input type="text" id="otp" name="otp" placeholder="Enter OTP" required><br>
                <button type="submit" name="verify_otp" class="btn-submit">Verify OTP</button>
            </form>
        </div>

        <div class="sign-panels reset-password">
            <div class="title">
                <span>Reset Password</span>
                <p>Enter your new password</p>
            </div>
            <form action="" method="POST">
                <input type="password" id="new_password" name="new_password" placeholder="New Password" required><br>
                <button type="submit" name="reset_password" class="btn-submit">Reset Password</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('.signup').hide();
        $('.recover-password').hide();
        $('.verify-otp').hide();
        $('.reset-password').hide();

        $('.btn-reset').click(function () {
            $('.login').hide();
            $('.recover-password').fadeIn(300);
        });

        $('.btn-member').click(function () {
            $('.login').hide();
            $('.signup').fadeIn(300);
        });

        $('.btn-login').click(function () {
            $('.signup').hide();
            $('.recover-password').hide();
            $('.verify-otp').hide();
            $('.reset-password').hide();
            $('.login').fadeIn(300);
        });

        $('#forgotPasswordForm').on('submit', function (e) {
            e.preventDefault();
            $('.recover-password form').hide();
            $('.notification').fadeIn(300);
            $.ajax({
                type: 'POST',
                url: 'send_otp.php',
                data: $(this).serialize(), // Send all form data
                success: function (response) {
                    console.log(response);
                    setTimeout(function () {
                        $('.recover-password').hide();
                        $('.verify-otp').fadeIn(300);
                    }, 3000); // Show OTP verification form after 3 seconds
                },
                error: function () {
                    alert('An error occurred while sending OTP.');
                }
            });
        });

        $('.verify-otp form').on('submit', function (e) {
            e.preventDefault();
            $('.verify-otp').hide();
            $('.reset-password').fadeIn(300);
        });

        $('.reset-password form').on('submit', function (e) {
            e.preventDefault();
            $('.reset-password').hide();
            $('.login').fadeIn(300);
        });
    </script>
</body>

</html>