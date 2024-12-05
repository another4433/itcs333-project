// script.js File 

var passwordInput = document.getElementById("password");
var passwordMessageItems = document.getElementsByClassName("password-message-item");
var passwordMessage = document.getElementById("password-message");
let isValidPass;

passwordMessage.style.display = "none";


passwordInput.onfocus = function () {
    passwordMessage.style.display = "block";
}

// After clicking outside of password input hide the message
passwordInput.onblur = function () {
    passwordMessage.style.display = "none";

    isValidPass = true;
    //maybe combine into 1 if statement!
    if (!passwordInput.value.match(/[A-Z]/g)) {isValidPass = false; console.log("1")}
    if (!passwordInput.value.match(/[a-z]/g)) {isValidPass = false; console.log("2")}
    if (!passwordInput.value.match(/[0-9]/g)) {isValidPass = false; console.log("3")}
    if (passwordInput.value.length < 8) {isValidPass = false; console.log("4")}
    if (passwordInput.value.length > 24) {isValidPass = false; console.log("5")}


    if(isValidPass == true) {
        passwordInput.style.border = "2px solid green";
    } else {
        passwordInput.style.border = "2px solid red";
    }
    // console.log("hi", isValidPass, passwordInput.value);
}
//live feedback for the user
passwordInput.onkeyup = function () {
    // checking uppercase letters
    let uppercaseRegex = /[A-Z]/g;
    if (passwordInput.value.match(uppercaseRegex)) {
        passwordMessageItems[1].classList.remove("invalid");
        passwordMessageItems[1].classList.add("valid");
    } else {
        passwordMessageItems[1].classList.remove("valid");
        passwordMessageItems[1].classList.add("invalid");
    }

    // checking lowercase letters
    let lowercaseRegex = /[a-z]/g;
    if (passwordInput.value.match(lowercaseRegex)) {
        passwordMessageItems[0].classList.remove("invalid");
        passwordMessageItems[0].classList.add("valid");
    } else {
        passwordMessageItems[0].classList.remove("valid");
        passwordMessageItems[0].classList.add("invalid");
    }

    // checking the number
    let numbersRegex = /[0-9]/g;
    if (passwordInput.value.match(numbersRegex)) {
        passwordMessageItems[2].classList.remove("invalid");
        passwordMessageItems[2].classList.add("valid");
    } else {
        passwordMessageItems[2].classList.remove("valid");
        passwordMessageItems[2].classList.add("invalid");
    }

    // Checking length of the password
    if (passwordInput.value.length >= 8) {
        passwordMessageItems[3].classList.remove("invalid");
        passwordMessageItems[3].classList.add("valid");
    } else {
        passwordMessageItems[3].classList.remove("valid");
        passwordMessageItems[3].classList.add("invalid");
    }
    if (passwordInput.value.length <= 24) {
        passwordMessageItems[4].classList.remove("invalid");
        passwordMessageItems[4].classList.add("valid");
    } else {
        passwordMessageItems[4].classList.remove("valid");
        passwordMessageItems[4].classList.add("invalid");
    }
}
/**************************************/
/******** Confirm Password ************/
/**************************************/

var confirmPasswordInput = document.getElementById("confirmPassword");
var confirmPasswordMessageItems = document.getElementsByClassName("confirmPassword-message-item");
var confirmPasswordMessage = document.getElementById("confirmPassword-message");
let isValidConfirmPass;
confirmPasswordMessage.style.display = "none";

confirmPasswordInput.onfocus = function () {
    confirmPasswordMessage.style.display = "block";
}

// After clicking outside of password input hide the message
confirmPasswordInput.onblur = function () {
    confirmPasswordMessage.style.display = "none";

    isValidConfirmPass = true;
    if (confirmPasswordInput.value == "") isValidConfirmPass = false;
    if (confirmPasswordInput.value !== passwordInput.value) isValidConfirmPass = false;

    if(isValidConfirmPass == true) {
        confirmPasswordInput.style.border = "2px solid green";
    } else {
        confirmPasswordInput.style.border = "2px solid red";
    }

}

