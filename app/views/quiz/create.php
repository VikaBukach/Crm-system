<?php

$title = 'Create quiz';
ob_start();

?>
<h1 class="mb-4">Create quiz</h1>
<form method="POST" action="/quiz/store">
    <div class="form-group mb-3">
        <label for="question">Question</label>
        <input type="text" class="form-control" id="question" name="question" required>
            <div class="question-suggestions"></div>
    </div>
    <div class="form-group mb-3">
        <label for="answer_1">Answer 1:</label>
        <input type="text" class="form-control" id="answer_1" name="answer_1" required>
    </div>
    <div class="form-group mb-3">
        <label for="answer_2">Answer 2:</label>
        <input type="text" class="form-control" id="answer_2" name="answer_2" required>
    </div>
    <div class="form-group mb-3">
        <label for="answer_3">Answer 3:</label>
        <input type="text" class="form-control" id="answer_3" name="answer_3" required>
    </div>

    <div class="form-group mb-3">
        <label for="correct_answer" class="form-label">Correct answer:</label>
        <select class="form-select" id="correct_answer" name="correct_answer" required>
            <option value="0">Answer 1</option>
            <option value="1">Answer 2</option>
            <option value="2">Answer 3</option>
        </select>
    </div>
    <div class="form-group mb-3">
        <label for="explanation" class="form-label">Explanation of the answer:</label>
        <textarea class="form-control" name="explanation" id="explanation" rows="3"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>

<script>
    const questionInput = document.getElementById('question');
    const suggestionsContainer = document.querySelector('.question-suggestions');

    questionInput.addEventListener('input', (event) => {
        const inputValue = event.target.value;
        if(inputValue.length < 3){
            suggestionsContainer.innerHTML = '';
            suggestionsContainer.style.display = 'none';
            return;
        }

        fetch(`/quiz/search`, {
            method: 'POST',
            body: JSON.stringify({question: inputValue}),
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                //clean container
                suggestionsContainer.innerHTML = '';
                //if there is a match we display them in container
                if (data.length > 0) {
                    const ul = document.createElement('ul');
                    data.forEach(item => {
                        const li = document.createElement('li');
                        li.textContent = item.question;
                        li.addEventListener('click', () => {
                            questionInput.value = item.question;
                            suggestionsContainer.style.display = 'none';
                        });
                        ul.appendChild(li);
                    });
                    suggestionsContainer.appendChild(ul);
                    suggestionsContainer.style.display = 'block';
                }else {
                    suggestionsContainer.style.display = 'none';
                }
            })
            .catch(error => console.error(error));
    });
</script>


<?php $content = ob_get_clean();

include 'app/views/layout.php';
?>



