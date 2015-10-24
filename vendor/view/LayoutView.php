<?php


class LayoutView {
  
  public function render($isLoggedIn, $isRegisterPage, View $v, DateTimeView $dtv) {
    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>
          <h1>Quiz System</h1>
          ' . $this->renderSwitchPage($isLoggedIn, $isRegisterPage) . $this->renderIsLoggedIn($isLoggedIn) . '
          
          <div class="container">
              ' . $v->response() . '
              
              ' . $dtv->show() . '
          </div>';
    if (!$isLoggedIn) {
      echo '</body>
        </html>
      ';
    }
  }
  
  private function renderIsLoggedIn($isLoggedIn) {
    if ($isLoggedIn) {
      return '<h2>Logged in</h2>';
    }
    else {
      return '<h2>Not logged in</h2>';
    }
  }

  private function renderSwitchPage($isLoggedIn, $registerPage) {
    if (!$isLoggedIn) {
      if ($registerPage) {
        return '<a href="?">Back to login</a>';
      } else {
        return '<a href="?register">Register a new user</a>';
      }
    }
  }

}
