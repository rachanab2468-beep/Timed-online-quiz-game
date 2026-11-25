<?php
session_start();
require "question.php";

if (!isset($_SESSION["student"]) ||
    !isset($_SESSION["quiz_started_at"])) {
    header("Location: index.php");
    exit;
}

$elapsed = time() - $_SESSION["quiz_started_at"];

if ($elapsed > 330) {
    $submittedAnswers = [];
} elseif (isset($_POST["timeout"])) {
    $submittedAnswers = [];
} else {
    $submittedAnswers = $_POST["answers"] ?? [];
}

$score = 0;
$studentAnswers = [];

foreach ($questions as $index => $question) {

    $selected = $submittedAnswers[$index] ?? null;

    if (
        is_scalar($selected) &&
        ctype_digit((string)$selected) &&
        (int)$selected >= 0 &&
        (int)$selected < count($question["options"])
    ) {
        $selected = (int)$selected;

        if ($selected === $question["answer"]) {
            $score++;
        }
    } else {
        $selected = null;
    }

    $studentAnswers[] = $selected;
}

$_SESSION["score"] = $score;
$_SESSION["student_answers"] = $studentAnswers;

header("Location: result.php");
exit;
?>