confirmPasswordInput.onkeyup = function () {
    if (confirmPasswordInput.value !== "") {
        confirmPasswordMessageItems[0].classList.remove("invalid");
        confirmPasswordMessageItems[0].classList.add("valid");
        confirmPasswordMessageItems[0].style.display = "none";
    } else {
        confirmPasswordMessageItems[0].classList.remove("valid");
        confirmPasswordMessageItems[0].classList.add("invalid");
        confirmPasswordMessageItems[0].style.display = "block";
    }
    if (confirmPasswordInput.value == passwordInput.value) {
        confirmPasswordMessageItems[1].classList.remove("invalid");
        confirmPasswordMessageItems[1].classList.add("valid");
        confirmPasswordMessageItems[1].style.display = "none";

    } else {
        confirmPasswordMessageItems[1].classList.remove("valid");
        confirmPasswordMessageItems[1].classList.add("invalid");
        confirmPasswordMessageItems[1].style.display = "block";
    }
}



/**************************************/
/************* Email ******************/
/**************************************/

var emailInput = document.getElementById("email");
var emailMessageItems = document.getElementsByClassName("email-message-item");
var emailMessage = document.getElementById("email-message");
let isValidEmail;
emailMessage.style.display = "none";

emailInput.onfocus = function () {
    emailMessage.style.display = "block";
}
emailInput.onblur = function () {
    emailMessage.style.display = "none";

    isValidEmail = true;
    
    if (emailInput.value.length > 0 && emailInput.value.includes("@")) {
        let email = emailInput.value.split("@");
        const userEmailDomain = email[1].toLowerCase();
        if (email[0].length < 8) isValidEmail=false;
        if (userEmailDomain !== "stu.uob.edu.bh" && userEmailDomain !== "uob.edu.bh") isValidEmail=false;
    } else isValidEmail = false;

    if(isValidEmail == true) {
        emailInput.style.border = "2px solid green";
    } else {
        emailInput.style.border = "2px solid red";
    }

}

emailInput.onkeyup = function () {
    if (emailInput.value.length > 0 && emailInput.value.includes("@")) {
        let email = emailInput.value.split("@");
        const userEmailDomain = email[1].toLowerCase();
        
        if (email[0].length >= 8) {
            emailMessageItems[0].classList.remove("invalid");
            emailMessageItems[0].classList.add("valid");
        } else {
            emailMessageItems[0].classList.remove("valid");
            emailMessageItems[0].classList.add("invalid");
        }

        if (userEmailDomain == "stu.uob.edu.bh" || userEmailDomain == "uob.edu.bh") {
            emailMessageItems[1].classList.remove("invalid");
            emailMessageItems[1].classList.add("valid");
        } else {
            emailMessageItems[1].classList.remove("valid");
            emailMessageItems[1].classList.add("invalid");
        }

        emailMessageItems[2].classList.remove("invalid");
        emailMessageItems[2].classList.add("valid");
    } else {
        emailMessageItems[2].classList.remove("valid");
        emailMessageItems[2].classList.add("invalid");
    }
    
}
/**************************************/
/************** Name ******************/
/**************************************/

var fName = document.getElementById("firstName");
var lName = document.getElementById("LastName");

fName.onblur = function () {
    if(fName.value.length > 0) {
        fName.style.border = "2px solid green";
    } else fName.style.border = "2px solid red";
}
lName.onblur = function () {
    if(lName.value.length > 0) {
        lName.style.border = "2px solid green";
    } else lName.style.border = "2px solid red";
}


/**************************************/
/********* Form Submission ************/
/**************************************/
var form = document.getElementById("form");
form.addEventListener('submit', function (e) {//maybe 'register' instead of 'submit'. Test Later
    e.preventDefault();
    if(isValidPass && isValidConfirmPass && isValidEmail) form.submit();
    else console.log("block");
});