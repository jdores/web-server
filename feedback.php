<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webserver</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">Simple web server</div>
            <button class="sign-in" onclick="location.href='index.html'">Back to Home</button>
        </nav>
    </header>

    <main>
        <section class="hero">
            <h1>Feedback Submission</h1>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $feedback = htmlspecialchars($_POST["feedback"]);
                file_put_contents("feedback.txt", $feedback . "\n", FILE_APPEND);
                echo "<p>Thank you for your feedback!</p>";
            } else {
                echo "<p>No feedback submitted.</p>";
            }
            ?>
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
