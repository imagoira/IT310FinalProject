<?php
// Handle Add addmin form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_admin'])) {
    $xmlFile = 'user_account.xml';

    // Load or create XML
    if (file_exists($xmlFile)) {
        $xml = simplexml_load_file($xmlFile);
    } else {
        $xml = new SimpleXMLElement('<admin></admin>');
    }

    // Determine new uniqueID
    $lastID = 0;
    foreach ($xml->admin as $admin) {
        $lastID = max($lastID, (int)$admin->uniqueID);
    }
    $newID = $lastID + 1;

    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $description = $_POST['description'];

    // Handle image upload
    $profilePicturePath = "assets/default_profile.jpg"; // Default profile picture
    if (isset($_FILES["profilePicture"]) && $_FILES["profilePicture"]["error"] == UPLOAD_ERR_OK) {
        $targetDir = "dp/";
        $fileName = basename($_FILES["profilePicture"]["name"]);
        $targetFile = $targetDir . time() . "_" . $fileName;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowedTypes)) {
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0775, true);
            }
            move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $targetFile);
            $profilePicturePath = $targetFile; // Update to uploaded file path
        } else {
            echo "<script>alert('Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.');</script>";
        }
    }


    // Add new admin
    $newadmin = $xml->addChild('admin');
    $newadmin->addChild('uniqueID', $newID);
    $newadmin->addChild('name', $name);
    $newadmin->addChild('username', $username);
    $newadmin->addChild('password', $password);
    $newadmin->addChild('description', $description);
    $newadmin->addChild('profilePicture', $profilePicturePath);

    $xml->asXML('user_account.xml');

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Delete admin
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_admin'])) {
    $uniqueID = $_POST['uniqueID'];
    $xml = simplexml_load_file('user_account.xml');
    $index = 0;
    foreach ($xml->admin as $admin) {
        if ((string)$admin->uniqueID === $uniqueID) {
            // Get the path of the profile picture to delete it
            $profilePicturePath = (string)$admin->profilePicture;

            // Check if the file exists and delete it
            if (file_exists($profilePicturePath) && $profilePicturePath !== "assets/default_profile.jpg") {
                unlink($profilePicturePath);  // Delete the file
            }

            // Remove the admin record from XML
            unset($xml->admin[$index]);
            break;
        }
        $index++;
    }

    // Save the updated XML file
    $xml->asXML('user_account.xml');

    // Redirect back to the page
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}


