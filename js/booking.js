// Booking JavaScript

const checkInInput = document.getElementById('check_in');
const checkOutInput = document.getElementById('check_out');
const roomSelect = document.getElementById('room_id');
const extraBedsInput = document.getElementById('extra_beds');
const bookingForm = document.getElementById('bookingForm');
const priceSummary = document.getElementById('priceSummary');
const availabilityStatus = document.getElementById('availabilityStatus');

// Update check-out min date when check-in changes
checkInInput?.addEventListener('change', function() {
    if (this.value) {
        const minDate = new Date(this.value);
        minDate.setDate(minDate.getDate() + 1);
        checkOutInput.min = minDate.toISOString().split('T')[0];
        
        if (checkOutInput.value && checkOutInput.value <= this.value) {
            checkOutInput.value = '';
        }
        
        checkAvailability();
    }
});

// Update check-in max date when check-out changes
checkOutInput?.addEventListener('change', function() {
    if (this.value && checkInInput.value) {
        checkAvailability();
        calculatePrice();
    }
});

// Calculate price when inputs change
roomSelect?.addEventListener('change', function() {
    calculatePrice();
    if (checkInInput.value && checkOutInput.value) {
        checkAvailability();
    }
});

extraBedsInput?.addEventListener('input', calculatePrice);

function calculatePrice() {
    if (!roomSelect.value || !checkInInput.value || !checkOutInput.value) {
        priceSummary.style.display = 'none';
        return;
    }
    
    const selectedOption = roomSelect.options[roomSelect.selectedIndex];
    const pricePerDay = parseFloat(selectedOption.dataset.price);
    const extraBedPrice = parseFloat(selectedOption.dataset.extraBed);
    
    const checkIn = new Date(checkInInput.value);
    const checkOut = new Date(checkOutInput.value);
    const days = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
    
    if (days <= 0) {
        priceSummary.style.display = 'none';
        return;
    }
    
    const extraBeds = parseInt(extraBedsInput.value) || 0;
    const roomPrice = pricePerDay * days;
    const extraBedTotal = extraBedPrice * extraBeds * days;
    const total = roomPrice + extraBedTotal;
    
    document.getElementById('roomPriceDisplay').textContent = `₹${roomPrice.toLocaleString()}`;
    document.getElementById('extraBedPriceDisplay').textContent = `₹${extraBedTotal.toLocaleString()}`;
    document.getElementById('totalPriceDisplay').textContent = `₹${total.toLocaleString()}`;
    
    priceSummary.style.display = 'block';
}

async function checkAvailability() {
    if (!checkInInput.value || !checkOutInput.value || !roomSelect.value) {
        return;
    }
    
    const formData = new FormData();
    formData.append('check_in', checkInInput.value);
    formData.append('check_out', checkOutInput.value);
    formData.append('room_id', roomSelect.value);
    
    try {
        const response = await fetch('api/check_availability.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success && result.rooms.length > 0) {
            const room = result.rooms[0];
            if (room.is_available == 1) {
                availabilityStatus.innerHTML = '<div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 5px; border: 1px solid #c3e6cb;">✓ Room is available for selected dates</div>';
            } else {
                availabilityStatus.innerHTML = '<div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 5px; border: 1px solid #f5c6cb;">✗ Room is not available for selected dates. Please select different dates.</div>';
            }
        }
    } catch (error) {
        console.error('Error checking availability:', error);
    }
}

// Handle form submission
bookingForm?.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(bookingForm);
    formData.append('action', 'create');
    
    // Show loading
    const submitBtn = bookingForm.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Processing...';
    submitBtn.disabled = true;
    
    try {
        const response = await fetch('api/booking.php', {
            method: 'POST',
            body: formData
        });
        
        // Check if response is ok
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        // Get response text first to check if it's valid JSON
        const responseText = await response.text();
        let result;
        
        try {
            result = JSON.parse(responseText);
        } catch (parseError) {
            console.error('JSON Parse Error:', parseError);
            console.error('Response Text:', responseText);
            throw new Error('Invalid JSON response from server');
        }
        
        if (result.success) {
            // Show success message and redirect to payment
            alert(`Booking confirmed! Booking ID: ${result.booking_id}\nTotal Amount: ₹${result.total_price}\nRedirecting to payment...`);
            window.location.href = result.payment_link;
        } else {
            alert(result.message || 'Booking failed. Please try again.');
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }
    } catch (error) {
        console.error('Error:', error);
        console.error('Error details:', error.message);
        alert('An error occurred: ' + error.message + '. Please check the console for details.');
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }
});

