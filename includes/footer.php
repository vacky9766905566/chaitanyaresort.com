    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Chaitanya Resort</h3>
                    <p>Your beach paradise destination for luxury, comfort, and unforgettable memories.</p>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="gallery.php">Gallery</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contact Info</h4>
                    <p>📍 Chaitanya resort, Palande beach, Dapoli, Ratnagiri District, Maharashtra.</p>
                    <p>📞 +91-9112680201</p>
                    <p>✉️ info@chaitanyaresort.com</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Chaitanya Resort. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Floating Buttons -->
    <div class="floating-buttons">
        <a href="https://wa.me/919112680201" target="_blank" class="floating-btn floating-btn-whatsapp" title="WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </a>
        <a href="https://maps.app.goo.gl/n8ZPq3inbxDyt36u8" target="_blank" class="floating-btn floating-btn-map" title="Google Maps">
            <i class="fas fa-map-marker-alt"></i>
        </a>
    </div>

    <!-- Auth Modals -->
    <div id="authModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="authModalTitle">Sign In</h2>
                <button class="close-modal" onclick="closeAuthModal()">&times;</button>
            </div>
            <form id="authForm">
                <div class="form-group">
                    <label for="authEmail">Email</label>
                    <input type="email" id="authEmail" name="email" required>
                </div>
                <div class="form-group">
                    <label for="authPassword">Password</label>
                    <input type="password" id="authPassword" name="password" required>
                </div>
                <div class="form-group" id="authNameGroup" style="display: none;">
                    <label for="authName">Name</label>
                    <input type="text" id="authName" name="name">
                </div>
                <div class="form-group" id="authPhoneGroup" style="display: none;">
                    <label for="authPhone">Phone</label>
                    <input type="text" id="authPhone" name="phone" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">Submit</button>
                <p id="authToggle" style="text-align: center; margin-top: 1rem; color: var(--text-light); cursor: pointer;"></p>
            </form>
        </div>
    </div>

    <script src="js/script.js"></script>
    <script src="js/auth.js"></script>
</body>
</html>