// Update admin
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_admin'])) {
    $uniqueID = $_POST['uniqueID'];
    $name = $_POST['name'];
    $username = $_POST['username'];
    $description = $_POST['description'];
    $password = $_POST['password']; 

    $xml = simplexml_load_file('user_account.xml');
    foreach ($xml->admin as $admin) {
        if ((string)$admin->uniqueID === $uniqueID) {
            $admin->name = $name;
            $admin->username = $username;
            $admin->description = $description;
            $admin->password = $password; 

            // Handle the profile picture upload
            if (isset($_FILES["profilePicture"]) && $_FILES["profilePicture"]["error"] == UPLOAD_ERR_OK) {
                $targetDir = "dp/";
                if (!file_exists($targetDir)) mkdir($targetDir, 0775, true);
                $fileName = basename($_FILES["profilePicture"]["name"]);
                $targetFile = $targetDir . time() . "_" . $fileName;
                $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array($imageFileType, $allowedTypes)) {
                    move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $targetFile);
                    $admin->profilePicture = $targetFile;
                }
            }
            break;
        }
    }
    $xml->asXML('user_account.xml');
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>TPLeague | Admin List</title>
    <style>
        body {
            background-color: #f9f9f9;
            font-family: "Arial", sans-serif;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: white;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        h1 {
            text-align: center;
            padding-top: 5px;
            font-size: 50px;
            color: #3b187a;
            font-weight: bold;
        }
        h1::before {
            content: "MANAGE ";
            color: #ff9800;
        }
        p {
            text-align: center;
            font-size: 18px;
            color: #333;
            margin-bottom: 50px;
        }
        .logo-container {
            display: flex;
            align-items: center;
            margin-left: 30px;
        }
        .logo {
            width: 60px;
            height: auto;
            margin-right: 10px;
            cursor: pointer;
        }
        .logo-text {
            font-size: 24px;
            font-weight: bold;
            color: #373643;
            cursor: pointer;
            padding-right: 10px;
        }
        nav {
            display: flex;
            gap: 25px;
            margin-right: 90px;
        }
        nav a {
            text-decoration: none;
            font-weight: bold;
            color: #373643; 
            font-weight: bold;
            cursor: pointer;
            position: relative;
        }
        nav a:hover {
            color: #ff9800;
        }
        table {
            width: 80%;
            border-collapse: collapse;
            margin: 20px auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: middle;
            text-align: center; 
        }
        th {
            background-color: #3b187a;
            color: white;
        }
        .crud-buttons button, .crud-buttons form input[type="submit"] {
            margin: 2px;
            padding: 5px 10px;
            background-color: #3b187a;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .crud-buttons button:hover, .crud-buttons form input[type="submit"]:hover {
            background-color: #ff9800;
        }
        img.profile-pic {
            width: 50px;
            height: 50px;
            object-fit: cover;
            display: block;
            margin: auto;
        }

        /* Pop-up Background Overlay */
        .popup {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%; 
            background: rgba(0, 0, 0, 0.7); /* Black background with transparency */
            z-index: 9999; /* Sit on top */
        }

        /* Pop-up Content */
        .popup-content {
            background: #fff; /* White background */
            margin: 10% auto; /* Center the pop-up */
            padding: 20px; 
            border-radius: 8px; 
            width: 90%; /* Responsive width */
            max-width: 400px; /* Max width for larger screens */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Shadow for depth */
            position: relative; 
        }

        /* Pop-up Header */
        .popup-header {
            font-size: 20px; /* Larger font size */
            font-weight: bold;
            margin-bottom: 15px; /* Space below header */
            color: #3b187a; /* Header color */
            text-align: center; /* Centered text */
        }

        /* Input Fields in Pop-up Forms */
        .popup-content input[type="text"],
        .popup-content input[type="number"],
        .popup-content input[type="file"] {
            width: calc(100% - 20px); /* Full width minus padding */
            padding: 10px; /* Padding inside input */
            border: 1px solid #ddd; /* Light border */
            border-radius: 5px; /* Rounded corners */
            margin-bottom: 15px; /* Space below inputs */
        }

        /* Submit Button in Pop-up Forms */
        .popup-content input[type="submit"] {
            background: #3b187a; /* Button background color */
            color: white; /* Button text color */
            border: none; /* No border */
            padding: 10px; /* Padding inside button */
            border-radius: 5px; /* Rounded corners */
            cursor: pointer; /* Pointer cursor */
            width: 100%; /* Full width */
        }

        /* Submit Button Hover Effect in Pop-up Forms */
        .popup-content input[type="submit"]:hover {
            background: #ff9800; /* Change background on hover */
        }

        /* Close Button */
        .close {
            position: absolute; 
            top: 10px; 
            right: 15px; 
            font-size: 20px; 
            cursor: pointer; 
            color: #3b187a; /* Close button color */
        }

        /*Responsiveness*/
        @media screen and (max-width: 100px) {
        body {
            padding: 10px;
            font-size: 14px;
        }

        nav {
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .logo-container {
            flex-direction: column;
            align-items: center;
            margin: 0;
        }

        .logo-text {
            font-size: 18px;
            text-align: center;
        }

        h1 {
            font-size: 24px;
            text-align: center;
        }

        p {
            font-size: 14px;
            text-align: center;
        }

        .crud-buttons {
            flex-direction: column;
            gap: 8px;
            align-items: stretch;
        }

        .crud-buttons button,
        .crud-buttons form input[type="submit"] {
            font-size: 14px;
            padding: 8px 12px;
        }

        table {
            display: block;
            width: 100%;
            overflow-x: auto;
            font-size: 12px;
        }

        th, td {
            padding: 6px;
        }

        img.profile-pic {
            width: 40px;
            height: 40px;
            object-fit: cover;
        }

        .popup-content {
            width: 95%;
            padding: 15px;
            margin: 15% auto;
            max-width: 400px;
        }

        .popup-content label,
        .popup-content input[type="text"],
        .popup-content input[type="number"],
        .popup-content input[type="file"],
        .popup-content input[type="submit"] {
            font-size: 14px;
            width: 100%;
            padding: 8px;
            margin-bottom: 8px;
        }

        .popup-header {
            font-size: 18px;
            text-align: center;
        }

        .close {
            font-size: 18px;
            top: 5px;
            right: 10px;
        }
    }
    </style>
</head>
<body>
<header>
    <div class="logo-container">
        <img src="./assets/tpl_logo.png" alt="TPLeague Logo" class="logo" onclick="window.location.href='home.php';">
        <div class="logo-text" onclick="window.location.href='home.php';">TPLeague</div>
    </div>
    <nav>
        <a href="home.php">Home</a>
        <a href="about.php">About</a>
        <div id="settings-container" style="position: relative;">
            <a href="#" id="settings-link">Settings</a>
            <div class="dropdown" id="settings-dropdown" style="display:none; position:absolute; background:#fff; box-shadow: 0 2px 8px rgba(0,0,0,.1); margin-top:5px; border-radius:5px; min-width:140px; z-index:1000;">
                <a href="manage_list.php" style="display:block; padding:10px; color:#3b187a; text-decoration:none;">Manage List</a>
                <a href="manage_admin.php" style="display:block; padding:10px; color:#3b187a; text-decoration:none;">Manage Admin</a>
                <a href="login.php" style="display:block; padding:10px; color:#3b187a; text-decoration:none;">Log Out</a>
            </div>
        </div>
    </nav>
</header>

<h1>Admins</h1>
<p>As the Head Admin, you can delete, update, view, and add more system administrators.</p>

<!-- Search Filter -->
<div style="text-align:center; margin-top: 20px;">
    <form method="get" action="manage_admin.php" style="display: inline-block;">
        <input type="text" name="search" placeholder="Search by name or description..."  style="padding:8px; width:300px; border-radius:5px; border:1px solid #ddd;">
        <input type="submit" value="Search" style="padding:8px 12px; background-color:#3b187a; color:white; border:none; border-radius:5px; cursor:pointer;">
        <button type="button" onclick="window.location.href='manage_admin.php'" style="padding:8px 12px; background-color:#555; color:white; border:none; border-radius:5px; cursor:pointer; margin-left:10px;">Refresh</button>
    </form>
</div>



<?php
// Load and display admins
$xml = simplexml_load_file('user_account.xml');
$found = false; 
if ($xml === false) {
    echo "<p>Failed to load admin data.</p>";
} else {
    // Get the search query if it's set
    $searchQuery = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';

    echo '<table>';
    echo '<tr><th>Profile Picture</th><th>Name</th><th>Username</th><th>Password</th><th>Description</th><th>Actions</th></tr>';
    
    foreach ($xml->admin as $admin) {
        // Fetch admin details
        $uniqueID = (string)$admin->uniqueID; 
        $name = htmlspecialchars($admin->name);
        $username = htmlspecialchars($admin->username);
        $password = htmlspecialchars($admin->password);
        $description = htmlspecialchars($admin->description);
        $profilePicture = htmlspecialchars($admin->profilePicture);
    
        // Check if the admin matches the search query (case-insensitive search)
        if (!$searchQuery || (
            strpos(strtolower($name), $searchQuery) !== false ||
            strpos(strtolower($description), $searchQuery) !== false
        )) {
            $found = true; // At least one match was found
            // Display admin data in table row echo '<tr><td colspan = 6>No record found.</td></tr>.';
            echo '<tr>';
            echo "<td><img src=\"$profilePicture\" alt=\"$name\" class=\"profile-pic\"></td>";
            echo "<td>$name</td>";
            echo "<td>$username</td>";
            echo "<td>$password</td>";
            echo "<td>$description</td>";
            echo "<td class=\"crud-buttons\">";
            echo "<button type=\"button\" onclick=\"fillUpdateForm('$uniqueID', '$name', '$username','$password', '$description', '$profilePicture')\">Update</button> ";
            echo "<form style=\"display:inline;\" method=\"post\" onsubmit=\"return confirm('Are you sure you want to delete this admin?');\">";
            echo "<input type=\"hidden\" name=\"uniqueID\" value=\"$uniqueID\" />"; 
            echo "<input type=\"submit\" name=\"delete_admin\" value=\"Delete\" />";
            echo "</form>";
            echo "</td>";
            echo '</tr>';
        }  
    }

    if (!$found) {
    echo '<tr><td colspan="6" style="text-align:center;">No record found.</td></tr>';
    }

    echo '</table>';
    echo '<div style="text-align:center; margin-top:20px;">
            <button onclick="openPopup(\'add-user\')" style="padding:10px 20px; background-color:#3b187a; color:white; border:none; border-radius:5px; cursor:pointer;">Add admin</button>
            <button onclick="window.location.href=\'about.php\'" style="padding:10px 20px; background-color:#3b187a; color:white; border:none; border-radius:5px; cursor:pointer; margin-left:10px;">View Changes</button>
          </div>';
}
?>

<!-- Add admin Pop-up Form -->
<div id="add-user" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closePopup('add-user')">x</span>
        <div class="popup-header">Add New admin</div>
        <form method="post" enctype="multipart/form-data">
            <label>Name:</label>
            <input type="text" name="name" required><br><br>
            <label>Username:</label>
            <input type="text" name="username" required><br><br>
            <label>Password:</label>
            <input type="text" name="password" required><br><br>
            <label>Description:</label>
            <input type="text" name="description" required><br><br>
            <label>Profile Picture:</label>
            <input type="file" name="profilePicture" accept="image/*"><br><br>
            <input type="submit" name="add_admin" value="Save" style="background:#3b187a; color:white; border:none; padding:8px 12px; border-radius:4px; cursor:pointer;">
        </form>
    </div>
</div>

<!-- Update admin -->
<div id="update-user" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closePopup('update-user')">x</span>
        <div class="popup-header">Update admin</div>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="uniqueID" id="update-uniqueID">

            <label>Name:</label>
            <input type="text" name="name" id="update-name" required><br><br>
            
            <label>Username:</label>
            <input type="text" name="username" id="update-username" required><br><br>

            <label>Password:</label>
            <input type="text" name="password" id="update-password"  required><br><br>

            <label>Description:</label>
            <input type="text" name="description" id="update-description" required><br><br>

            <label>New Profile Picture:</label>
            <input type="file" name="profilePicture" id="update-profile"><br><br>
            <input type="submit" name="update_admin" value="Update admin" style="background:#3b187a; color:white; border:none; padding:8px 12px; border-radius:4px; cursor:pointer;">
        </form>
    </div>
</div>



<script>
    // Dropdown toggle
    document.addEventListener('DOMContentLoaded', () => {
        const settingsLink = document.getElementById('settings-link');
        const dropdown = document.getElementById('settings-dropdown');
        settingsLink.addEventListener('click', (e) => {
            e.preventDefault();
            dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
        });
        document.addEventListener('click', (e) => {
            if (!settingsLink.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });
    });

    function openPopup(id) {
        document.getElementById(id).style.display = 'block';
    }

    function closePopup(id) {
        document.getElementById(id).style.display = 'none';
    }

    function fillUpdateForm(uniqueID, name, username, password, description, profilePicture) {
    document.getElementById('update-uniqueID').value = uniqueID;
    document.getElementById('update-name').value = name;
    document.getElementById('update-username').value = username;
    document.getElementById('update-password').value = password;
    document.getElementById('update-description').value = description;


    let profilePicElement = document.getElementById('current-profile-picture');
    if (profilePicElement) {
        profilePicElement.src = profilePicture; 
        profilePicElement.style.display = 'block'; 
    }

    document.getElementById('update-user').style.display = 'block';  
}

</script>

</body>
</html>
