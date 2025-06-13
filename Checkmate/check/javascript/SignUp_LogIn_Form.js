document.addEventListener('DOMContentLoaded', () => {
  const container = document.querySelector('.container');
  const registerBtn = document.querySelector('.register-btn');
  const loginBtn = document.querySelector('.login-btn');

  registerBtn.addEventListener('click', () => {
    container.classList.add('active-register');
    // Clear hash if needed
    history.replaceState(null, '', ' ');
  });

  loginBtn.addEventListener('click', () => {
    container.classList.remove('active-register');
    history.replaceState(null, '', ' ');
  });
});
