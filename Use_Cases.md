# Requirement Specification

Editor: Ioseff Griffith

# Simple Quiz system component for the web

### Supplementary specification

* The system should respond to input in an acceptable timeframe.
* The system should be user-friendly.
* System provides helpful error messages.
* System avoids unnecessary input.
* The system should follow web standards.

# UC1 View landing page

## Preconditions

A user is authenticated via the log in system.

## Main Scenario
1. Starts when the user has successfully logged in.
2. The system displays a list of recently added quizzes, a link to view more, and a link to create own quizzes.

# UC2 Create new quiz

## Preconditions

A user is authenticated via the log in system.

## Main Scenario
1. Starts when the user clicks the link to create their own quiz.
2. System asks user to input a name for the quiz and select the number of questions the quiz will contain.
3. User provides information.
4. System asks user to input questions and answers for each question.
5. User provides information.
6. System validates information and presents whether the quiz was successfully created or not.

## Alternate Scenarios

4a. Information could not be validated.
* System presents an error message.
* Step 2 in main scenario.
6a. Information could not be validated.
* System presents an error message.
* Step 4 in main scenario.

# UC3 View quiz list

## Preconditions

A user is authenticated via the log in system.

## Main Scenario
1. Starts when the user clicks the link to view all quizzes.
2. A list of quizzes is displayed in order of most recently added.

# UC4 Take quiz

## Preconditions

* A user is authenticated via the log in system.
* The specified quiz was not created by the current user.
* The specified quiz has not already been taken by the current user.

## Main Scenario
1. Starts when the user selects a quiz to take.
2. The user is presented with a list of the questions from the quiz and input fields for the answers.
3. User inputs answers.
4. System validates input and assigns the user a score which is displayed to the user and stored in data.

# UC5 View quiz stats

## Preconditions

* A user is authenticated via the log in system.
* A quiz exists.

## Main Scenario
1. Starts when a user chooses to view stats for a quiz.
2. System displays a list of most recent quiz takers and their scores.

## Alternate Scenarios

2a. User is the creator of the quiz.
* System displays a list of all quiz takers and their scores, plus an average score.

# UC6 View user stats

## Preconditions

* A user is authenticated via the log in system.
* A user exists.

## Main Scenario
1. Starts when a user chooses to view the stats for another user.
2. System displays a list of all quizzes created by the user.

## Alternate Scenarios

2a. User is logged in as the user being viewed.
* System displays a list of all quizzes created by the user, plus a list of all quizzes taken and their scores.