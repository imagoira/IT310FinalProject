<?php
session_start();

$login_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = $_POST['username'] ?? '';
    $input_password = $_POST['password'] ?? '';

    $xml = simplexml_load_file("user_account.xml") or die("Error: Cannot load XML file");

    $authenticated = false;

    foreach ($xml->admin as $admin) {
        $username = (string)$admin->username;
        $password = (string)$admin->password;
        $name = (string)$admin->name;

        if ($username === $input_username && $password === $input_password) {
            $_SESSION['username'] = $username;
            $_SESSION['name'] = $name;
            $authenticated = true;
            break;
        }
    }

    if ($authenticated) {
        header("Location: home.php");
        exit;
    } else {
        $login_error = "Wrong username and password combination. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TPLeague | Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: url('assets/tpleague_members.png') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        .login-wrapper {
            background-color: rgba(255, 255, 255, 0.96);
            transform: translate(-5vh,20vh);
            padding: 30px 25px;
            width: 100%;
            height: 100%;
            max-width: 360px;
            margin-right: 12%;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .login-wrapper h2 {
            margin: 0 0 10px;
        }

        .login-wrapper p {
            margin: 0 0 20px;
            font-size: 14px;
        }

        .login-wrapper label {
            font-size: 14px;
            margin-top: 10px;
            display: block;
        }

        .login-wrapper input[type="text"],
        .login-wrapper input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .login-wrapper input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #0057d8;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .login-wrapper input[type="submit"]:hover {
            background-color: #0042a8;
        }

        .form-footer {
            font-size: 12px;
            text-align: center;
        }

        .form-footer a {
            color: #0057d8;
            text-decoration: none;
        }

        .error-message {
        position: fixed;
        top: 20px;
        right: -400px;
        background-color: #d9534f;
        color: white;
        padding: 12px 20px;
        border-radius: 6px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        z-index: 9999;
        animation: slide-in 0.5s forwards;
    }

    @keyframes slide-in {
        to {
            right: 20px;
        }
    }

        @media (max-width: 768px) {
            body {
                justify-content: center;
                background-position: center top;
            }

            .login-wrapper {
                margin: 0 20px;
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <form class="login-wrapper" method="post" action="login.php">
        <img src="assets\tpleague_text.png" width="100%;" style="text-align: center;">
        <h2>WELCOME BACK</h2>
        <p>Log In to your Account</p>

        <?php if ($login_error): ?>
    <div class="error-message"><?php echo $login_error; ?></div>
<?php endif; ?>

        <label>Username</label>
        <input type="text" name="username" placeholder="Enter your username" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Enter your password" required>

        <input type="submit" value="LOG IN">

    </form>

    <script>
    window.addEventListener('DOMContentLoaded', () => {
        const errorBox = document.querySelector('.error-message');
        if (errorBox) {
            setTimeout(() => {
                errorBox.style.transition = 'opacity 0.5s ease';
                errorBox.style.opacity = '0';
                setTimeout(() => errorBox.remove(), 500);
            }, 4000);
        }
    });
</script>

</body>
</html>
