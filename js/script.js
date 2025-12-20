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
    
    // Store reference to original removeDuplicates function before shadowing
    const originalRemoveDuplicates = removeDuplicates;
    
    // Make remove duplicates function available globally
    window.removeDuplicates = function() {
        const before = getClickTrackingData().length;
        const cleaned = originalRemoveDuplicates();
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
    
    // Clean up any existing duplicates on initialization (use original function)
    originalRemoveDuplicates();
    
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

// Visitor Information Modal
function showVisitorModal() {
    // Show modal on every page visit
    const visitorModal = document.getElementById('visitorModal');
    if (visitorModal) {
        visitorModal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function hideVisitorModal() {
    const visitorModal = document.getElementById('visitorModal');
    if (visitorModal) {
        visitorModal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
}

// Function to save visitor information - always tries server first
async function saveVisitorInfo(name, contact) {
    console.log('saveVisitorInfo called with:', { name, contact });
    
    // Prepare visitor data
    const visitorData = {
        timestamp: new Date().toISOString(),
        name: name.trim(),
        contact: contact.trim(),
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
    
    console.log('Prepared visitor data:', visitorData);
    
    // Check if we're running from file:// protocol
    const isLocalFile = window.location.protocol === 'file:';
    console.log('Protocol check - isLocalFile:', isLocalFile, 'protocol:', window.location.protocol);
    
    if (isLocalFile) {
        // For file:// protocol, save directly to visitors.json file
        console.log('Running from file:// protocol. Saving directly to visitors.json file.');
        
        // Save directly to file using File System Access API
        try {
            await saveDirectlyToFile(visitorData);
            console.log('✓ Data saved directly to visitors.json file');
            return visitorData;
        } catch (error) {
            console.error('Failed to save to file:', error);
            throw new Error('Failed to save data. Please grant folder permission when prompted.');
        }
    }
    
    // For http:// or https://, try to save to server
    try {
        const response = await fetch('save-visitor.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(visitorData)
        });
        
        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`HTTP error! status: ${response.status}, message: ${errorText}`);
        }
        
        const result = await response.json();
        
        if (result.success) {
            console.log('✓ Visitor information saved to server:', visitorData);
            console.log('Server response:', result);
            console.log('Total visitors:', result.totalVisitors);
            return visitorData;
        } else {
            console.error('Server error:', result.error);
            throw new Error(result.error || 'Failed to save data');
        }
    } catch (error) {
        console.error('Error saving visitor info to server:', error);
        throw error;
    }
}

// Save directly to visitors.json file (no localStorage)
async function saveDirectlyToFile(visitorData) {
    // Try to use File System Access API
    if (!('showDirectoryPicker' in window)) {
        throw new Error('File System Access API not supported. Please use Chrome or Edge browser.');
    }
    
    try {
        // Check if we have permission stored in IndexedDB
        let dataDirHandle = await getStoredDirectoryHandle();
        
        // Validate the handle is still valid
        if (dataDirHandle) {
            try {
                // Test if handle is valid by trying to get a file
                await dataDirHandle.getFileHandle('visitors.json', { create: false }).catch(() => {});
                console.log('Using stored directory handle');
            } catch (error) {
                console.log('Stored handle is invalid, requesting new one');
                dataDirHandle = null;
                // Clear invalid handle
                await clearStoredDirectoryHandle();
            }
        }
        
        if (!dataDirHandle) {
            // Request directory permission (one-time only)
            // Try to use startIn option if available (Chrome 120+)
            const pickerOptions = {};
            
            // Try to set startIn to the project directory if possible
            try {
                // Check if we can use startIn (not all browsers support this)
                if ('startIn' in window.showDirectoryPicker) {
                    // This won't work with file://, but we can try
                    pickerOptions.startIn = 'documents'; // Default fallback
                }
            } catch (e) {
                // Ignore if not supported
            }
            
            const userConfirmed = confirm(
                'To save data automatically, please select the "data" folder.\n\n' +
                'Navigate to: C:\\Project\\chaitanyaresort.com\\data\n\n' +
                'This is a ONE-TIME permission. After this, all data will save automatically without asking.'
            );
            
            if (!userConfirmed) {
                throw new Error('User cancelled directory selection');
            }
            
            dataDirHandle = await window.showDirectoryPicker(pickerOptions);
            
            // Verify it's a valid directory handle
            if (!dataDirHandle || typeof dataDirHandle.getFileHandle !== 'function') {
                throw new Error('Invalid directory handle received');
            }
            
            // Store handle in IndexedDB for persistence
            await storeDirectoryHandle(dataDirHandle);
            console.log('✓ Directory permission granted and stored');
        }
        
        // Verify handle has getFileHandle method
        if (typeof dataDirHandle.getFileHandle !== 'function') {
            console.error('Invalid directory handle, requesting new one');
            // Clear invalid handle first
            await clearStoredDirectoryHandle();
            // Request new handle
            dataDirHandle = await window.showDirectoryPicker();
            if (dataDirHandle && typeof dataDirHandle.getFileHandle === 'function') {
                await storeDirectoryHandle(dataDirHandle);
            } else {
                throw new Error('Failed to get valid directory handle');
            }
        }
        
        // Read existing data from visitors.json
        let existingData = [];
        try {
            const jsonFileHandle = await dataDirHandle.getFileHandle('visitors.json', { create: true });
            const file = await jsonFileHandle.getFile();
            const fileContent = await file.text();
            if (fileContent.trim()) {
                existingData = JSON.parse(fileContent);
                console.log('Loaded', existingData.length, 'existing records from visitors.json');
            }
        } catch (error) {
            console.log('visitors.json not found or empty, starting fresh:', error);
            existingData = [];
        }
        
        // Add new visitor data
        existingData.push(visitorData);
        console.log('Total records after adding new:', existingData.length);
        
        // Create JSON content
        const jsonContent = JSON.stringify(existingData, null, 2);
        
        // Create JavaScript content
        const jsContent = `// Auto-generated JavaScript file from visitors.json
// This file is updated automatically when visitors.json changes
window.visitorsData = ${JSON.stringify(existingData, null, 2)};
`;
        
        // Write visitors.json
        const jsonFileHandle = await dataDirHandle.getFileHandle('visitors.json', { create: true });
        const jsonWritable = await jsonFileHandle.createWritable();
        await jsonWritable.write(jsonContent);
        await jsonWritable.close();
        console.log('✓ visitors.json updated');
        
        // Write visitors.js
        const jsFileHandle = await dataDirHandle.getFileHandle('visitors.js', { create: true });
        const jsWritable = await jsFileHandle.createWritable();
        await jsWritable.write(jsContent);
        await jsWritable.close();
        console.log('✓ visitors.js updated');
        
        return true;
    } catch (error) {
        if (error.name === 'AbortError') {
            console.log('Directory permission cancelled');
            throw new Error('File save cancelled. Please try again and select the data folder.');
        } else {
            console.error('File save error:', error);
            throw error;
        }
    }
}

// Store directory handle in IndexedDB for persistence
async function storeDirectoryHandle(handle) {
    try {
        if (!handle || typeof handle.getFileHandle !== 'function') {
            throw new Error('Invalid handle provided');
        }
        
        const db = await openDB();
        const tx = db.transaction('handles', 'readwrite');
        const store = tx.objectStore('handles');
        
        // Store handle (IndexedDB can store FileSystemDirectoryHandle in modern browsers)
        await store.put(handle, 'dataDir');
        
        // Wait for transaction to complete
        await new Promise((resolve, reject) => {
            tx.oncomplete = () => resolve();
            tx.onerror = () => reject(tx.error);
        });
        
        // Also store in window for immediate access (faster)
        window.dataDirHandle = handle;
        
        console.log('✓ Directory handle stored in IndexedDB and window');
    } catch (error) {
        console.warn('Could not store directory handle:', error);
        // Fallback: store in window only (won't persist across reloads)
        window.dataDirHandle = handle;
        console.log('Stored handle in window as fallback');
    }
}

// Get stored directory handle from IndexedDB
async function getStoredDirectoryHandle() {
    try {
        // First check window (faster, for same session)
        if (window.dataDirHandle && typeof window.dataDirHandle.getFileHandle === 'function') {
            console.log('Using directory handle from window');
            return window.dataDirHandle;
        }
        
        // Then check IndexedDB (for persistence across reloads)
        const db = await openDB();
        const tx = db.transaction('handles', 'readonly');
        const store = tx.objectStore('handles');
        const handle = await store.get('dataDir');
        
        // Verify handle is valid
        if (handle && typeof handle.getFileHandle === 'function') {
            // Also store in window for faster access
            window.dataDirHandle = handle;
            console.log('Retrieved directory handle from IndexedDB');
            return handle;
        }
        
        return null;
    } catch (error) {
        console.warn('Could not get stored directory handle:', error);
        return null;
    }
}

// Clear stored directory handle
async function clearStoredDirectoryHandle() {
    try {
        const db = await openDB();
        const tx = db.transaction('handles', 'readwrite');
        const store = tx.objectStore('handles');
        await store.delete('dataDir');
        await tx.complete;
        console.log('Cleared invalid directory handle');
    } catch (error) {
        console.warn('Could not clear directory handle:', error);
    }
}

// Open IndexedDB database
function openDB() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open('visitorDataDB', 1);
        request.onerror = () => reject(request.error);
        request.onsuccess = () => resolve(request.result);
        request.onupgradeneeded = (event) => {
            const db = event.target.result;
            if (!db.objectStoreNames.contains('handles')) {
                db.createObjectStore('handles');
            }
        };
    });
}

