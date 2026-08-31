<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Krushi-Manch Marketplace</title>
    <style>
        * {
            box-sizing: border-box;
            padding: 0;
            margin: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #e0f9e1, #f5fff7);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            padding: 60px 10px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
            width: 90%;
            transition: 0.4s ease;
        }

        h1 {
            color: #2e7d32;
            font-size: 36px;
            margin-bottom: 10px;
        }

        p {
            font-size: 18px;
            color: #666;
            margin-bottom: 30px;
        }

        .buttons {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .btn {
            text-decoration: none;
            background: #2e7d32;
            color: #fff;
            padding: 14px;
            font-size: 16px;
            border-radius: 10px;
            font-weight: 600;
            transition: 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn:hover {
            background: #1b5e20;
            transform: scale(1.02);
        }

        @media (min-width: 600px) {
            .buttons {
                flex-direction: row;
                justify-content: center;
            }

            .btn {
                width: 150px;
            }
        }

        /* Home Nav Styles */
        .krushi-nav {
            background: linear-gradient(135deg, #00796B 0%, #00897B 100%);
            padding: 15px 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }
        .krushi-nav-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .krushi-nav-home {
            color: white;
            text-decoration: none;
            font-size: 18px;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 6px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .krushi-nav-home:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateX(2px);
        }
        .krushi-nav-title {
            color: white;
            font-size: 16px;
            font-weight: 500;
        }

        body {
            padding-top: 60px;
        }
    </style>
</head>
<body>
<!-- Home Nav -->


    <div class="container">
        <h1>Welcome to 
            <br> Krushi-Manch Marketplace</h1>
        <p>Where Farmers and Buyers Meet with Trust and Transparency</p>
        <div class="buttons">
            <a href="user/login.php" class="btn">Login</a>
            <a href="user/register.php" class="btn">Register</a>
        </div>
    </div>
</body>
</html>
