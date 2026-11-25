<?php
session_start();
session_unset();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Challenge</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="welcome-card">
    <div class="logo">QC</div>

    <h1>Welcome to Quiz Challenge!</h1>

    <p>
        Challenge your mind, test your knowledge
        and discover your score.
    </p>

    <div class="features">
        <span>10 Questions</span>
        <span>5 Minutes</span>
        <span>Instant Results</span>
    </div>

    <a href="details.php" class="btn">
        Start Quiz →
    </a>

    <p class="small-text">
        Read each question carefully. Good luck!
    </p>
</div>

</body>
</html>