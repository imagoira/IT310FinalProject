<!DOCTYPE html>
<head>
    <Title>TPLeague | Home </Title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f9f9f9;
            font-family: "Arial", sans-serif;
            margin: 0;
            padding: 0;
        }
        header {
            background-color:white;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
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
            font-size: 16px;
            font-weight: bold;
            color: #373643; 
            font-weight: bold;
            cursor: pointer;
            position: relative; 
        }
        nav a:hover {
            color: #ff9800;
        }
        .dropdown {
            display: none; 
            position: absolute;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            margin-top: 5px; 
            border-radius: 5px;
            min-width: 140px;
        }
        nav a:hover .dropdown {
            display: block;
        }
        .dropdown a {
            display: block;
            padding: 10px;
            color: #3b187a;
            text-decoration: none;
        }
        .dropdown a:hover {
            background-color: #f0f0f0; 
        }
        h1 {
            text-align: center;
            padding-top: 25px;
            font-size: 50px;
            color: #3b187a;
            font-weight: bold;
        }
        h1::before {
            content: "TPL ";
            color: #ff9800;
        }
        p {
            text-align: center;
            font-size: 18px;
            color: #333;
            margin-bottom: 50px;
        }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            padding: 20px;
            max-width: 1200px; 
            margin: auto;
            justify-content: center;
            align-items: start;
        }

        .officers {
            background: #ffffff;
            padding: 20px;
            text-align: center;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            min-height: 350px; 
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .officers:hover {
            transform: scale(1.03);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .officers img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #ffcc00;
            margin-bottom: 10px;
            margin-top: 10px;

            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .officers button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #3b187a;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 15px;
            transition: background 0.3s ease-in-out;
        }
        .officers button:hover {
            background-color: #ff9800;
        }

        .carousel-inner > .item > img {
            width: 100%;
            height: 600px;
            object-fit: cover;
        }  
        
        @media (max-width: 600px) {
            .grid-container {
                grid-template-columns: repeat(2, 1fr);
            }
            .grid-container {
                grid-template-columns: 1fr;
            }
        }

    </style>
    
</head>


<script>
function getData() {
    fetch('tpl_officer.xml?' + new Date().getTime())
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(str => {
            const parser = new DOMParser();
            const xml = parser.parseFromString(str, "application/xml");
            const officers = xml.getElementsByTagName("officer");
            let output = "<div class='grid-container'>";

            for (let k = 0; k < officers.length; k++) {
                const officer = officers[k].childNodes;
                output += "<div class='officers'>";

                // Display the profile picture first
                for (let i = 0; i < officer.length; i++) {
                    const detail = officer[i];
                    if (detail.nodeType === 1) {
                        if (detail.nodeName.toLowerCase() === "profilepicture") {
                            let imgSrc = detail.textContent.trim() || './assets/default_profile.jpg';
                            output += "<img src='" + imgSrc + "' ><br/>";
                        }
                    }
                }

                // Display only the name and position
                let name = "";
                let position = "";
                for (let i = 0; i < officer.length; i++) {
                    const detail = officer[i];
                    if (detail.nodeType === 1) {
                        if (detail.nodeName.toLowerCase() === "name") {
                            name = detail.textContent; 
                        } else if (detail.nodeName.toLowerCase() === "position") {
                            position = detail.textContent; 
                        }
                    }
                }

                output += "<div style='font-size: 20px; font-weight: bold;'>" + name + "</div>"; 
                output += "<div style='font-size: 16px; color: #666; margin-top: 4px;'>" + position + "</div>"; 

                output += "<button onclick=\"window.location.href='manage_list.php'\">View More</button>";
                output += "</div>";
            }
            output += "</div>";
            document.getElementById("officerData").innerHTML = output;
        })
        .catch(error => {
            console.error("Failed to load XML file:", error);
            document.getElementById("officerData").innerHTML = "<p>Failed to load officer data.</p>";
        });
}

// Dropdown toggle for Settings menu
document.addEventListener('DOMContentLoaded', () => {
    const settingsLink = document.getElementById('settings-link');
    const dropdown = document.getElementById('settings-dropdown');

    settingsLink.addEventListener('click', (event) => {
        event.preventDefault();
        // Toggle display
        if (dropdown.style.display === 'block') {
            dropdown.style.display = 'none';
        } else {
            dropdown.style.display = 'block';
        }
    });

    // Close dropdown if click outside
    document.addEventListener('click', (event) => {
        if (!settingsLink.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.style.display = 'none';
        }
    });
});


</script>

<body onload="getData();">
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
                <div class="dropdown" id="settings-dropdown">
                    <a href="manage_list.php">Manage List</a>
                    <a href="manage_admin.php">Manage Admin</a>
                    <a href="login.php">Log Out</a>
                </div>
            </div>
        </nav>
    </header>

    <div class="container" style="width: 100%; margin-top: 10px; margin-bottom: 50px;">
        <!--Add Carousel of TPL Events-->
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner">
            <div class="item active">
            <img src="./assets/event1.jpg" alt="Event 1" style="width:100%;">
            </div>

            <div class="item">
            <img src="./assets/event2.jpg" alt="Event 2" style="width:100%;">
            </div>

            <div class="item">
            <img src="./assets/event3.jpg" alt="Event 3" style="width:100%;">
            </div>
        </div>

        <!-- Controls -->
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
            <span class="sr-only">Next</span>
        </a>
        </div>
    </div>

    <h1>Executive Officers</h1>
    <p>The TPL Executive Officers inspires an environment of collaboration, passion, and dedication to the organization's purpose. Together, they shine the light as thought leaders in organization.</p>
    
    <div id="officerData"></div> 

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</body>

<?php include('footer.php') ?>
</html>