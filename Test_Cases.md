# Test Cases

# Test Case 1.1, Show Landing Page
When the user has logged in, the landing page should be shown.

### Input:
* User logged in

### Output:
* A list of the five most recently added quizzes each linking to the named quiz.
* A link to "View more quizzes".
* A link to "Create new quiz".

# Test Case 2.1, Show quiz creation page

### Input:
* User logged in.
* User clicked "Create new quiz".

### Output:
* The text "Please enter a name for the quiz and the number of questions it will contain." is displayed.
* A form requesting quiz name and number of questions is shown (the basic quiz info form).

# Test Case 2.2, Creation with blank fields should fail

### Input:
* Press submit leaving both fields blank

### Output:
* The text "No quiz name was entered." is shown.
* Shows the basic quiz info form.

# Test Case 2.3, Creation with no quiz name should fail

### Input: 
* Enter "10" in the number of questions field.
* Leave quiz name field blank.
* Submit the form.

### Output:
* The text "No quiz name was entered." is shown.
* Shows the basic quiz info form.

# Test Case 2.4, Creation with no question number should fail

### Input: 
* Enter a unique quiz name in the quiz name field.
* Leave question number field blank.
* Submit the form.

### Output:
* The text "Please enter a number of questions." is shown.
* Shows the basic quiz info form with the quiz name already filled in.

# Test Case 2.5, Creation with existing quiz name should fail

### Input: 
* Enter "10" in the number of questions field.
* Enter the name of an existing quiz in the quiz name field.
* Submit the form.

### Output:
* The text "A quiz with the specified name already exists." is shown.
* Shows the basic quiz info form with blank fields.

# Test Case 2.6, Correct quiz info input

### Input:
* Enter a unique quiz name in the quiz name field.
* Enter "10" in the number of questions field.
* Submit the form.

### Output:
* The text "Please input each question and corresponding answer." is shown.
* A form with a number of question and answer fields equal to the number input in the number of questions field is shown.

# Test Case 2.7, Not all questions filled in

### Input:
* Enter a question and answer in one pair of fields.
* Leave all other fields blank.
* Submit the form.

### Output:
* The text "Please fill in all questions and answers." is shown.
* The question and answer form is shown with all specified questions and answers filled in.

# Test Case 2.8, Quiz successfully created

### Input:
* Enter a question and answer in every pair of fields.
* Submit the form.

### Output:
* The system displays a link to the status page for the newly created quiz.

# Test Case 2.9, Trying to create quiz while not logged in should fail

### Input:
* Navigate to quiz creation page while not logged in.

### Output:
* The user should be redirected automatically to the login page via headers.

# Test Case 3.1, Show quiz list

### Input:
* Log in
* Navigate to quiz list

### Output:
* A list of all quizzes should be displayed in order of creation (newest first) with links to each quiz.

# Test Case 4.1, Attempting to take quiz as user who created it should not be possible

### Input:
* Select a quiz created by the user currently logged in.

### Output:
* The "Take this quiz" button is not available.

# Test Case 4.2, Attempting to take a quiz already taken should fail

### Input:
* Select a quiz already taken by the user currently logged in.

### Output:
* The "Take this quiz" button is not available.

# Test Case 4.3, Attempting to take a quiz while not logged in should fail

### Input:
* Navigate to the quiz page while not logged in.

### Output:
* The user should be redirected automatically to the login page via headers.

# Test Case 4.4, Completing a quiz with blank fields should succeed

### Input:
* Leave fields on the quiz page blank while taking a quiz.
* Submit the form.

### Output:
* The quiz should submit successfully.
* The questions left blank should be registered as being answered incorrectly.

# Test Case 4.5, Successfully completing a quiz

### Input:
* Complete a quiz.

### Output:
* The text "Your score was: " followed by the user's score should be displayed.
* The score should be saved on user stats and quiz stats.

# Test Case 5.1, Show quiz stats when quiz wasn't created by logged in user.

### Input:
* Navigate to the stats page of a quiz not created by the logged in user.

### Output:
* A list of the latest five people who have taken the quiz along with their scores should be displayed.
* Each username should be a link which takes you to their stats page.

# Test Case 5.2, Show quiz stats when quiz was created by logged in user

### Input:
* Navigate to the stats page of a quiz created by the logged in user.

### Output:
* A list of all users who have taken the quiz along with their scores should be displayed in order with the most recent user first.

# Test Case 5.3, Showing quiz stats for non-existent quiz should fail

### Input:
* Navigate to the stats page of a quiz which does not exist.

### Output:
* The user should be redirected to the quiz list via headers.

# Test Case 6.1, Show user stats when user is not currently logged in user

### Input:
* Navigate to the user page of a user that is not the currently active user.

### Output:
* A list of all quizzes created by the selected user should be displayed in order with most recent first.

# Test Case 6.2, Show user stats when user is currently logged in user

### Input:
* Navigate to the user page of the currently active user.

### Output:
* A list of all quizzes created by the user should be displayed in order with most recent first.
* A list of all quizzes taken by the user along with the scores should be displayed in order with most recent first.

# Test Case 6.3, Showing user stats for non-existent user should fail

### Input:
* Navigate to the user page of a non-existent user.

### Output:
* The user should be redirected to the landing page via headers.