// check if two passwords are the same during sign up
function checkPasswordMatch() {
    var password = $("#psw").val();
    var confirmPassword = $("#psw-repeat").val();
    if (password != confirmPassword){
        $("#divCheckPasswordMatch").html("Passwords do not match!");
        $("#divCheckPasswordMatch").css("color", "red");
        document.getElementById("signupbtn").disabled = true;
    }else{
        $("#divCheckPasswordMatch").html("Passwords match.");
        document.getElementById("signupbtn").disabled = false;
        $("#divCheckPasswordMatch").css("color", "green");
    }
}

//check if the verifcation code is the same
function checkVerficationCodeMatch(){
  var entered = $("#verificationCode").val();
  var code = $("#code").text()
  if (entered != code){
    $("#divCheckVerificationCodeMatch").html("Verification code is not correct");
    $("#divCheckVerificationCodeMatch").css("color", "red");
    document.getElementById("signupbtn").disabled = true;
  }else{
    $("#divCheckVerificationCodeMatch").html("Verification code is correct.");
    document.getElementById("signupbtn").disabled = false;
    $("#divCheckVerificationCodeMatch").css("color", "green");
  }
}

// check email validity during sign up
function checkEmailFormat(){
  var $result = $("#result");
  var email = $("#email").val();
  if (validateEmail(email)) {
    $result.html(email + " is valid :)");
    $result.css("color", "green");
  } else {
    $result.html(email + " is not valid :(");
    $result.css("color", "red");
  }
} 
// helper function for email validity during sign up
function validateEmail(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}

$(document).ready(function () {
   $("#psw, #psw-repeat").keyup(checkPasswordMatch);
   $("#verificationCode").keyup(checkVerficationCodeMatch);
   $("#email").keyup(checkEmailFormat);
});

// email verification, generate random verification code first
function generateVerificationCode(){
  var num = Math.floor(Math.random() * 900000) + 100000;
  $("#code").text(num);
  $("#code").display='none';
  document.getElementById('code').style.display='none'; 
  return num
}

// block user from clicking signing up
function displaySignUp() {
  document.getElementById('id01').style.display='block';
  document.getElementById("signupbtn").disabled = true;
  document.getElementById('id01').style.display='block';
}

// decide which pop up window to appear after signing up
window.onload = function() {
    if(window.location.href.indexOf("message=1") > -1) {
       alert("Make sure your entered a correct password");
    }
    if(window.location.href.indexOf("message=2") > -1) {
       alert("Sign up success, please log in!");
    }
    if(window.location.href.indexOf("message=3") > -1) {
       alert("Sign up failed, user name already exist!");
    }
    if(window.location.href.indexOf("message=4") > -1) {
       alert("Log in failed, user name not exist!");
    }
}

//send email verificateion
function sendEmail(){
  //first disable the button for few seconds to avoid too many clickings
  var fewSeconds = 5;
  var btn = $(".verifybtn");
  btn.css("background-color", "#474e5d");
  setTimeout(function(){
      btn.prop('.disabled', false);
      btn.css("background-color", "#4CAF50");
  }, fewSeconds*1000);

  var email = $("#email").val();
  if (!btn.prop(".disabled")){
    if (validateEmail(email)){
    des = $('#email').val()  //address to send to
    code = generateVerificationCode(); //verification code
    // console.log("test!")
    Email.send("curide.customerservice@gmail.com",
            des,
            "[DO NOT REPLY] CU Ride: Verification code",
            "The code is: " +code +"<br><br> Yours",
            "smtp.gmail.com",
            'curide.service@gmail.com');
    // console.log('Done');
    }else{
      alert("Please make sure your email is valid before verification!");
    }
  }else{
    alert("Please wait for a few seconds for the verfication email to arrive!");
  }

  btn.prop('.disabled', true);
}



