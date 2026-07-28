document.querySelectorAll('.password-toggle').forEach(function (button) {
    button.addEventListener('click', function () {
        const inputId = this.getAttribute('data-target');
        const passwordInput = document.getElementById(inputId);

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            this.textContent = 'Hide';
        } else {
            passwordInput.type = 'password';
            this.textContent = '👁';
        }
    });
});