// Helper function to save to visitors.json file (for file:// protocol) - DEPRECATED
async function saveToVisitorsJS(visitorData) {
    console.log('saveToVisitorsJS called with:', visitorData);
    try {
        // Try to load existing data from visitors.json via visitors.js
        let existingData = [];
        
        // Check if visitors.js is already loaded
        if (window.visitorsData && Array.isArray(window.visitorsData)) {
            existingData = [...window.visitorsData];
            console.log('Loaded existing data from window.visitorsData:', existingData.length, 'entries');
        } else {
            // Try to load from visitors.js file
            try {
                await loadVisitorsJS();
                if (window.visitorsData && Array.isArray(window.visitorsData)) {
                    existingData = [...window.visitorsData];
                    console.log('Loaded existing data from visitors.js:', existingData.length, 'entries');
                }
            } catch (error) {
                console.warn('Could not load existing visitors.js, starting fresh:', error);
                existingData = [];
            }
        }
        
        // Add new visitor data
        existingData.push(visitorData);
        console.log('New data array:', existingData);
        
        // Update window.visitorsData
        window.visitorsData = existingData;
        
        // Create JSON content for visitors.json
        const jsonContent = JSON.stringify(existingData, null, 2);
        
        // Generate JavaScript content for visitors.js
        const jsContent = `// Auto-generated JavaScript file from visitors.json
// This file is updated automatically when visitors.json changes
window.visitorsData = ${JSON.stringify(existingData, null, 2)};
`;
        
        // Try to use File System Access API to write directly to files
        if ('showDirectoryPicker' in window || 'showSaveFilePicker' in window) {
            try {
                let dataDirHandle = null;
                
                // Try to get stored directory handle
                const storedHandle = sessionStorage.getItem('dataDirHandle');
                if (storedHandle) {
                    try {
                        // Note: Handles can't be serialized, so we'll ask for directory each time
                        // but user can select the same directory
                        if ('showDirectoryPicker' in window) {
                            dataDirHandle = await window.showDirectoryPicker();
                            console.log('Got directory handle');
                        }
                    } catch (e) {
                        console.log('Could not reuse directory handle');
                    }
                }
                
                // Get directory permission (user selects data folder)
                if (!dataDirHandle && 'showDirectoryPicker' in window) {
                    dataDirHandle = await window.showDirectoryPicker();
                    console.log('Directory selected:', dataDirHandle.name);
                }
                
                if (dataDirHandle) {
                    // Write visitors.json
                    const jsonFileHandle = await dataDirHandle.getFileHandle('visitors.json', { create: true });
                    const jsonWritable = await jsonFileHandle.createWritable();
                    await jsonWritable.write(jsonContent);
                    await jsonWritable.close();
                    console.log('✓ visitors.json saved directly to data folder');
                    
                    // Write visitors.js
                    const jsFileHandle = await dataDirHandle.getFileHandle('visitors.js', { create: true });
                    const jsWritable = await jsFileHandle.createWritable();
                    await jsWritable.write(jsContent);
                    await jsWritable.close();
                    console.log('✓ visitors.js saved directly to data folder');
                    
                    alert(`✓ Data saved! Files updated in data folder.\n\nTotal visitors: ${existingData.length}`);
                    return visitorData;
                }
                
                // Fallback: Use showSaveFilePicker if directory picker not available
                if ('showSaveFilePicker' in window) {
                    // Request permission to save visitors.json
                    const jsonHandle = await window.showSaveFilePicker({
                        suggestedName: 'visitors.json',
                        types: [{
                            description: 'JSON files',
                            accept: { 'application/json': ['.json'] }
                        }]
                    });
                    
                    const jsonWritable = await jsonHandle.createWritable();
                    await jsonWritable.write(jsonContent);
                    await jsonWritable.close();
                    console.log('✓ visitors.json saved directly to file system');
                    
                    // Request permission to save visitors.js
                    const jsHandle = await window.showSaveFilePicker({
                        suggestedName: 'visitors.js',
                        types: [{
                            description: 'JavaScript files',
                            accept: { 'application/javascript': ['.js'] }
                        }]
                    });
                    
                    const jsWritable = await jsHandle.createWritable();
                    await jsWritable.write(jsContent);
                    await jsWritable.close();
                    console.log('✓ visitors.js saved directly to file system');
                    
                    alert(`✓ Data saved! Files updated.\n\nTotal visitors: ${existingData.length}`);
                    return visitorData;
                }
            } catch (error) {
                if (error.name === 'AbortError') {
                    console.log('User cancelled file save, falling back to download');
                    // Fall through to download method
                } else {
                    console.warn('File System Access API failed, falling back to download:', error);
                    // Fall through to download method
                }
            }
        }
        
        // Fallback: Auto-download files if File System Access API is not available
        const jsonBlob = new Blob([jsonContent], { type: 'application/json' });
        const jsonUrl = URL.createObjectURL(jsonBlob);
        const jsBlob = new Blob([jsContent], { type: 'application/javascript' });
        const jsUrl = URL.createObjectURL(jsBlob);
        
        // Auto-download visitors.json file
        const downloadJSON = () => {
            const a = document.createElement('a');
            a.href = jsonUrl;
            a.download = 'visitors.json';
            a.style.display = 'none';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            setTimeout(() => URL.revokeObjectURL(jsonUrl), 100);
        };
        
        // Auto-download visitors.js file
        const downloadJS = () => {
            setTimeout(() => {
                const a = document.createElement('a');
                a.href = jsUrl;
                a.download = 'visitors.js';
                a.style.display = 'none';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                setTimeout(() => URL.revokeObjectURL(jsUrl), 100);
            }, 200);
        };
        
        // Download JSON first, then JS
        downloadJSON();
        downloadJS();
        
        console.log('✓ Visitor information prepared for download:', visitorData);
        console.log('✓ Total visitors:', existingData.length);
        console.log('✓ Files downloaded. Please save them to:');
        console.log('  - C:\\Project\\chaitanyaresort.com\\data\\visitors.json');
        console.log('  - C:\\Project\\chaitanyaresort.com\\data\\visitors.js');
        
        // Show instruction
        alert(`Data saved! Files downloaded.\n\nPlease save:\n1. visitors.json → C:\\Project\\chaitanyaresort.com\\data\\visitors.json\n2. visitors.js → C:\\Project\\chaitanyaresort.com\\data\\visitors.js\n\nTotal visitors: ${existingData.length}`);
        
        return visitorData;
    } catch (error) {
        console.error('Error saving to visitors.json:', error);
        console.error('Error details:', error.message, error.stack);
        return null;
    }
}

