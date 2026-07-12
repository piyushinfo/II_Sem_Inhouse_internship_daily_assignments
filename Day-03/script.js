// Day 3 - Responsive Landing Page

// Smooth Scrolling for all nav links
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
