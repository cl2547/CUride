<?php 

include_once "config.php";

$toReturn = '';
$toReturn .= '<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">

    <script src="jquery-3.3.1.min.js"></script>
    <script src="myscripts.js"></script>
    <script src="https://smtpjs.com/v2/smtp.js">
</head>
<body>

<h2>CURide Login Form</h2>

<form action="    ' . $actionface . '      " method="POST">
  <div class="imgcontainer">
    <img src="img_avatar.png" alt="Avatar" class="avatar">
  </div>

  <div class="container">
      
      <!--dummy -->
      <input type="text" style="display: none" name="fromtype" value="login">
      
      
    <label for="uname"><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="uname" required>

    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="psw" required>


    <div>
        <button id="login" type="submit">Login</button>
        <button id = "signup" onclick="displaySignUp()" >Sign Up</button>
    </div>
    
  </div>

</form>';


$toReturn .= '
<!-- for sign up -->
<div id="id01" class="modal">
  <span onclick="document.getElementById(\'id01\').style.display=\'none\'" class="close" title="Close Modal">&times;</span>
  <form class="modal-content" action="    ' . $actionface . '    

" method="POST">
          <!--dummy -->
      <input type="text" style="display: none" name="fromtype" value="signup">
    
    <div class="container">
      <h1>Sign Up</h1>
      <p>Please fill in this form to create an account.</p>
      <hr>
      <label for="name"><b>Name</b></label> 
      <input type="text" placeholder="Enter Your Name" id = "name" name="name" required>

      <label for="email"><b>Email</b></label>
      <input type="text" placeholder="Enter Email" id = "email" name="email" required>
      <p id="result"></p>

      <button type="button" class="verifybtn" onclick="sendEmail()">Verify (Please check your email and enter the verfication code. You need to verify your email to continue.)</button>
      <label for="Verification code"><b>Verifcation Code</b></label><p display="none" id = "code"></p>
      <input type="text" placeholder="Enter Verificatoin Code" id = "verificationCode" name="verificationCode" required>
      
      <label for="psw"><b>Password</b></label>
      <input type="password" placeholder="Enter Password" id="psw" name="psw" required>

      <label for="psw-repeat"><b>Repeat Password</b></label>
      <input type="password" placeholder="Repeat Password" id="psw-repeat" name="psw-repeat" required>
      <div class="registrationFormAlert" id="divCheckPasswordMatch"></div>
      <p>By creating an account you agree to our <a href="#" style="color:dodgerblue">Terms & Privacy</a>.</p>

      <div class="clearfix">
        <button type="button" onclick="document.getElementById(\'id01\').style.display=\'none\'" class="cancelbtn">Cancel</button>
        <button type="submit" class="signupbtn" id="signupbtn">Sign Up</button>
      </div>
    </div>
  </form>
</div>

</body>
</html>  ';


echo $toReturn;

 ?>
