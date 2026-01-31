/* ======================================
   WeOryx - JavaScript Interactions
   ====================================== */

document.addEventListener('DOMContentLoaded', function () {

    // ===============================
    // Initialize AOS
    // ===============================
    AOS.init({
        duration: 600,
        easing: 'ease-out',
        once: true,
        offset: 0,
        delay: 0,
        anchorPlacement: 'top-bottom',
        disable: window.innerWidth < 768 // Disable on mobile for better performance
    });

    // ===============================
    // Initialize Hero Swiper
    // ===============================
    const heroSwiper = new Swiper('.hero-swiper', {
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.hero-swiper .swiper-pagination',
            clickable: true,
        },
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        },
        speed: 800,
    });

    // ===============================
    // Initialize Testimonials Swiper
    // ===============================
    const testimonialsSwiper = new Swiper('.testimonials-swiper', {
        loop: true,
        autoplay: {
            delay: 6000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.testimonials-swiper .swiper-pagination',
            clickable: true,
        },
        speed: 600,
        spaceBetween: 30,
    });

    // ===============================
    // Initialize Reels Swiper
    // ===============================
    const reelsSwiper = new Swiper('.reels-swiper', {
        slidesPerView: 1.5,
        spaceBetween: 20,
        centeredSlides: false,
        loop: false,
        grabCursor: true,
        navigation: {
            nextEl: '.reels-section .swiper-button-next',
            prevEl: '.reels-section .swiper-button-prev',
        },
        pagination: {
            el: '.reels-section .swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            480: {
                slidesPerView: 2.5,
                spaceBetween: 20,
            },
            768: {
                slidesPerView: 3.5,
                spaceBetween: 25,
            },
            1024: {
                slidesPerView: 4.5,
                spaceBetween: 30,
            },
            1400: {
                slidesPerView: 5.5,
                spaceBetween: 30,
            }
        }
    });


    // ===============================
    // Header Scroll Effect & Logo Switch
    // ===============================
    const header = document.getElementById('header');
    const logoImg = document.querySelector('.logo-img');

    function handleScroll() {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
            // Switch to colored logo on scrolled/sticky header
            if (logoImg) {
                logoImg.src = 'images/logo.svg';
            }
        } else {
            header.classList.remove('scrolled');
            // Switch back to white logo on transparent header
            if (logoImg) {
                logoImg.src = 'images/logo-white.svg';
            }
        }
    }

    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll(); // Check on load

    // ===============================
    // Mobile Menu Toggle
    // ===============================
    const menuToggle = document.getElementById('menuToggle');
    const navMenu = document.getElementById('navMenu');

    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', () => {
            menuToggle.classList.toggle('active');
            navMenu.classList.toggle('active');
            document.body.style.overflow = navMenu.classList.contains('active') ? 'hidden' : '';
        });

        // Close menu when clicking on a link
        navMenu.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                menuToggle.classList.remove('active');
                navMenu.classList.remove('active');
                document.body.style.overflow = '';
            });
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!navMenu.contains(e.target) && !menuToggle.contains(e.target)) {
                menuToggle.classList.remove('active');
                navMenu.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }

    // ===============================
    // Active Nav Link
    // ===============================
    const currentPage = window.location.pathname.split('/').pop() || 'index.html';
    const navLinks = document.querySelectorAll('.nav-link');

    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href === currentPage || (currentPage === '' && href === 'index.html')) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });

    // ===============================
    // Counter Animation
    // ===============================
    const counters = document.querySelectorAll('.stat-number');
    let countersAnimated = false;

    function animateCounters() {
        if (countersAnimated) return;

        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-count'));
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;

            const updateCounter = () => {
                current += step;
                if (current < target) {
                    counter.textContent = Math.floor(current);
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.textContent = target;
                }
            };

            updateCounter();
        });

        countersAnimated = true;
    }

    // Intersection Observer for counters
    const statsSection = document.querySelector('.stats-section');
    if (statsSection) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounters();
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.3 });

        observer.observe(statsSection);
    }

    // ===============================
    // Back to Top Button
    // ===============================
    const backToTop = document.getElementById('backToTop');

    if (backToTop) {
        function toggleBackToTop() {
            if (window.scrollY > 500) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        }

        window.addEventListener('scroll', toggleBackToTop, { passive: true });

        backToTop.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // ===============================
    // Smooth Scroll for Anchor Links
    // ===============================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');

            if (targetId === '#') return;

            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                e.preventDefault();

                const headerHeight = header.offsetHeight;
                const targetPosition = targetElement.offsetTop - headerHeight;

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // ===============================
    // Contact Form Handling
    // ===============================
    const contactForm = document.getElementById('contactForm');

    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(contactForm);
            const data = Object.fromEntries(formData);

            // Basic validation
            let isValid = true;
            const requiredFields = ['name', 'email', 'message'];

            requiredFields.forEach(field => {
                const input = contactForm.querySelector(`[name="${field}"]`);
                if (input && !input.value.trim()) {
                    isValid = false;
                    input.style.borderColor = '#E85A3C';
                } else if (input) {
                    input.style.borderColor = '#E8E8E8';
                }
            });

            // Email validation
            const emailInput = contactForm.querySelector('[name="email"]');
            if (emailInput) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(emailInput.value)) {
                    isValid = false;
                    emailInput.style.borderColor = '#E85A3C';
                }
            }

            if (isValid) {
                const submitBtn = contactForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-check"></i> Message Sent!';
                submitBtn.style.background = '#28a745';
                submitBtn.disabled = true;

                setTimeout(() => {
                    contactForm.reset();
                    submitBtn.innerHTML = originalText;
                    submitBtn.style.background = '';
                    submitBtn.disabled = false;
                }, 3000);

                console.log('Form submitted:', data);
            }
        });

        // Reset input styles on focus
        contactForm.querySelectorAll('input, textarea').forEach(input => {
            input.addEventListener('focus', function () {
                this.style.borderColor = '#1A5F7A';
            });

            input.addEventListener('blur', function () {
                if (this.value.trim()) {
                    this.style.borderColor = '#E8E8E8';
                }
            });
        });
    }

    // ===============================
    // Portfolio Filter (for portfolio page)
    // ===============================
    const filterButtons = document.querySelectorAll('.filter-btn');
    const portfolioItems = document.querySelectorAll('.portfolio-item[data-category]');

    if (filterButtons.length > 0 && portfolioItems.length > 0) {
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');

                const filter = button.getAttribute('data-filter');

                portfolioItems.forEach(item => {
                    const category = item.getAttribute('data-category');

                    if (filter === 'all' || category === filter) {
                        item.style.display = 'block';
                        item.style.animation = 'fadeIn 0.5s ease forwards';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    }

    // ===============================
    // Newsletter Form
    // ===============================
    const newsletterForms = document.querySelectorAll('.newsletter-form');

    newsletterForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const emailInput = this.querySelector('input[type="email"]');
            const submitBtn = this.querySelector('button');

            if (emailInput && emailInput.value.trim()) {
                const originalIcon = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-check"></i>';
                emailInput.value = '';

                setTimeout(() => {
                    submitBtn.innerHTML = originalIcon;
                }, 2000);
            }
        });
    });

    // ===============================
    // Lazy Loading Images
    // ===============================
    const lazyImages = document.querySelectorAll('img[data-src]');

    if ('IntersectionObserver' in window && lazyImages.length > 0) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    observer.unobserve(img);
                }
            });
        });

        lazyImages.forEach(img => imageObserver.observe(img));
    }

});

// ===============================
// Window Resize Handler
// ===============================
let resizeTimer;
window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
        if (window.innerWidth > 992) {
            const navMenu = document.getElementById('navMenu');
            const menuToggle = document.getElementById('menuToggle');
            if (navMenu && menuToggle) {
                navMenu.classList.remove('active');
                menuToggle.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
    }, 250);
});

// ===============================
// Console Message
// ===============================
console.log('%c WeOryx Digital Marketing ', 'background: #1A5F7A; color: #fff; padding: 10px 20px; font-size: 16px; font-family: Poppins, sans-serif; border-radius: 5px;');
console.log('%c Designed with ❤️ ', 'color: #E85A3C; font-size: 12px;');
