// Background Image Rotator
const images = ["images/image1.jpg", "images/image2.jpg", "images/image3.jpg"];
let index = 0;

function changeBackground() {
    index = (index + 1) % images.length;
    const heading = document.querySelector(".heading");
    if (heading) {
        heading.style.backgroundImage = `url(${images[index]})`;
        heading.style.transition = "background-image 1s ease-in-out"; // Smooth transition
    }
}
setInterval(changeBackground, 3000);

// Scroll to Top Button
function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

document.getElementById("scrollTop")?.addEventListener("click", scrollToTop);

// Navbar Active State Toggle on Scroll
window.addEventListener('scroll', function () {
    const navbar = document.querySelector('nav');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

// Smooth Scroll for All Links (optional enhancement)
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
        }
    });
});
