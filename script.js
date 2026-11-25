document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("quiz-form");

    // Run this script only on the quiz page
    if (!form) return;

    const questions = Array.from(
        document.querySelectorAll(".question-box")
    );

    const timer = document.getElementById("timer");
    const progressFill = document.getElementById("progress-fill");
    const progressText = document.getElementById("progress-text");
    const answeredText = document.getElementById("answered-text");

    const previousBtn = document.getElementById("previous-btn");
    const nextBtn = document.getElementById("next-btn");
    const submitBtn = document.getElementById("submit-btn");

    let currentQuestion = 0;

    let remaining = Number(timer.dataset.remaining);

    if (!Number.isFinite(remaining) || remaining <= 0) {
        remaining = 0;
    }

    function countAnswered() {
        return questions.filter(question =>
            question.querySelector("input:checked")
        ).length;
    }

    function updateProgress() {

        const answered = countAnswered();

        answeredText.textContent = answered + " answered";

        progressFill.style.width =
            (answered / questions.length * 100) + "%";

        progressText.textContent =
            "Question " + (currentQuestion + 1) +
            " of " + questions.length;
    }

    function showQuestion(index) {

        currentQuestion = index;

        questions.forEach((question, i) => {
            question.hidden = i !== index;
        });

        previousBtn.disabled = index === 0;

        nextBtn.hidden = index === questions.length - 1;
        submitBtn.hidden = index !== questions.length - 1;

        updateProgress();
    }

    nextBtn.addEventListener("click", function () {
        if (currentQuestion < questions.length - 1) {
            showQuestion(currentQuestion + 1);
        }
    });

    previousBtn.addEventListener("click", function () {
        if (currentQuestion > 0) {
            showQuestion(currentQuestion - 1);
        }
    });

    form.addEventListener("change", updateProgress);

    // Countdown timer
    function updateTimer() {

        const minutes = Math.floor(remaining / 60);
        const seconds = remaining % 60;

        timer.textContent =
            String(minutes).padStart(2, "0") + ":" +
            String(seconds).padStart(2, "0");

        if (remaining <= 60) {
            timer.classList.add("warning");
        }

        if (remaining <= 0) {
            form.requestSubmit();
            return;
        }

        remaining--;
    }

    showQuestion(0);
    updateTimer();

    const timerInterval = setInterval(updateTimer, 1000);

    form.addEventListener("submit", function () {
        clearInterval(timerInterval);

        // Prevent double submission
        submitBtn.disabled = true;
        nextBtn.disabled = true;
        previousBtn.disabled = true;
    });

});