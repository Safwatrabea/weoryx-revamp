/* ======================================
   WeOryx - JavaScript Interactions
   ====================================== */

// Global initialization function for the whole page
window.weoryxInitAll = function () {
    // ===============================
    // AOS (Animate on Scroll)
    // ===============================
    if (typeof AOS !== 'undefined') {
        const isEditor = window.weoryx_is_editor || false;

        if (document.body.classList.contains('aos-init')) {
            AOS.refresh();
        } else {
            AOS.init({
                duration: 800,
                easing: 'ease-out',
                once: true,
                offset: 0,
                anchorPlacement: 'top-bottom',
                disable: isEditor ? true : false
            });
            // Force a refresh after a short delay
            setTimeout(() => { AOS.refresh(); }, 500);
        }
    }

    // ===============================
    // Initialize Swipers
    // ===============================
    if (typeof Swiper !== 'undefined') {
        // Helper to check if loop should be enabled
        const shouldLoop = (el, slidesPerView = 1) => {
            const slidesCount = el.querySelectorAll('.swiper-slide').length;
            // If slidesPerView is 'auto', we might need a more complex check, 
            // but for marquee (clients) we usually want loop anyway if count > 2
            if (slidesPerView === 'auto') return slidesCount > 2;
            return slidesCount > Math.ceil(slidesPerView);
        };

        // Hero Swiper
        document.querySelectorAll('.hero-swiper').forEach(el => {
            if (el.swiper) el.swiper.destroy();
            new Swiper(el, {
                loop: shouldLoop(el, 1),
                autoplay: { delay: 5000, disableOnInteraction: false },
                pagination: { el: '.swiper-pagination', clickable: true },
                effect: 'fade',
                fadeEffect: { crossFade: true },
                speed: 800,
            });
        });

        // Testimonials (Stack) Swiper
        document.querySelectorAll('.stack-swiper').forEach(el => {
            if (el.swiper) el.swiper.destroy();
            new Swiper(el, {
                effect: 'cards',
                cardsEffect: { slideShadows: true, perSlideOffset: 12, perSlideRotate: 4 },
                grabCursor: true,
                loop: shouldLoop(el, 1),
                speed: 800,
                autoplay: { delay: 5000, disableOnInteraction: false },
                navigation: {
                    nextEl: el.closest('.testimonial-stack-wrapper')?.querySelector('.swiper-button-next') || '.stack-nav.swiper-button-next',
                    prevEl: el.closest('.testimonial-stack-wrapper')?.querySelector('.swiper-button-prev') || '.stack-nav.swiper-button-prev',
                },
            });
        });

        // Reels Swiper
        document.querySelectorAll('.reels-swiper').forEach(el => {
            if (el.swiper) el.swiper.destroy();
            // Get the closest controls bar for this swiper instance
            const section = el.closest('.container-fluid') || el.parentElement;
            const prevBtn = section.querySelector('.reels-nav-prev');
            const nextBtn = section.querySelector('.reels-nav-next');
            const paginationEl = section.querySelector('.reels-pagination');

            new Swiper(el, {
                slidesPerView: 1.5,
                spaceBetween: 20,
                loop: false,
                grabCursor: true,
                navigation: {
                    nextEl: nextBtn || '.reels-nav-next',
                    prevEl: prevBtn || '.reels-nav-prev'
                },
                pagination: {
                    el: paginationEl || '.reels-pagination',
                    clickable: true
                },
                breakpoints: {
                    480: { slidesPerView: 2.5, spaceBetween: 20 },
                    768: { slidesPerView: 3.5, spaceBetween: 25 },
                    1024: { slidesPerView: 4.5, spaceBetween: 30 },
                    1400: { slidesPerView: 5.5, spaceBetween: 30 }
                }
            });
        });

        // Clients Marquee
        // Clients Marquee (Handled by CSS for 100% reliability)
        // document.querySelectorAll('.clients-swiper').forEach(el => { ... });

        // Team Swiper
        document.querySelectorAll('.team-swiper').forEach(el => {
            if (el.swiper) el.swiper.destroy();
            const slidesCount = el.querySelectorAll('.swiper-slide').length;
            new Swiper(el, {
                slidesPerView: 1,
                spaceBetween: 30,
                loop: slidesCount > 1,
                grabCursor: true,
                navigation: {
                    nextEl: '.team-nav-next',
                    prevEl: '.team-nav-prev',
                },
                breakpoints: {
                    640: { slidesPerView: 2, spaceBetween: 20 },
                    768: { slidesPerView: 3, spaceBetween: 30 },
                    1024: { slidesPerView: 4, spaceBetween: 30 }
                }
            });
        });
    }

    // ===============================
    // Video Block Interactivity
    // ===============================
    document.querySelectorAll(".video-container").forEach((container) => {
        if (container.dataset.initDone) return;
        container.dataset.initDone = "true";

        container.style.cursor = "pointer";

        const startVideo = function (e) {
            if (e) e.preventDefault();
            if (container.querySelector("iframe") || container.querySelector("video")) return;

            const videoUrl = container.getAttribute("data-video-url");
            const overlay = container.querySelector(".video-overlay") || container.querySelector("[data-video-id]");
            const videoId = overlay ? (overlay.getAttribute("data-video-id") || overlay.dataset.videoId) : null;

            let embedUrl = "";
            if (videoId && videoId.length === 11) {
                embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0&enablejsapi=1`;
            } else if (videoUrl) {
                const ytRegex = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/;
                const match = videoUrl.match(ytRegex);
                if (match && match[1]) {
                    embedUrl = `https://www.youtube.com/embed/${match[1]}?autoplay=1&rel=0&enablejsapi=1`;
                } else if (videoUrl.includes("google.com") || videoUrl.includes("googleapis.com") || videoUrl.endsWith(".mp4") || videoUrl.includes("wp-content/uploads")) {
                    const videoEl = document.createElement("video");
                    videoEl.controls = true;
                    videoEl.autoplay = true;
                    videoEl.playsInline = true;
                    videoEl.style.width = "100%";
                    videoEl.style.height = "100%";
                    videoEl.style.objectFit = "cover";
                    videoEl.style.position = "absolute";
                    videoEl.style.top = "0";
                    videoEl.style.left = "0";
                    videoEl.style.zIndex = "10";

                    const source = document.createElement("source");
                    source.src = videoUrl;
                    source.type = "video/mp4";
                    videoEl.appendChild(source);

                    if (overlay) overlay.style.display = "none";
                    container.appendChild(videoEl);
                    return;
                } else if (videoUrl.includes('<iframe')) {
                    if (overlay) overlay.style.display = "none";
                    container.insertAdjacentHTML('beforeend', videoUrl);
                    const iframe = container.querySelector('iframe:last-child');
                    if (iframe) {
                        iframe.style.position = "absolute";
                        iframe.style.top = "0";
                        iframe.style.left = "0";
                        iframe.style.width = "100%";
                        iframe.style.height = "100%";
                        iframe.style.zIndex = "10";
                    }
                    return;
                } else {
                    // Match TikTok
                    const tiktokRegex = /tiktok\.com\/.*\/video\/(\d+)/;
                    const tiktokMatch = videoUrl.match(tiktokRegex);
                    
                    // Match Instagram
                    const igRegex = /instagram\.com\/(?:p|reel)\/([a-zA-Z0-9_-]+)/;
                    const igMatch = videoUrl.match(igRegex);
                    
                    // Match Vimeo
                    const vimeoRegex = /vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/(?:[^\/]*)\/videos\/|album\/(?:\d+)\/video\/|)(\d+)(?:$|\/|\?)/;
                    const vimeoMatch = videoUrl.match(vimeoRegex);

                    if (tiktokMatch && tiktokMatch[1]) {
                        embedUrl = `https://www.tiktok.com/embed/v2/${tiktokMatch[1]}`;
                    } else if (igMatch && igMatch[1]) {
                        embedUrl = `https://www.instagram.com/p/${igMatch[1]}/embed/captioned/`;
                    } else if (vimeoMatch && vimeoMatch[1]) {
                        embedUrl = `https://player.vimeo.com/video/${vimeoMatch[1]}?autoplay=1`;
                    } else if (videoUrl.includes('<blockquote') || videoUrl.includes('<script')) {
                        // User pasted a blockquote with script (e.g. raw TikTok HTML)
                        if (overlay) overlay.style.display = "none";
                        container.insertAdjacentHTML('beforeend', videoUrl);
                        // Make sure to execute the script if any
                        const scripts = container.querySelectorAll('script');
                        scripts.forEach(oldScript => {
                            const newScript = document.createElement('script');
                            Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                            newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                            oldScript.parentNode.replaceChild(newScript, oldScript);
                        });
                        return;
                    } else {
                        embedUrl = videoUrl; // Generic Fallback
                    }
                }
            }

            if (embedUrl) {
                const iframe = document.createElement("iframe");
                iframe.width = "100%";
                iframe.height = "100%";
                iframe.src = embedUrl;
                iframe.frameBorder = "0";
                iframe.scrolling = "no"; // Hide scrollbar
                iframe.allow = "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture";
                iframe.allowFullscreen = true;
                iframe.style.position = "absolute";
                iframe.style.top = "0";
                iframe.style.left = "0";
                iframe.style.width = "100%";
                iframe.style.height = "100%";
                iframe.style.zIndex = "10";
                // Add a border-radius if needed
                iframe.style.borderRadius = "inherit";

                if (overlay) overlay.style.display = "none";
                container.appendChild(iframe);
                
                // Add a close button to let user dismiss the video when it ends
                const closeBtn = document.createElement("button");
                closeBtn.innerHTML = '<i class="fas fa-times"></i>';
                closeBtn.style.position = "absolute";
                closeBtn.style.top = "10px";
                closeBtn.style.right = "10px";
                closeBtn.style.zIndex = "11";
                closeBtn.style.background = "rgba(0,0,0,0.6)";
                closeBtn.style.color = "#fff";
                closeBtn.style.border = "none";
                closeBtn.style.borderRadius = "50%";
                closeBtn.style.width = "30px";
                closeBtn.style.height = "30px";
                closeBtn.style.display = "flex";
                closeBtn.style.alignItems = "center";
                closeBtn.style.justifyContent = "center";
                closeBtn.style.cursor = "pointer";
                closeBtn.style.fontSize = "14px";
                closeBtn.style.transition = "0.3s";
                
                closeBtn.onmouseover = () => closeBtn.style.background = "var(--accent-orange, #e85a3c)";
                closeBtn.onmouseout = () => closeBtn.style.background = "rgba(0,0,0,0.6)";
                
                closeBtn.addEventListener("click", (evt) => {
                    evt.stopPropagation(); // don't trigger startVideo again
                    iframe.remove();
                    closeBtn.remove();
                    if (overlay) overlay.style.display = "";
                });
                
                container.appendChild(closeBtn);
            }
        };

        container.addEventListener("click", startVideo);

        // Also listen for play button clicks explicitly
        const playBtn = container.querySelector(".play-btn, .play-button");
        if (playBtn) {
            playBtn.addEventListener("click", (e) => {
                e.stopPropagation();
                startVideo(e);
            });
        }
    });


    // ===============================
    // Counters
    // ===============================
    const counters = document.querySelectorAll('.stat-number');
    const statsSection = document.querySelector('.stats-section');
    if (statsSection && counters.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    counters.forEach(counter => {
                        const target = parseInt(counter.getAttribute('data-target'));
                        if (isNaN(target)) return;
                        let current = 0;
                        const step = target / 120;
                        const updateCounter = () => {
                            current += step;
                            if (current < target) {
                                counter.textContent = Math.floor(current);
                                requestAnimationFrame(updateCounter);
                            } else { counter.textContent = target; }
                        };
                        updateCounter();
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.3 });
        observer.observe(statsSection);
    }
};

