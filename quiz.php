<?php
session_start();
require_once __DIR__ . "/question.php";

// Check whether student details exist
if (
    !isset($_SESSION["student"]) ||
    !isset($_SESSION["quiz_started_at"])
) {
    header("Location: index.php");
    exit;
}

// Calculate remaining quiz time
$duration = 300;
$elapsed = time() - $_SESSION["quiz_started_at"];
$remaining = max(0, $duration - $elapsed);

// If time has expired, redirect to submit page
if ($remaining <= 0) {
    header("Location: submit.php?timeout=1");
    exit;
}

// Ensure exactly 10 questions are available
if (count($questions) !== 10) {
    die("Error: Exactly 10 questions are required.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Quiz Game - Student Challenge</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="quiz-container">

    <div class="quiz-header">
        <div>
            <h1>🎯 Quiz Challenge</h1>
            <p>Think carefully. Every answer counts!</p>
        </div>

        <div class="timer"
             id="timer"
             data-remaining="<?= $remaining ?>">
            05:00
        </div>
    </div>

    <div class="progress-info">
        <span id="progress-text">Question 1 of 10</span>
        <span id="answered-text">0 answered</span>
    </div>

    <div class="progress-track">
        <div class="progress-fill"
             id="progress-fill"
             style="width: 0%;">
        </div>
    </div>

    <form action="submit.php"
          method="POST"
          id="quiz-form">

        <?php foreach ($questions as $index => $question): ?>

            <section class="question-box"
                     data-question="<?= $index ?>"
                     <?= $index === 0 ? '' : 'hidden' ?>>

                <h3>Question <?= $index + 1 ?></h3>

                <p class="question-text">
                    <?= htmlspecialchars($question["question"], ENT_QUOTES, "UTF-8") ?>
                </p>

                <div class="options">

                    <?php foreach ($question["options"] as $optionIndex => $option): ?>

                        <label class="option">

                            <input
                                type="radio"
                                name="answers[<?= $index ?>]"
                                value="<?= $optionIndex ?>"
                            >

                            <span>
                                <?= htmlspecialchars($option, ENT_QUOTES, "UTF-8") ?>
                            </span>

                        </label>

                    <?php endforeach; ?>

                </div>

            </section>

        <?php endforeach; ?>

        <div class="quiz-buttons">

            <button type="button"
                    class="btn secondary"
                    id="previous-btn"
                    disabled>
                ← Previous
            </button>

            <button type="button"
                    class="btn"
                    id="next-btn">
                Next →
            </button>

            <button type="submit"
                    class="btn"
                    id="submit-btn"
                    hidden>
                Submit Quiz
            </button>

        </div>

    </form>

    <p class="small-text">
        Answer all questions before submitting your quiz.
    </p>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("quiz-form");
    const questions = document.querySelectorAll(".question-box");

    const previousBtn = document.getElementById("previous-btn");
    const nextBtn = document.getElementById("next-btn");
    const submitBtn = document.getElementById("submit-btn");

    const progressText = document.getElementById("progress-text");
    const answeredText = document.getElementById("answered-text");
    const progressFill = document.getElementById("progress-fill");

    const timer = document.getElementById("timer");

    let currentQuestion = 0;
    let remaining = Number(timer.dataset.remaining);

    function updateProgress() {

        let answered = 0;

        questions.forEach(function (question) {
            if (question.querySelector("input[type='radio']:checked")) {
                answered++;
            }
        });

        answeredText.textContent = answered + " answered";

        progressFill.style.width =
            (answered / questions.length * 100) + "%";

        progressText.textContent =
            "Question " + (currentQuestion + 1) +
            " of " + questions.length;
    }

    function showQuestion(index) {

        questions.forEach(function (question, i) {
            question.hidden = i !== index;
        });

        currentQuestion = index;

        previousBtn.disabled = index === 0;

        nextBtn.hidden = index === questions.length - 1;
        submitBtn.hidden = index !== questions.length - 1;

        updateProgress();
    }

    previousBtn.addEventListener("click", function () {
        if (currentQuestion > 0) {
            showQuestion(currentQuestion - 1);
        }
    });

    nextBtn.addEventListener("click", function () {
        if (currentQuestion < questions.length - 1) {
            showQuestion(currentQuestion + 1);
        }
    });

    form.addEventListener("change", updateProgress);

    form.addEventListener("submit", function (event) {

        const unanswered = [];

        questions.forEach(function (question, index) {

            if (!question.querySelector("input[type='radio']:checked")) {
                unanswered.push(index + 1);
            }

        });

        if (unanswered.length > 0 && remaining > 0) {

            event.preventDefault();

            alert(
                "Please answer all questions.\n" +
                "Unanswered questions: " + unanswered.join(", ")
            );

            showQuestion(unanswered[0] - 1);
        }

    });

    function updateTimer() {

        const minutes = Math.floor(remaining / 60);
        const seconds = remaining % 60;

        timer.textContent =
            String(minutes).padStart(2, "0") +
            ":" +
            String(seconds).padStart(2, "0");

        if (remaining <= 30) {
            timer.style.color = "#ff3333";
        }

        if (remaining <= 0) {

            clearInterval(timerInterval);

            // Submit automatically when time runs out
            const timeoutInput = document.createElement("input");
            timeoutInput.type = "hidden";
            timeoutInput.name = "timeout";
            timeoutInput.value = "1";

            form.appendChild(timeoutInput);

            form.submit();

            return;
        }

        remaining--;
    }

    showQuestion(0);

    updateTimer();

    const timerInterval = setInterval(updateTimer, 1000);

});
</script>

</body>
</html>