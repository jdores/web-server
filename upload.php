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
            // Define the upload directory
            $uploadDir = "uploads/";

            // Ensure the upload directory exists
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Check if the form was submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
                $fileName = basename($_FILES["file"]["name"]);
                $targetFile = $uploadDir . $fileName;

                // Validate file size (max 5 MB)
                if ($_FILES["file"]["size"] > 5 * 1024 * 1024) {
                    die("<br>File is too large. Max: 5 MB.<br><br>");
                }

                // Allow only specific file types (e.g., PDF, PNG, JPG)
                $allowedTypes = ['pdf', 'png', 'jpg', 'jpeg', 'txt'];
                $fileExtension = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                if (!in_array($fileExtension, $allowedTypes)) {
                    die("<br>Invalid file type. Allowed types: pdf, png, jpg, jpeg, txt.<br><br>");
                }

                // Move uploaded file to the target directory
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
                    echo "<br>File uploaded successfully: <a href='$targetFile'>$fileName</a><br><br>";
                } else {
                    echo "<br>Error uploading the file.<br><br>";
                }
            } else {
                echo "<br>No file uploaded.<br><br>";
            }
            ?>

    <a href="index.html">Back to Home</a>

    </div>
</body>

</html>