<!DOCTYPE html>
<head>
    <Title>TPLeague | The Developers </Title>
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
        .section {
            padding: 40px 20px;
            text-align: center;
        }

        .intro {
            background: #fff;
            padding: 60px 20px;
            text-align: center;
        }

        .intro h1 {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #ff9800;
        }

        .intro p {
            font-size: 18px;
            color: #666;
            max-width: 600px;
            margin: 0 auto;
        }

        .team-section {
            background-color: #eef8fa;
            padding: 60px 20px;
        }

        .team-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 40px;
        }

        .member-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 300px;
            transition: transform 0.3s;
        }

        .member-card:hover {
            transform: scale(1.03);
        }

        .member-photo {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }

        .member-info {
            padding: 20px;
        }

        .member-info h2 {
            margin: 10px 0;
            font-size: 22px;
        }

        .member-info p {
            font-size: 16px;
            color: #777;
        }

        .highlight {
            color:#3b187a;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .intro h1 {
                font-size: 28px;
            }

            .member-card {
                max-width: 90%;
            }
        }

        @media (max-width: 480px) {
            .intro h1 {
                font-size: 24px;
            }

            .intro p {
                font-size: 16px;
            }

            .member-info h2 {
                font-size: 20px;
            }
        }
    </style>
</head>

<script>
// Dropdown toggle for Settings menu
document.addEventListener('DOMContentLoaded', () => {
    const settingsLink = document.getElementById('settings-link');
    const dropdown = document.getElementById('settings-dropdown');

    settingsLink.addEventListener('click', (event) => {
        event.preventDefault();
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    });

    document.addEventListener('click', (event) => {
        if (!settingsLink.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.style.display = 'none';
        }
    });

    // Call the team data function
    loadTeamDataFromString(teamXmlString);
});
</script>


<body>
    <header>
        <div class="logo-container">
            <img src="./assets/tpl_logo.png" alt="TPLeague Logo" class="logo" onclick="window.location.href='home.php';">
            <div class="logo-text" onclick="window.location.href='home.php';">TPLeague</div>
        </div>
        <nav>
            <a href="home.php">Home</a>
            <a href="#">About</a>
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

        <!-- About Us Section Start -->
        <section class="intro">
            <h1>About <span class="highlight">Us</span></h1>
            <p>Welcome to TPLeague. We are a team of two passionate developers committed to changing how people build and experience digital platforms.</p>
        </section>

        <section class="team-section">
        <div class="team-container" id="team-container">
    <!-- Developer profiles will load here from XML -->
</div>

        </section>
       
        <script>
function loadTeamData() {
    fetch('user_account.xml?' + new Date().getTime())
        .then(response => response.text())
        .then(data => {
            const parser = new DOMParser();
            const xml = parser.parseFromString(data, 'application/xml');
            const admins = xml.getElementsByTagName('admin');

            let output = '';

            for (let i = 0; i < admins.length; i++) {
                const name = admins[i].getElementsByTagName('name')[0].textContent;
                const description = admins[i].getElementsByTagName('description')[0].textContent;
                const profilePicture = admins[i].getElementsByTagName('profilePicture')[0].textContent.replace(/\\/g, '/');

                output += `
                    <div class="member-card">
                        <img src="${profilePicture}" alt="${name}" class="member-photo">
                        <div class="member-info">
                            <h2>${name}</h2>
                            <p>${description}</p>
                        </div>
                    </div>
                `;
            }

            document.getElementById('team-container').innerHTML = output;
        })
        .catch(error => {
            console.error("Error loading XML:", error);
        });
}

document.addEventListener('DOMContentLoaded', loadTeamData);
</script>

</body>
<?php include('footer.php') ?>
</html>