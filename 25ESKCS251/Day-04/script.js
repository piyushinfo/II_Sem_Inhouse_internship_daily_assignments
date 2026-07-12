// ===== DAY 4 - JAVASCRIPT FUNDAMENTALS =====

// ---- 1. SMOOTH SCROLLING ----
const links = document.querySelectorAll('a[href^="#"]');

links.forEach(function(link) {
  link.addEventListener('click', function(e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      target.scrollIntoView({ behavior: 'smooth' });
    }
  });
});


// ---- 2. DARK MODE TOGGLE ----
function toggleDarkMode() {
  const body = document.getElementById('mainBody');
  const btn = document.getElementById('darkModeBtn');

  body.classList.toggle('dark');

  // Change button label based on mode
  if (body.classList.contains('dark')) {
    btn.textContent = '☀️ Light Mode';
  } else {
    btn.textContent = '🌙 Dark Mode';
  }
}


// ---- 3. CLICK COUNTER ----
let clickCount = 0;

function countClick() {
  clickCount++;
  document.getElementById('clickDisplay').textContent = 'Clicks: ' + clickCount;
}

function resetCount() {
  clickCount = 0;
  document.getElementById('clickDisplay').textContent = 'Clicks: 0';
}


// ---- 4. FORM VALIDATION ----
function validateForm() {

  // Get values from inputs
  const name = document.getElementById('nameInput').value;
  const email = document.getElementById('emailInput').value;

  // Get error and success message elements
  const nameError = document.getElementById('nameError');
  const emailError = document.getElementById('emailError');
  const successMsg = document.getElementById('successMsg');

  // Clear old messages first
  nameError.textContent = '';
  emailError.textContent = '';
  successMsg.textContent = '';

  // Track if form is valid
  let isValid = true;

  // Check name is not empty
  if (name === '') {
    nameError.textContent = '⚠️ Please enter your name.';
    isValid = false;
  }

  // Check email contains @
  if (email === '') {
    emailError.textContent = '⚠️ Please enter your email.';
    isValid = false;
  } else if (!email.includes('@')) {
    emailError.textContent = '⚠️ Please enter a valid email address.';
    isValid = false;
  }

  // If all checks passed, show success message
  if (isValid) {
    successMsg.textContent = '✅ Your request has been submitted successfully!';

    // Clear the form fields
    document.getElementById('nameInput').value = '';
    document.getElementById('emailInput').value = '';
  }
}