// Helper function to load visitors.js
function loadVisitorsJS() {
    return new Promise((resolve, reject) => {
        if (window.visitorsData && Array.isArray(window.visitorsData)) {
            resolve(window.visitorsData);
            return;
        }
        
        const script = document.createElement('script');
        script.src = 'data/visitors.js';
        script.onload = () => {
            if (window.visitorsData && Array.isArray(window.visitorsData)) {
                resolve(window.visitorsData);
            } else {
                reject(new Error('visitors.js loaded but window.visitorsData not found'));
            }
        };
        script.onerror = () => {
            // File doesn't exist yet, that's okay
            window.visitorsData = [];
            resolve([]);
        };
        document.head.appendChild(script);
    });
}

// Fallback function to save to localStorage (only used as last resort)
function saveToLocalStorage(visitorData) {
    console.log('saveToLocalStorage called with:', visitorData);
    try {
        if (typeof(Storage) !== "undefined" && localStorage) {
            console.log('localStorage is available');
            const stored = localStorage.getItem('visitorInfo');
            console.log('Existing stored data:', stored);
            const existingData = stored ? JSON.parse(stored) : [];
            console.log('Parsed existing data:', existingData);
            existingData.push(visitorData);
            console.log('New data array:', existingData);
    localStorage.setItem('visitorInfo', JSON.stringify(existingData));
            console.log('✓ Visitor information saved to localStorage:', visitorData);
            console.log('✓ Total visitors in localStorage:', existingData.length);
    
            // Verify it was saved
            const verify = localStorage.getItem('visitorInfo');
            console.log('Verification - data in localStorage:', verify);
            
    return visitorData;
        } else {
            console.error('localStorage is not available');
            return null;
        }
    } catch (error) {
        console.error('Error saving to localStorage:', error);
        console.error('Error details:', error.message, error.stack);
        return null;
    }
}

