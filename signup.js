document.getElementById('signupForm').addEventListener('submit', function(event) {
  event.preventDefault();
  const name = document.getElementById('name').value;
  const email = document.getElementById('email').value;
  const studentId = document.getElementById('studentId').value;
  const password = document.getElementById('password').value;
  const confirmPassword = document.getElementById('confirmPassword').value;

  if (password !== confirmPassword) {
    alert('Passwords do not match!');
    return;
  }

  if (password.length < 6) {
    alert('Password must be at least 6 characters long!');
    return;
  }
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    alert('Please enter a valid email address!');
    return;
  }
  alert('Sign up successful! Welcome to KUET Career Club.');
});