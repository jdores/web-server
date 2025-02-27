<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>WebApp</title>
    <style>
        body {
            font-family: 'Rubik', sans-serif;
            text-align: center;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: #f38020; /* Cloudflare Orange */
            color: #fff; /* White Text */
        }

        .message {
            background-color: #fff; /* White Box */
            color: #333; /* Dark Text */
            font-size: 20px;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0px 10px 40px rgba(0, 0, 0, 0.2); /* Shadow */
            transition: box-shadow 0.3s ease-in-out;
        }

        .message:hover {
            box-shadow: 0px 15px 50px rgba(0, 0, 0, 0.3); /* Enhanced Shadow on Hover */
        }

        h2 {
            font-size: 36px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="message">
        <img src="https://www.cloudflare.com/img/logo-cloudflare-dark.svg" alt="Cloudflare Logo" width="150">

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $feedback = htmlspecialchars($_POST["feedback"]);
        file_put_contents("feedback.txt", $feedback . "\n", FILE_APPEND);
        echo "<p>Thank you for your feedback!</p>";
        }
        ?>

    <a href="index.html">Back to Home</a>

    </div>
</body>

</html>