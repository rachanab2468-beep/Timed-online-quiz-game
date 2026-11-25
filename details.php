<?php
session_start();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $class = trim($_POST["student_class"] ?? "");
    $roll = trim($_POST["roll"] ?? "");
    $email = trim($_POST["email"] ?? "");

    if (strlen($name) < 2) {
        $error = "Please enter a valid name.";
    } elseif ($class === "") {
        $error = "Please select your class.";
    } elseif ($roll === "" || !preg_match("/^[A-Za-z0-9-]+$/", $roll)) {
        $error = "Please enter a valid roll number.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {

        $_SESSION["student"] = [
            "name" => $name,
            "class" => $class,
            "roll" => $roll,
            "email" => $email
        ];

        unset($_SESSION["score"], $_SESSION["student_answers"]);

        $_SESSION["quiz_started_at"] = time();

        header("Location: quiz.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-card">

    <h1>Student Details</h1>
    <p class="subtitle">Please enter your details to continue.</p>

    <?php if ($error !== ""): ?>
        <div class="error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="details.php">

        <label>Full Name</label>
        <input
            type="text"
            name="name"
            placeholder="Enter your full name"
            value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
            minlength="2"
            maxlength="60"
            required
        >

        <label>Class / Year</label>
        <select name="student_class" required>
            <option value="">Select your class</option>
            <option>School Student</option>
            <option>1st Year</option>
            <option>2nd Year</option>
            <option>3rd Year</option>
            <option>4th Year</option>
            <option>Other</option>
        </select>

        <label>Roll Number</label>
        <input
            type="text"
            name="roll"
            placeholder="Enter your roll number"
            maxlength="20"
            required
        >

        <label>Email Address</label>
        <input
            type="email"
            name="email"
            placeholder="Enter your email address"
            maxlength="100"
            required
        >

        <button type="submit" class="btn">
            Start the Quiz →
        </button>

    </form>

</div>

</body>
</html>