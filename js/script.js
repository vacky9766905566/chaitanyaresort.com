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

// WhatsApp Click Tracking
// Function to extract WhatsApp number from URL
function extractWhatsAppNumber(url) {
    const match = url.match(/wa\.me\/(\d+)/);
    return match ? match[1] : null;
}

// Function to get or initialize click tracking data
function getClickTrackingData() {
    const stored = localStorage.getItem('whatsappClickTracking');
    return stored ? JSON.parse(stored) : [];
}

// Track recent clicks to prevent duplicates (within 2 seconds)
let recentClicks = new Map();

// Function to save click tracking data with duplicate prevention
function saveClickTracking(whatsappNumber) {
    const now = Date.now();
    const clickKey = `${whatsappNumber}_${Math.floor(now / 2000)}`; // Group by number and 2-second window
    
    // Check if we already saved a click for this number in the last 2 seconds
    if (recentClicks.has(clickKey)) {
        console.log('Duplicate click prevented for:', whatsappNumber);
        return recentClicks.get(clickKey);
    }
    
    const clickData = {
        timestamp: new Date().toISOString(),
        whatsappNumber: whatsappNumber,
        date: new Date().toLocaleDateString('en-IN', { 
            year: 'numeric', 
            month: '2-digit', 
            day: '2-digit' 
        }),
        time: new Date().toLocaleTimeString('en-IN', { 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit',
            hour12: false 
        })
    };
    
    // Get existing data
    const existingData = getClickTrackingData();
    
    // Additional check: prevent exact duplicates (same number and timestamp within 1 second)
    const lastClick = existingData[existingData.length - 1];
    if (lastClick && 
        lastClick.whatsappNumber === whatsappNumber && 
        (now - new Date(lastClick.timestamp).getTime()) < 1000) {
        console.log('Duplicate click prevented (exact match):', whatsappNumber);
        return lastClick;
    }
    
    // Add new click
    existingData.push(clickData);
    
    // Save to localStorage
    localStorage.setItem('whatsappClickTracking', JSON.stringify(existingData));
    
    // Store in recent clicks map
    recentClicks.set(clickKey, clickData);
    
    // Clean up old entries from recentClicks map (older than 5 seconds)
    setTimeout(() => {
        recentClicks.delete(clickKey);
    }, 5000);
    
    // Also save to a JSON file (for download)
    saveToJSONFile(existingData);
    
    // Optional: Send to backend API if available
    // sendToBackend(clickData);
    
    return clickData;
}