document.addEventListener('DOMContentLoaded', function () {
    // Run all initializers
    window.weoryxInitAll();

    // Final refresh on window load
    window.addEventListener('load', () => {
        if (typeof AOS !== 'undefined') {
            AOS.refresh();
        }
    });

    // ===============================
    // Header Scroll Effect & Logo Switch
    // ===============================
    const header = document.getElementById('header');
    const logoImg = document.querySelector('.logo-img');

    function handleScroll() {
        if (!header) return;
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    }

    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll();

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

        navMenu.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                menuToggle.classList.remove('active');
                navMenu.classList.remove('active');
                document.body.style.overflow = '';
            });
        });
    }

    // ===============================
    // Back to Top Button
    // ===============================
    const backToTop = document.getElementById('backToTop');
    if (backToTop) {
        window.addEventListener('scroll', () => {
            backToTop.classList.toggle('visible', window.scrollY > 500);
        }, { passive: true });

        backToTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // ===============================
    // Portfolio Tab Switching
    // ===============================
    const tabLinks = document.querySelectorAll('.filter-link-v2');
    const tabContents = document.querySelectorAll('.portfolio-tab-content');
    const portfolioGrid = document.querySelector('.portfolio-main-grid');

    if (tabLinks.length > 0 && tabContents.length > 0) {
        tabLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('data-tab');

                // Update Active Tab Link
                tabLinks.forEach(l => l.classList.remove('is-active'));
                this.classList.add('is-active');

                // Switch Visibility
                tabContents.forEach(content => {
                    content.classList.remove('is-active');
                    if (content.id === targetId) {
                        content.classList.add('is-active');
                    }
                });

                // Scroll to top of grid for better UX
                if (portfolioGrid) {
                    const headerHeight = header ? header.offsetHeight : 0;
                    const filterHeight = document.querySelector('.portfolio-filter-v2')?.offsetHeight || 0;
                    window.scrollTo({
                        top: portfolioGrid.offsetTop - headerHeight - filterHeight - 20,
                        behavior: 'smooth'
                    });
                }

                // Refresh AOS for new content
                setTimeout(() => {
                    if (typeof AOS !== 'undefined') {
                        AOS.refresh();
                    }
                }, 100);
            });
        });

        // Magnetic Effect for Portfolio Tabs
        tabLinks.forEach(link => {
            link.addEventListener('mousemove', function (e) {
                const rect = this.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;

                this.style.transform = `translate(${x * 0.3}px, ${y * 0.5}px)`;
                this.querySelector('.link-text').style.transform = `translate(${x * 0.15}px, ${y * 0.25}px)`;
            });

            link.addEventListener('mouseleave', function () {
                this.style.transform = `translate(0, 0)`;
                this.querySelector('.link-text').style.transform = `translate(0, 0)`;
            });
        });

        // Interactive Aurora Background
        const aurora = document.querySelector('.portfolio-bg-aurora');
        if (aurora) {
            window.addEventListener('mousemove', (e) => {
                const mouseX = e.clientX / window.innerWidth;
                const mouseY = e.clientY / window.innerHeight;

                const blobs = aurora.querySelectorAll('.aurora-blob');
                blobs.forEach((blob, index) => {
                    const speed = (index + 1) * 20;
                    const x = (mouseX - 0.5) * speed;
                    const y = (mouseY - 0.5) * speed;
                    blob.style.transform = `translate(${x}px, ${y}px)`;
                });
            });
        }
    }

    // ===============================
    // Smooth Scroll (Updated to ignore portfolio tabs)
    // ===============================
    document.querySelectorAll('a[href^="#"]:not(.filter-link-v2)').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                e.preventDefault();
                const headerHeight = header ? header.offsetHeight : 0;
                window.scrollTo({
                    top: targetElement.offsetTop - headerHeight,
                    behavior: 'smooth'
                });
            }
        });
    });
});

// ===============================
// Modern Portfolio Filter
// ===============================
const filterBtns = document.querySelectorAll('.filter-btn');
const portfolioGridModern = document.querySelector('.portfolio-grid-modern');
const portfolioItems = document.querySelectorAll('.portfolio-item-modern');

if (filterBtns.length > 0 && portfolioGridModern) {
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            // Remove active class from all buttons
            filterBtns.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');

            const filterValue = this.getAttribute('data-filter');

            portfolioItems.forEach(item => {
                if (filterValue === 'all' || item.classList.contains(filterValue)) {
                    item.classList.remove('hide');
                    item.classList.add('show');
                } else {
                    item.classList.add('hide');
                    item.classList.remove('show');
                }
            });

            // Refresh AOS
            setTimeout(() => {
                if (typeof AOS !== 'undefined') {
                    AOS.refresh();
                }
            }, 100);
        });
    });
}


console.log('%c WeOryx Digital Marketing ', 'background: #1A5F7A; color: #fff; padding: 10px 20px; font-size: 16px; font-family: Poppins, sans-serif; border-radius: 5px;');
