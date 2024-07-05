function togglePasswordVisibility(fieldId) {
    const field = document.getElementById(fieldId);
    if (field.type === "password") {
        field.type = "text";
    } else {
        field.type = "password";
    }
}

function validatePasswords(event) {
    const password = document.getElementById("register-password").value;
    const confirmPassword = document.getElementById("register-confirm-password").value;

    if (password !== confirmPassword) {
        $('#passwordMismatchModal').modal('show');
        event.preventDefault(); // Evitar que el formulario se envíe
        return false;
    }

    return true;
}


document.getElementById("register-password").addEventListener("input", function() {
    const confirmPasswordField = document.getElementById("register-confirm-password");
    confirmPasswordField.required = this.value.length > 0;
});

document.getElementById("register-confirm-password").addEventListener("input", function() {
    const passwordField = document.getElementById("register-password");
    const errorMessage = document.getElementById("password-error");

    if (this.value !== passwordField.value) {
        errorMessage.style.display = "block";
    } else {
        errorMessage.style.display = "none";
    }
});