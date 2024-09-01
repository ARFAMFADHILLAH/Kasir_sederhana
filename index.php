<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DLH.STORE</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: black;
        }
        .container {
            text-align: center;
            background-color: rgba(255, 255, 255, 0.1);
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }
        h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
        }
        p {
            font-size: 1.2em;
            margin-bottom: 30px;
        }
        .buttons {
            display: flex;
            justify-content: center; /* Center the buttons */
        }
        .buttons a {
            text-decoration: none;
            background-color: #0984e3;
            color: white;
            padding: 15px 40px;
            border-radius: 10px;
            font-size: 1.1em;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .buttons a:hover {
            background-color: #74b9ff;
            transform: scale(1.1); /* Zoom effect */
        }
        .footer {
            margin-top: 30px;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Selamat Datang di DLH.STORE</h1>
        <div class="buttons">
            <a href="login.php">Login</a>
        </div>
        <div class="footer">
            <p>&copy; 2024 <strong>ArfaMuhammadFadhillah</strong>. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
