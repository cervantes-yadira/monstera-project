
const userName = document.getElementById("input-user-name");
const email = document.getElementById("input-email");
const password = document.getElementById("input-password");
const passwordConfirm = document.getElementById("input-password-confirm");


// validation

function validateForm() {
    let isUserNameValid = validateUserName();
    let isEmailValid = validateEmail();
    let isPasswordValid =  validatePassword();

    let isValid = isUserNameValid && isEmailValid && isPasswordValid;

    if(isValid === false) {

        window.scroll({
            top: 70,
            left: 100,
            behavior: "smooth",
        });
    }

    return isValid;
}

function validateUserName() {
    let isUserValid = userName.value.length !== 0;

    const nameError = document.getElementById("name-error");
    const nameMessage = "Please enter user name";

    if (isUserValid === true) {
        nameError.style.visibility = "hidden";
        userName.classList.remove("form-input-error");
    } else {
        if (isUserValid === false) {
            nameError.innerText = nameMessage;
            userName.classList.add("form-input-error");
        }
        nameError.style.visibility = "visible";
    }
    return isUserValid;
}

function validateEmail() {
    let re = /[^\s@]+@[^\s@]+\.[^\s@]+/;
    let isValid = re.test(email.value);

    const emailError = document.getElementById("email-error");

    if(isValid === true) {
        emailError.style.visibility = "hidden";
        email.setAttribute("class", "form-control");
    } else {
        emailError.style.visibility = "visible";
        email.setAttribute("class", "form-control form-input-error");
    }

    return isValid;
}

function validatePassword() {
    let re = /^(?=.*\d)(?=.*[a-zA-Z])[a-zA-Z\d!@#$%&*_\-.]{8,16}$/;
    let isValid = re.test(password.value) && (password.value === passwordConfirm.value);
    const passwordError = document.getElementById("password-error");

    if(isValid === false) {
        passwordError.style.visibility = "visible";
        password.classList.add('form-input-error');
        passwordConfirm.classList.add('form-input-error');
    } else {
        passwordError.style.visibility = "hidden";
        password.classList.remove('form-input-error');
        passwordConfirm.classList.remove('form-input-error');
    }

    return isValid;
}

