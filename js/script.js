// Mobile Navigation Toggle
const hamburger = document.getElementById('hamburger');
const navMenu = document.getElementById('navMenu');

if (hamburger && navMenu) {
    hamburger.addEventListener('click', () => {
        navMenu.classList.toggle('active');
        hamburger.classList.toggle('active');
    });

    // Close menu when clicking on a link
    document.querySelectorAll('.nav-menu a').forEach(link => {
        link.addEventListener('click', () => {
            navMenu.classList.remove('active');
            hamburger.classList.remove('active');
        });
    });

    // Close menu when clicking outside
    document.addEventListener('click', (e) => {
        if (!hamburger.contains(e.target) && !navMenu.contains(e.target)) {
            navMenu.classList.remove('active');
            hamburger.classList.remove('active');
        }
    });
}

// Smooth Scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href !== '#' && href !== '#!') {
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                const offsetTop = target.offsetTop - 80; // Account for sticky navbar
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        }
    });
});

// Navbar Scroll Effect
let lastScroll = 0;
const navbar = document.querySelector('.navbar');

window.addEventListener('scroll', () => {
    const currentScroll = window.pageYOffset;
    
    if (currentScroll > 100) {
        navbar.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.15)';
        navbar.style.background = 'rgba(255, 255, 255, 0.98)';
    } else {
        navbar.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
        navbar.style.background = 'rgba(255, 255, 255, 0.95)';
    }
    
    // Update active nav link based on scroll position
    updateActiveNavLink();
    
    lastScroll = currentScroll;
});

// Update active navigation link based on scroll position
function updateActiveNavLink() {
    const sections = document.querySelectorAll('section[id]');
    const scrollY = window.pageYOffset;

    sections.forEach(section => {
        const sectionHeight = section.offsetHeight;
        const sectionTop = section.offsetTop - 100;
        const sectionId = section.getAttribute('id');
        const navLink = document.querySelector(`.nav-menu a[href="#${sectionId}"]`);

        if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
            document.querySelectorAll('.nav-menu a').forEach(link => {
                link.classList.remove('active');
            });
            if (navLink) {
                navLink.classList.add('active');
            }
        }
    });
}

// Gallery Lightbox Modal
const galleryItems = document.querySelectorAll('.gallery-item');
const galleryModal = document.getElementById('galleryModal');
const modalImage = document.getElementById('modalImage');
const closeModal = document.querySelector('.close-modal');

