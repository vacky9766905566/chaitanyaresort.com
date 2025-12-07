// Authentication JavaScript

let isSignUp = false;

// Open sign in modal
document.getElementById('signInBtn')?.addEventListener('click', (e) => {
    e.preventDefault();
    isSignUp = false;
    openAuthModal('Sign In');
});

// Open sign up modal
document.getElementById('signUpBtn')?.addEventListener('click', (e) => {
    e.preventDefault();
    isSignUp = true;
    openAuthModal('Sign Up');
});

// Login prompt from booking page
document.getElementById('loginPrompt')?.addEventListener('click', (e) => {
    e.preventDefault();
    isSignUp = false;
    openAuthModal('Sign In');
});

document.getElementById('signupPrompt')?.addEventListener('click', (e) => {
    e.preventDefault();
    isSignUp = true;
    openAuthModal('Sign Up');
});

// Logout
document.getElementById('logoutBtn')?.addEventListener('click', (e) => {
    e.preventDefault();
    logout();
});

function openAuthModal(title) {
    const modal = document.getElementById('authModal');
    const modalTitle = document.getElementById('authModalTitle');
    const nameGroup = document.getElementById('authNameGroup');
    const phoneGroup = document.getElementById('authPhoneGroup');
    const authToggle = document.getElementById('authToggle');
    const form = document.getElementById('authForm');
    
    modalTitle.textContent = title;
    modal.classList.add('active');
    
    if (isSignUp) {
        nameGroup.style.display = 'block';
        phoneGroup.style.display = 'block';
        document.getElementById('authName').required = true;
        document.getElementById('authPhone').required = true;
        authToggle.innerHTML = 'Already have an account? <a href="#" id="switchToLogin">Sign In</a>';
    } else {
        nameGroup.style.display = 'none';
        phoneGroup.style.display = 'none';
        document.getElementById('authName').required = false;
        document.getElementById('authPhone').required = false;
        authToggle.innerHTML = 'Don\'t have an account? <a href="#" id="switchToSignup">Sign Up</a>';
    }
    
    // Switch between sign in and sign up
    document.getElementById('switchToLogin')?.addEventListener('click', (e) => {
        e.preventDefault();
        isSignUp = false;
        openAuthModal('Sign In');
    });
    
    document.getElementById('switchToSignup')?.addEventListener('click', (e) => {
        e.preventDefault();
        isSignUp = true;
        openAuthModal('Sign Up');
    });
}

function closeAuthModal() {
    const modal = document.getElementById('authModal');
    modal.classList.remove('active');
    document.getElementById('authForm').reset();
}

// Close modal on outside click
document.getElementById('authModal')?.addEventListener('click', (e) => {
    if (e.target.id === 'authModal') {
        closeAuthModal();
    }
});

// Handle form submission
document.getElementById('authForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    formData.append('action', isSignUp ? 'register' : 'login');
    
    try {
        const response = await fetch('api/auth.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            closeAuthModal();
            // Reload page to update navigation
            window.location.reload();
        } else {
            alert(result.message || 'An error occurred');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    }
});

async function logout() {
    try {
        const formData = new FormData();
        formData.append('action', 'logout');
        
        const response = await fetch('api/auth.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            window.location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

