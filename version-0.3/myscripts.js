// check if two passwords are the same during sign up
function checkPasswordMatch() {
    var password = $("#psw").val();
    var confirmPassword = $("#psw-repeat").val();
    if (password != confirmPassword){
        $("#divCheckPasswordMatch").html("Passwords do not match!");
        document.getElementById("signupbtn").disabled = true;
    }
    else{
        $("#divCheckPasswordMatch").html("Passwords match.");
        document.getElementById("signupbtn").disabled = false;
    }
}

// helper function for email validity during sign up
function validateEmail(email) {
  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  return re.test(email);
}

// check email validity during sign up
function checkEmailFormat(){
  var $result = $("#result");
  var email = $("#email").val();
  if (validateEmail(email)) {
    $result.text(email + " is valid :)");
    $result.css("color", "green");
  } else {
    $result.text(email + " is not valid :(");
    $result.css("color", "red");
  }
} 

$(document).ready(function () {
   $("#psw, #psw-repeat").keyup(checkPasswordMatch);
   $("#email").keyup(checkEmailFormat);
});

// email verification, generate random verification code first
function generateVerificationCode(){
  var num = Math.floor(Math.random() * 900000) + 100000;
  $("#code").text(num)
}

// block user from clicking signing up
function displaySignUp() {
	document.getElementById('id01').style.display='block';
  document.getElementById("signupbtn").disabled = true;
  document.getElementById('id01').style.display='block';
  generateVerificationCode()
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

  // call the python 
  function sendEmail(){ 
    des = $('#email').val()  //address to send to
    code = $('#code').text(); //verification code
    console.log("test!")
    result = runPyScript(des, code);
    console.log('Got back ' + result);
  }

  function runPyScript(des, code){
        var jqXHR = $.ajax({
            type: "POST",
            url: "/sendEmail",
            async: false,
            data: {email: des, code: code }
        });

        if (jqXHR.responseText){
          console.log("successful called sendEmail.py")
        }else{
          console.log("failed")
        }

    }

