    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>चैतन्य रिसॉर्ट</h3>
                    <p>पालंदे बीच येथे लक्झरी, आराम आणि अविस्मरणीय आठवणींसाठी आपले बीच पॅराडाईज गंतव्यस्थान.</p>
                    <div class="social-links">
                        <a href="https://wa.me/919112680201" target="_blank" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h4>द्रुत दुवे</h4>
                    <ul>
                        <li><a href="#home">मुख्यपृष्ठ</a></li>
                        <li><a href="#gallery">गॅलरी</a></li>
                        <li><a href="#rooms">खोल्या</a></li>
                        <li><a href="#amenities">सुविधा</a></li>
                        <li><a href="#information">माहिती</a></li>
                        <li><a href="#location">स्थान</a></li>
                        <li><a href="#contact">संपर्क</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>संपर्क माहिती</h4>
                    <p><i class="fas fa-map-marker-alt"></i> चैतन्य रिसॉर्ट, पालंदे बीच, दापोली-हरनाई रोड, रत्नागिरी जिल्हा, महाराष्ट्र.</p>
                    <p><i class="fas fa-phone"></i> +91-9112680201</p>
                    <p><i class="fas fa-phone"></i> +91-9421297851</p>
                    <p><i class="fas fa-phone"></i> +91-7768962339</p>
                    <p><i class="fas fa-phone"></i> +91-8390347209</p>
                    <p><i class="fas fa-envelope"></i> info@chaitanyaresort.com</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> चैतन्य रिसॉर्ट. सर्व हक्क राखीव. <div id="sfctrz9bewa7pxcp921zwqcz1z1bnl8ebe2"></div>
                    <script type="text/javascript" src="https://counter1.optistats.ovh/private/counter.js?c=trz9bewa7pxcp921zwqcz1z1bnl8ebe2&down=async" async></script>
                    <noscript><a href="https://www.freecounterstat.com" title="free hit counters"><img src="https://counter1.optistats.ovh/private/freecounterstat.php?c=trz9bewa7pxcp921zwqcz1z1bnl8ebe2" border="0" title="free hit counters" alt="free hit counters"></a></noscript>
                    </p>
            </div>
        </div>
    </footer>

    <!-- Floating Action Buttons -->
    <div class="floating-buttons">
        <a href="https://wa.me/919112680201" class="floating-btn floating-btn-whatsapp" target="_blank" aria-label="WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </a>
        <a href="https://wa.me/919421297851" class="floating-btn floating-btn-whatsapp" target="_blank" aria-label="WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </a>
        <a href="https://wa.me/917768962339" class="floating-btn floating-btn-whatsapp" target="_blank" aria-label="WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </a>
        <a href="https://wa.me/918390347209" class="floating-btn floating-btn-whatsapp" target="_blank" aria-label="WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </a>
    </div>

    <!-- Gallery Lightbox Modal -->
    <div id="galleryModal" class="gallery-modal">
        <span class="close-modal">&times;</span>
        <img class="modal-content" id="modalImage">
        <div class="modal-caption"></div>
    </div>

    <!-- Video Modal -->
    <div id="videoModal" class="video-modal">
        <span class="close-video-modal">&times;</span>
        <div class="video-modal-content">
            <video id="modalVideo" controls playsinline webkit-playsinline>
                आपला ब्राउझर व्हिडिओ टॅगला समर्थन देत नाही.
            </video>
        </div>
    </div>

    <!-- Visitor Information Modal -->
    <div id="visitorModal" class="visitor-modal">
        <div class="visitor-modal-content">
            <div class="visitor-modal-header">
                <h2>Welcome to Chaitanya Resort</h2>
                <p>कृपया आपली माहिती प्रविष्ट करा</p>
            </div>
            <form id="visitorForm">
                <div class="form-group">
                    <label for="visitorName">Name / नाव <span class="required">*</span></label>
                    <input type="text" id="visitorName" name="name" required placeholder="Enter your name" autocomplete="name" inputmode="text">
                </div>
                <div class="form-group">
                    <label for="visitorContact">Contact Number / संपर्क क्रमांक <span class="required">*</span></label>
                    <input type="tel" id="visitorContact" name="contact" required placeholder="Enter your contact number" pattern="[0-9]{10}" minlength="10" maxlength="10" autocomplete="tel" inputmode="numeric">
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Submit / सबमिट करा</button>
                </div>
            </form>
        </div>
    </div>

