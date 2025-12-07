<?php
$page_title = 'Beach Paradise';
require_once 'includes/header.php';
?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="hero-title">Welcome to Chaitanya Resort</h1>
            <p class="hero-subtitle">Your Beach Paradise Awaits</p>
        </div>
    </section>

    <!-- Booking Section -->
    <section class="booking-section" style="padding: 80px 0; background: var(--white);">
        <div class="container">
            <?php if (!isLoggedIn()): ?>
                <div style="background: #fff3cd; border: 1px solid #ffc107; padding: 1rem; border-radius: 5px; margin-bottom: 2rem; text-align: center;">
                    <p style="margin: 0;">Please <a href="#" id="loginPrompt" style="color: var(--primary-color); font-weight: bold;">Sign In</a> or <a href="#" id="signupPrompt" style="color: var(--primary-color); font-weight: bold;">Sign Up</a> to make a booking.</p>
                </div>
            <?php endif; ?>

            <?php
            require_once 'config/database.php';
            $conn = getDBConnection();
            $result = $conn->query("SELECT * FROM rooms WHERE is_active = 1 ORDER BY room_number");
            $rooms = [];
            while ($row = $result->fetch_assoc()) {
                $rooms[] = $row;
            }
            closeDBConnection($conn);
            ?>

            <div class="booking-wrapper" style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem;">
                <!-- Booking Form -->
                <div class="booking-form-wrapper" style="background: var(--light-bg); padding: 2.5rem; border-radius: 10px; box-shadow: 0 5px 15px var(--shadow);">
                    <h2 style="color: var(--primary-color); margin-bottom: 2rem;">Booking Details</h2>
                    <form id="bookingForm">
                        <div class="form-group">
                            <label for="check_in">Check-in Date</label>
                            <input type="date" id="check_in" name="check_in" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div class="form-group">
                            <label for="check_out">Check-out Date</label>
                            <input type="date" id="check_out" name="check_out" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                        </div>
                        <div class="form-group">
                            <label for="room_id">Select Room</label>
                            <select id="room_id" name="room_id" required>
                                <option value="">-- Select Room --</option>
                                <?php foreach ($rooms as $room): ?>
                                    <option value="<?php echo $room['id']; ?>" 
                                            data-price="<?php echo $room['price_per_day']; ?>"
                                            data-extra-bed="<?php echo $room['extra_bed_price']; ?>">
                                        <?php echo htmlspecialchars($room['room_name'] . ' (₹' . $room['price_per_day'] . '/day)'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="number_of_guests">Number of Guests</label>
                            <input type="number" id="number_of_guests" name="number_of_guests" min="1" max="4" value="2" required>
                        </div>
                        <div class="form-group">
                            <label for="extra_beds">Extra Beds</label>
                            <input type="number" id="extra_beds" name="extra_beds" min="0" max="2" value="0">
                            <small style="color: var(--text-light);">₹300 per bed per day</small>
                        </div>
                        <div class="form-group">
                            <label for="guest_name">Guest Name</label>
                            <input type="text" id="guest_name" name="guest_name" required value="<?php echo isLoggedIn() ? htmlspecialchars($current_user['name']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="guest_email">Guest Email</label>
                            <input type="email" id="guest_email" name="guest_email" required value="<?php echo isLoggedIn() ? htmlspecialchars($current_user['email']) : ''; ?>">
                        </div>
                        <div class="form-group">
                            <label for="guest_phone">Guest Phone</label>
                            <input type="tel" id="guest_phone" name="guest_phone" required>
                        </div>
                        <div id="priceSummary" style="background: var(--white); padding: 1.5rem; border-radius: 5px; margin-bottom: 1.5rem; display: none;">
                            <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Price Summary</h3>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span>Room Price:</span>
                                <span id="roomPriceDisplay">₹0</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                                <span>Extra Beds:</span>
                                <span id="extraBedPriceDisplay">₹0</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 1.2rem; padding-top: 1rem; border-top: 2px solid var(--primary-color);">
                                <span>Total:</span>
                                <span id="totalPriceDisplay">₹0</span>
                            </div>
                        </div>
                        <div id="availabilityStatus" style="margin-bottom: 1.5rem;"></div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;" <?php echo !isLoggedIn() ? 'disabled' : ''; ?>>Book Now</button>
                    </form>
                </div>

                <!-- Booking Info -->
                <div class="booking-info-sidebar">
                    <div style="background: var(--light-bg); padding: 2.5rem; border-radius: 10px; box-shadow: 0 5px 15px var(--shadow); margin-bottom: 2rem;">
                        <h2 style="color: var(--primary-color); margin-bottom: 1.5rem;">Booking Information</h2>
                        <div style="margin-bottom: 1.5rem;">
                            <h3 style="color: var(--primary-color); margin-bottom: 1rem; font-size: 1.2rem;">🕐 Check-in & Check-out</h3>
                            <p><strong>Check-in:</strong> 2:00 PM</p>
                            <p><strong>Check-out:</strong> 12:00 PM</p>
                        </div>
                        <div style="margin-bottom: 1.5rem;">
                            <h3 style="color: var(--primary-color); margin-bottom: 1rem; font-size: 1.2rem;">💰 Pricing</h3>
                            <p><strong>Room Rate:</strong> ₹2,000/day (Double occupancy)</p>
                            <p><strong>Extra Bed:</strong> ₹300/day</p>
                        </div>
                        <div style="margin-bottom: 1.5rem;">
                            <h3 style="color: var(--primary-color); margin-bottom: 1rem; font-size: 1.2rem;">🏨 Room Features</h3>
                            <p>✓ Attached Bathroom</p>
                            <p>✓ 6 Rooms Available</p>
                            <p>✗ No AC (All rooms)</p>
                        </div>
                        <div>
                            <h3 style="color: var(--primary-color); margin-bottom: 1rem; font-size: 1.2rem;">🍽️ Restaurant</h3>
                            <p>Restaurant bill is separate and payable at the hotel.</p>
                            <p style="font-size: 0.9rem; color: var(--text-light); margin-top: 0.5rem;">
                                <strong>Note:</strong> If you want us to prepare fish for you, please purchase fish from Dapoli fish market and bring it to us.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        @media (max-width: 968px) {
            .booking-wrapper {
                grid-template-columns: 1fr !important;
            }
        }
    </style>

    <script src="js/booking.js"></script>

<?php require_once 'includes/footer.php'; ?>
