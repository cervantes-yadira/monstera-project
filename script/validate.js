/**
 * This file uses jQuery to asynchronously call the validation methods in
 * validate.php and display corresponding form errors.
 *
 * @author Yadira Cervantes
 * @version 1.0
 */

// loads jQuery
$(document).ready(function() {
    // DOM elements
    let username = $("#input-user-name");
    let email = $("#input-email");
    let password = $("#input-password");
    let passwordConfirm = $("#input-password-confirm");
    let message = $("#input-message");
    let name = $("#input-name");

    // executes when mouse clicks out of username input
    username.focusout(function () {
        $.post("models/validateAsync.php",
            {
                // post array values
                username: username.val(),
            },
            function(response){
                // error message set to php response
                $("#name-error").text(response);

            },
            "text");
    })

    // executes when mouse clicks out of email input
    email.focusout(function () {
        $.post("models/validateAsync.php",
            {
                // post array values
                email: email.val(),
            },
            function(response){
                // check if error message is already displayed
                if(isEmpty($("#errors-email"))) {
                    // error message set to php response
                    $("#email-error").text(response);
                }
            },
            "text");
    })

    // executes when mouse clicks out of password input
    password.focusout(function () {
        validatePassword();
    })

    // executes when mouse clicks out of confirmation password input
    passwordConfirm.focusout(function () {
        validatePassword();
    })

    // executes when mouse clicks out of message input
    message.focusout(function () {
        $.post("models/validateAsync.php",
            {
                // post array values
                message: message.val(),
            },
            function(response){
                // check if error message is already displayed
                if(isEmpty($("#errors-message"))) {
                    // error message set to php response
                    $("#message-error").text(response);
                }
            },
            "text");
    })

    // executes when mouse clicks out of name input
    name.focusout(function () {
        $.post("models/validateAsync.php",
            {
                // post array values
                name: name.val(),
            },
            function(response){
                // check if error message is already displayed
                if(isEmpty($("#errors-name"))) {
                    // error message set to php response
                    $("#name-error").text(response);
                }
            },
            "text");
    })


    /**
     * Checks if a password is valid using php validation functions.
     */
    function validatePassword() {
        $.post("models/validateAsync.php",
            {
                // post array values
                password: password.val(),
                passwordConfirm: passwordConfirm.val(),
            },
            function(response){
                // error message set to php response
                $("#password-error").text(response);
            },
            "text");
    }

    /**
     * Checks if an object is empty.
     *
     * @param object
     * @return {boolean}
     */
    function isEmpty(object) {
        return object.length === 0;
    }
});