// Function to save data to JSON file (for download)
function saveToJSONFile(data) {
    const jsonString = JSON.stringify(data, null, 2);
    const blob = new Blob([jsonString], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    
    // Store the URL for potential download
    window.whatsappTrackingDataURL = url;
    window.whatsappTrackingData = data;
}

// Function to remove duplicate entries from stored data
function removeDuplicates() {
    const data = getClickTrackingData();
    if (data.length === 0) {
        return data;
    }
    
    // Remove duplicates: same number and timestamp within 1 second
    const uniqueData = [];
    const seen = new Set();
    
    data.forEach((item, index) => {
        const key = `${item.whatsappNumber}_${item.timestamp}`;
        const timestamp = new Date(item.timestamp).getTime();
        
        // Check if we've seen a similar entry (same number, within 1 second)
        let isDuplicate = false;
        for (let i = 0; i < uniqueData.length; i++) {
            const existing = uniqueData[i];
            if (existing.whatsappNumber === item.whatsappNumber) {
                const existingTime = new Date(existing.timestamp).getTime();
                if (Math.abs(timestamp - existingTime) < 1000) {
                    isDuplicate = true;
                    break;
                }
            }
        }
        
        if (!isDuplicate) {
            uniqueData.push(item);
        }
    });
    
    // Save cleaned data
    if (uniqueData.length !== data.length) {
        localStorage.setItem('whatsappClickTracking', JSON.stringify(uniqueData));
        console.log(`Removed ${data.length - uniqueData.length} duplicate entries`);
    }
    
    return uniqueData;
}

// Function to download tracking data as JSON file
function downloadTrackingData() {
    const data = getClickTrackingData();
    if (data.length === 0) {
        console.log('No tracking data available');
        return;
    }
    
    const jsonString = JSON.stringify(data, null, 2);
    const blob = new Blob([jsonString], { type: 'application/json' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `whatsapp-clicks-${new Date().toISOString().split('T')[0]}.json`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}

// Function to attach click tracking to WhatsApp buttons
function attachWhatsAppTracking() {
    const floatingButtonsContainer = document.querySelector('.floating-buttons');
    const whatsappButtons = document.querySelectorAll('.floating-btn-whatsapp');
    
    console.log(`Found ${whatsappButtons.length} WhatsApp floating buttons`);
    
    if (whatsappButtons.length === 0) {
        console.warn('No WhatsApp floating buttons found!');
        return;
    }
    
    // Use event delegation on the container - single event listener to prevent duplicates
    if (floatingButtonsContainer) {
        // Track on mousedown (fires before navigation) - single listener to avoid duplicates
        floatingButtonsContainer.addEventListener('mousedown', function(e) {
            const button = e.target.closest('.floating-btn-whatsapp');
            if (button) {
                const href = button.getAttribute('href');
                const whatsappNumber = extractWhatsAppNumber(href);
                
                if (whatsappNumber) {
                    try {
                        // Save the click data (deduplication handled in saveClickTracking)
                        const clickData = saveClickTracking(whatsappNumber);
                        console.log('WhatsApp button clicked - Tracking saved:', clickData);
                        
                        // Verify it was saved
                        const verifyData = getClickTrackingData();
                        console.log(`Total clicks now: ${verifyData.length}`);
                    } catch (error) {
                        console.error('Error tracking WhatsApp click:', error);
                    }
                } else {
                    console.warn('Could not extract WhatsApp number from:', href);
                }
            }
        }, true); // Use capture phase
    }
    
    // Make download function available globally for admin use
    window.downloadWhatsAppTrackingData = downloadTrackingData;
    
    // Make remove duplicates function available globally
    window.removeDuplicates = function() {
        const before = getClickTrackingData().length;
        const cleaned = removeDuplicates();
        const after = cleaned.length;
        const removed = before - after;
        if (removed > 0) {
            alert(`Removed ${removed} duplicate entries!`);
            console.log(`Removed ${removed} duplicate entries`);
        } else {
            alert('No duplicates found!');
        }
        return cleaned;
    };
    
    // Make test function available for debugging
    window.testWhatsAppTracking = function(whatsappNumber = '919112680201') {
        console.log('Testing WhatsApp tracking with number:', whatsappNumber);
        const clickData = saveClickTracking(whatsappNumber);
        console.log('Test click saved:', clickData);
        const verifyData = getClickTrackingData();
        console.log(`Total clicks now: ${verifyData.length}`);
        alert(`Test click saved! Total clicks: ${verifyData.length}`);
        return clickData;
    };
    
    // Clean up any existing duplicates on initialization
    removeDuplicates();
    
    // Log current tracking data count (for debugging)
    const currentData = getClickTrackingData();
    if (currentData.length > 0) {
        console.log(`WhatsApp clicks tracked: ${currentData.length}`);
    } else {
        console.log('No WhatsApp clicks tracked yet');
    }
    
    // Log button info for debugging
    console.log('WhatsApp tracking initialized. Buttons found:', whatsappButtons.length);
    whatsappButtons.forEach((btn, idx) => {
        console.log(`Button ${idx + 1}:`, btn.getAttribute('href'));
    });
}

// Track clicks on WhatsApp floating buttons
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', attachWhatsAppTracking);
} else {
    // DOM is already loaded
    attachWhatsAppTracking();
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