// Function to export/download visitor data as JSON file
function downloadVisitorDataJSON() {
    try {
        const stored = localStorage.getItem('visitorInfo');
        const data = stored ? JSON.parse(stored) : [];
        
        if (data.length === 0) {
            alert('No visitor data to export. / निर्यात करण्यासाठी कोणतीही माहिती नाही.');
            return;
        }
        
        // Create JSON string with pretty formatting
        const jsonString = JSON.stringify(data, null, 2);
        
        // Create blob and download
        const blob = new Blob([jsonString], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'visitors.json';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        
        console.log('Visitor data exported:', data.length, 'entries');
        alert(`Exported ${data.length} visitor entries to visitors.json / ${data.length} प्रवेश निर्यात केले`);
    } catch (error) {
        console.error('Error exporting visitor data:', error);
        alert('Error exporting data. / डेटा निर्यात करताना त्रुटी.');
    }
}

// Make download function available globally
window.downloadVisitorDataJSON = downloadVisitorDataJSON;

// Handle visitor form submission
document.addEventListener('DOMContentLoaded', () => {
    const visitorForm = document.getElementById('visitorForm');
    const visitorModal = document.getElementById('visitorModal');
    
    if (visitorForm) {
        // Handle form submission
        visitorForm.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            handleFormSubmission();
        });
        
        // Also handle button click for mobile devices
        const submitButton = visitorForm.querySelector('button[type="submit"]');
        if (submitButton) {
            submitButton.addEventListener('click', function(e) {
                // Only handle if form validation passes
                if (visitorForm.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                    handleFormSubmission();
                }
            });
        }
    }
    
    async function handleFormSubmission() {
        const nameInput = document.getElementById('visitorName');
        const contactInput = document.getElementById('visitorContact');
        const submitButton = visitorForm.querySelector('button[type="submit"]');
        
        if (!nameInput || !contactInput) {
            console.error('Form inputs not found');
            alert('Error: Form inputs not found / त्रुटी: फॉर्म इनपुट सापडले नाहीत');
            return;
        }
        
        const name = nameInput.value.trim();
        const contact = contactInput.value.trim();
            
            // Validate
            if (!name || !contact) {
                alert('Please fill in all fields / कृपया सर्व फील्ड भरा');
            nameInput.focus();
                return;
            }
            
            if (contact.length !== 10 || !/^\d+$/.test(contact)) {
                alert('Please enter a valid 10-digit contact number / कृपया वैध 10-अंकी संपर्क क्रमांक प्रविष्ट करा');
            contactInput.focus();
                return;
            }
            
        // Disable submit button to prevent double submission
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Saving... / सेव्ह करत आहे...';
        }
        
        try {
            // Save visitor information (async)
            const saved = await saveVisitorInfo(name, contact);
            
            if (saved) {
                // Clear form
                nameInput.value = '';
                contactInput.value = '';
            
            // Hide modal
            hideVisitorModal();
            
            // Show thank you message
            alert('Thank you for your information! / आपल्या माहितीसाठी धन्यवाद!');
            } else {
                alert('Error saving information. Please try again. / माहिती सेव्ह करताना त्रुटी. कृपया पुन्हा प्रयत्न करा.');
            }
        } catch (error) {
            console.error('Error in form submission:', error);
            // Show user-friendly error message
            const errorMessage = error.message || 'Error saving information. Please try again. / माहिती सेव्ह करताना त्रुटी. कृपया पुन्हा प्रयत्न करा.';
            alert(errorMessage);
        } finally {
            // Re-enable submit button
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.textContent = 'Submit / सबमिट करा';
            }
        }
    }
    
    // Show modal after a short delay (1 second) on page load
    setTimeout(() => {
        showVisitorModal();
    }, 1000);
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
