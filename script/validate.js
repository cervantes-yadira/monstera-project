/**
 * Authors: Yadira Cervantes
 * File: validate.js
 *
 * This file uses jQuery to asynchronously call the validation methods in
 * validate.php and display corresponding form errors.
 */

// loads jQuery
$(document).ready(function() {
    // DOM elements
    let username = $("#input-user-name");
    let email = $("#input-email");
    let password = $("#input-password");
    let passwordConfirm = $("#input-password-confirm");

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
                // error message set to php response
                $("#email-error").text(response);
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

    // uses validPassword() method in php
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
});