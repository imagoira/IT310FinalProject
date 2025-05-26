<!DOCTYPE html>
<html lang="en">
<head>
    <title>TPLeague</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            height: 100vh;
            background-image: url('assets/bg_landing.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        header {
            background-color: transparent;
            padding: 20px;
            display: flex;
            align-items: center;
        }
        .logo-container {
            display: flex;
            align-items: center;
        }
        .logo {
            width: 70px;
            height: auto;
            margin-right: 10px;
            cursor: pointer;
        }
        .logo-text {
            font-size: 24px;
            font-weight: bold;
        }
        .container {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .get-started-button {
            margin-top: 80px;
            padding: 15px 30px;
            background-color: white; 
            font-weight: bold;
            color: #373643; 
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3); 
        }
        .get-started-button:hover {
            background-color: #fba35d; 
            color: white; 
        }
    </style>
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="./assets/tpl_logo.png" alt="TPLeague Logo" class="logo" onclick="window.location.href='https://www.facebook.com/cict.technoparagonsleague';">
        </div>
    </header>
    <div class="container">
        <button class="get-started-button" onclick="window.location.href='login.php';">Get Started</button>
    </div>
</body>
</html>