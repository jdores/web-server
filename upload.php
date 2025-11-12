<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webserver</title>
    <link rel="stylesheet" href="style.css"> </head>

<body>
    <header>
        <nav>
            <div class="logo">CFD / GCP / 10.156.15.233</div>
            <button class="sign-in" onclick="location.href='index.html'">Back</button>
        </nav>
    </header>

    <main>
        <section class="hero">
            <h1>File Upload Status</h1>
            <p>
                <?php
                    // Define the upload directory
                    $uploadDir = "uploads/";

                    // Ensure the upload directory exists
                    if (!file_exists($uploadDir)) {
                        // Attempt to create the directory with appropriate permissions
                        if (!mkdir($uploadDir, 0755, true)) {
                            // Log error and display a user-friendly message
                            error_log("Failed to create upload directory: " . $uploadDir);
                            die("Server error: Upload directory could not be created. Please contact support.");
                        }
                    }

                    // Check if the form was submitted
                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
                        $fileName = basename($_FILES["file"]["name"]);
                        $targetFile = $uploadDir . $fileName;

                        // Validate file size (max 5 MB)
                        if ($_FILES["file"]["size"] > 5 * 1024 * 1024) {
                            die("File is too large. Max: 5 MB.");
                        }

                        // Allow only specific file types (e.g., PDF, PNG, JPG)
                        $allowedTypes = ['pdf', 'png', 'jpg', 'jpeg', 'txt'];
                        $fileExtension = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                        if (!in_array($fileExtension, $allowedTypes)) {
                            die("Invalid file type. Allowed types: pdf, png, jpg, jpeg, txt.");
                        }

                        // Move uploaded file to the target directory
                        // Ensure the web server user has write permissions to the 'uploads/' directory
                        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
                            echo "File uploaded successfully: <a href='$targetFile'>$fileName</a>";
                        } else {
                            // Log potential errors for debugging (e.g., permissions, disk space)
                            error_log("Error moving uploaded file. Details: " . print_r($_FILES["file"], true));
                            echo "Error uploading the file. Please check server logs for more details.";
                        }
                    } else {
                        echo "No file uploaded or invalid request.";
                    }
                ?>
            </p>
            <a href="index.html" class="cta-button">Back to Home</a>
        </section>
    </main>

    <footer>
        <p>&copy; <span id="year"></span> Webserver powered by Cloudflare One. <a href="admin.html">Admin</a></p>
    </footer>

    <script>
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>
</body>

</html>
