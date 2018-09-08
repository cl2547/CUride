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

$(document).ready(function () {
   $("#psw, #psw-repeat").keyup(checkPasswordMatch);
});

function displaySignUp() {
	document.getElementById('id01').style.display='block'
    document.getElementById("signupbtn").disabled = true;
}

window.onload = function() {
    if(window.location.href.indexOf("message=1") > -1) {
       alert("make sure your entered a correct password");
    }
}