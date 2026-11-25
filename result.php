<?php
session_start();

if (!isset($_SESSION["student"]) ||
    !isset($_SESSION["score"])) {
    header("Location: index.php");
    exit;
}

$student = $_SESSION["student"];
$score = $_SESSION["score"];
$percentage = $score * 10;

if ($score >= 8) {
    $message = "Excellent performance! Great work!";
} elseif ($score >= 5) {
    $message = "Good effort! Keep practising!";
} else {
    $message = "Keep learning. You can improve!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Result</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="result-card">

    <div class="result-icon">✓</div>

    <h1>Quiz Completed!</h1>

    <p class="subtitle">
        Here is your performance report.
    </p>

    <div class="student-info">
        <p><strong>Name:</strong>
            <?= htmlspecialchars($student["name"]) ?>
        </p>

        <p><strong>Class:</strong>
            <?= htmlspecialchars($student["class"]) ?>
        </p>

        <p><strong>Roll Number:</strong>
            <?= htmlspecialchars($student["roll"]) ?>
        </p>
    </div>

    <div class="score-circle">
        <strong><?= $score ?>/10</strong>
        <span>Your Score</span>
    </div>

    <h2><?= $percentage ?>%</h2>

    <p class="message">
        <?= htmlspecialchars($message) ?>
    </p>

    <a href="index.php" class="btn">
        Play Again
    </a>

</div>

</body>
</html>