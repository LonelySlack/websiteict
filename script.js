document.addEventListener('DOMContentLoaded', () => {
    const loginBtn = document.getElementById('login-btn');
    const signupBtn = document.getElementById('signup-btn');
    const loginForm = document.getElementById('login-form');
    const signupForm = document.getElementById('signup-form');
    const forms = document.querySelectorAll('.form');

    function showForm(formToShow) {
        forms.forEach(form => {
            form.classList.remove('active');
        });
        formToShow.classList.add('active');
    }

    loginBtn.addEventListener('click', () => {
        showForm(loginForm);
        loginBtn.classList.add('active');
        signupBtn.classList.remove('active');
    });

    signupBtn.addEventListener('click', () => {
        showForm(signupForm);
        signupBtn.classList.add('active');
        loginBtn.classList.remove('active');
    });

    
    // Show login form by default
    showForm(loginForm);
});



