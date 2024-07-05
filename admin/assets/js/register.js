function validatePassword() {
    const passwordInput = document.getElementById('register-password');
    const confirmPasswordInput = document.getElementById('register-confirm-password');
    const passwordAlertContainer = document.getElementById('form-password-alert');

    if (passwordInput.value.length < 8) {
      passwordAlertContainer.innerHTML = '<div class="alert alert-danger" role="alert">Error: La contraseña debe tener al menos 8 caracteres.</div>';
      return false;
    }

    if (passwordInput.value !== confirmPasswordInput.value) {
      passwordAlertContainer.innerHTML = '<div class="alert alert-danger" role="alert">Error: Las contraseñas no coinciden. Por favor, verifica las contraseñas e intenta nuevamente.</div>';
      return false;
    }
    
    return true;
}
function togglePasswordVisibility(fieldId) {
    const field = document.getElementById(fieldId);
    if (field.type === "password") {
        field.type = "text";
    } else {
        field.type = "password";
    }
}