if (galleryItems.length > 0) {
    galleryItems.forEach((item, index) => {
        item.addEventListener('click', () => {
            const img = item.querySelector('img');
            if (img) {
                modalImage.src = img.src;
                modalImage.alt = img.alt || `Gallery Image ${index + 1}`;
                galleryModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        });
    });
}

// Close modal
if (closeModal) {
    closeModal.addEventListener('click', () => {
        galleryModal.classList.remove('active');
        document.body.style.overflow = 'auto';
    });
}

// Close modal when clicking outside the image
if (galleryModal) {
    galleryModal.addEventListener('click', (e) => {
        if (e.target === galleryModal) {
            galleryModal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && galleryModal.classList.contains('active')) {
            galleryModal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    });
}

// Video Modal Functionality
const videoModal = document.getElementById('videoModal');
const modalVideo = document.getElementById('modalVideo');
const closeVideoModal = document.querySelector('.close-video-modal');
const videoClickables = document.querySelectorAll('.video-clickable');

if (videoClickables.length > 0) {
    videoClickables.forEach((videoWrapper) => {
        const video = videoWrapper.querySelector('video');
        let touchStartY = 0;
        let touchStartTime = 0;
        
        // Function to open video modal
        const openVideoModal = (e) => {
            // Check if click/touch was on video controls (bottom area)
            if (e.target === video) {
                const rect = video.getBoundingClientRect();
                const clickY = (e.clientY || (e.touches && e.touches[0]?.clientY) || touchStartY) - rect.top;
                const videoHeight = rect.height;
                // If click is in bottom 30% (controls area), don't open modal
                if (clickY > videoHeight * 0.7) {
                    return;
                }
            }
            
            e.preventDefault();
            e.stopPropagation();
            
            const videoSrc = videoWrapper.getAttribute('data-video-src');
            if (videoSrc) {
                // Pause the original video if playing
                if (video && !video.paused) {
                    video.pause();
                }
                
                // Set video source and open modal
                modalVideo.src = videoSrc;
                modalVideo.load();
                videoModal.classList.add('active');
                document.body.style.overflow = 'hidden';
                
                // Play video when modal opens
                setTimeout(() => {
                    modalVideo.play().catch(err => {
                        console.log('Video autoplay prevented:', err);
                    });
                }, 100);
            }
        };
        
        // Add click handler for desktop
        videoWrapper.addEventListener('click', openVideoModal);
        
        // Add touch handlers for mobile
        videoWrapper.addEventListener('touchstart', (e) => {
            touchStartTime = Date.now();
            if (e.touches && e.touches[0]) {
                touchStartY = e.touches[0].clientY;
            }
        }, { passive: true });
        
        videoWrapper.addEventListener('touchend', (e) => {
            const touchEndTime = Date.now();
            const touchDuration = touchEndTime - touchStartTime;
            
            // Only open modal if it was a quick tap (not a long press or swipe)
            if (touchDuration < 500) {
                // Create a synthetic event for the check function
                const syntheticEvent = {
                    target: e.target,
                    preventDefault: () => e.preventDefault(),
                    stopPropagation: () => e.stopPropagation(),
                    clientY: touchStartY
                };
                openVideoModal(syntheticEvent);
            }
        }, { passive: false });
    });
}

// Close video modal
if (closeVideoModal) {
    closeVideoModal.addEventListener('click', () => {
        modalVideo.pause();
        modalVideo.src = '';
        videoModal.classList.remove('active');
        document.body.style.overflow = 'auto';
    });
}

// Close video modal when clicking outside
if (videoModal) {
    videoModal.addEventListener('click', (e) => {
        if (e.target === videoModal) {
            modalVideo.pause();
            modalVideo.src = '';
            videoModal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    });

    // Close video modal with Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && videoModal.classList.contains('active')) {
            modalVideo.pause();
            modalVideo.src = '';
            videoModal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    });
}

// Fade in animation on scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observe elements for fade-in animation
document.addEventListener('DOMContentLoaded', () => {
    const animateElements = document.querySelectorAll('.feature-card, .gallery-item, .amenity-item, .about-content, .video-wrapper');
    
    animateElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
});

// Video play/pause on scroll (optional - pause videos when not in view)
const videos = document.querySelectorAll('video');
const videoObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        const video = entry.target;
        if (entry.isIntersecting) {
            // Video is in view - you can autoplay if needed
            // video.play();
        } else {
            // Video is out of view - pause to save resources
            if (!video.paused) {
                video.pause();
            }
        }
    });
}, { threshold: 0.5 });

videos.forEach(video => {
    videoObserver.observe(video);
});

// Floating buttons animation on scroll
const floatingButtons = document.querySelector('.floating-buttons');
if (floatingButtons) {
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            floatingButtons.style.opacity = '1';
            floatingButtons.style.transform = 'translateX(0)';
        } else {
            floatingButtons.style.opacity = '0.7';
        }
    });
}

// Initialize floating buttons
if (floatingButtons) {
    floatingButtons.style.opacity = '0.7';
    floatingButtons.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
}

// Add loading animation for images
const images = document.querySelectorAll('img');
images.forEach(img => {
    img.addEventListener('load', function() {
        this.style.opacity = '1';
    });
    
    // Set initial opacity for fade-in effect
    img.style.opacity = '0';
    img.style.transition = 'opacity 0.5s ease';
    
    // If image is already loaded
    if (img.complete) {
        img.style.opacity = '1';
    }
});

// Contact Form Handling (if exists)
const contactForm = document.getElementById('contactForm');
if (contactForm) {
    contactForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        // Get form data
        const formData = new FormData(contactForm);
        const data = Object.fromEntries(formData);
        
        // Here you would typically send the data to a server
        // For now, we'll just show an alert
        alert('Thank you for your message! We will get back to you soon.');
        
        // Reset form
        contactForm.reset();
    });
}

// Add parallax effect to hero section (optional)
window.addEventListener('scroll', () => {
    const hero = document.querySelector('.hero');
    if (hero) {
        const scrolled = window.pageYOffset;
        const rate = scrolled * 0.5;
        hero.style.transform = `translateY(${rate}px)`;
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
    // Set initial active nav link
    updateActiveNavLink();
    
    // Add smooth reveal animation
    const heroContent = document.querySelector('.hero-content');
    if (heroContent) {
        heroContent.style.opacity = '0';
        setTimeout(() => {
            heroContent.style.opacity = '1';
            heroContent.style.transition = 'opacity 1s ease';
        }, 100);
    }
});
