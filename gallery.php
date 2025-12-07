<?php
$page_title = 'Gallery';
require_once 'includes/header.php';
?>

    <!-- Page Header -->
    <section class="page-header">
        <div class="page-header-overlay"></div>
        <div class="page-header-content">
            <h1>Gallery</h1>
            <p>Explore Our Beautiful Resort</p>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="gallery-section" style="padding: 80px 0; background: var(--light-bg);">
        <div class="container">
            <div class="gallery-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                <div class="gallery-item" style="position: relative; overflow: hidden; border-radius: 10px; box-shadow: 0 5px 15px var(--shadow); cursor: pointer;">
                    <img src="assets/images/resort-exterior.jpg" alt="Resort Exterior" style="width: 100%; height: 300px; object-fit: cover; transition: transform 0.3s ease;">
                </div>
                <div class="gallery-item" style="position: relative; overflow: hidden; border-radius: 10px; box-shadow: 0 5px 15px var(--shadow); cursor: pointer;">
                    <img src="assets/images/beach-hero.jpg" alt="Beach View" style="width: 100%; height: 300px; object-fit: cover; transition: transform 0.3s ease;">
                </div>
                <div class="gallery-item" style="position: relative; overflow: hidden; border-radius: 10px; box-shadow: 0 5px 15px var(--shadow); cursor: pointer;">
                    <img src="assets/images/beachfront-room.jpg" alt="Beachfront Room" style="width: 100%; height: 300px; object-fit: cover; transition: transform 0.3s ease;">
                </div>
                <div class="gallery-item" style="position: relative; overflow: hidden; border-radius: 10px; box-shadow: 0 5px 15px var(--shadow); cursor: pointer;">
                    <img src="assets/images/ocean-view-suite.jpg" alt="Ocean View Suite" style="width: 100%; height: 300px; object-fit: cover; transition: transform 0.3s ease;">
                </div>
                <div class="gallery-item" style="position: relative; overflow: hidden; border-radius: 10px; box-shadow: 0 5px 15px var(--shadow); cursor: pointer;">
                    <img src="assets/images/family-suite.jpg" alt="Family Suite" style="width: 100%; height: 300px; object-fit: cover; transition: transform 0.3s ease;">
                </div>
                <div class="gallery-item" style="position: relative; overflow: hidden; border-radius: 10px; box-shadow: 0 5px 15px var(--shadow); cursor: pointer;">
                    <img src="assets/images/garden-view-room.jpg" alt="Garden View Room" style="width: 100%; height: 300px; object-fit: cover; transition: transform 0.3s ease;">
                </div>
                <div class="gallery-item" style="position: relative; overflow: hidden; border-radius: 10px; box-shadow: 0 5px 15px var(--shadow); cursor: pointer;">
                    <img src="assets/images/beach-amenity.jpg" alt="Beach Amenity" style="width: 100%; height: 300px; object-fit: cover; transition: transform 0.3s ease;">
                </div>
                <div class="gallery-item" style="position: relative; overflow: hidden; border-radius: 10px; box-shadow: 0 5px 15px var(--shadow); cursor: pointer;">
                    <img src="assets/images/pool-amenity.jpg" alt="Pool Amenity" style="width: 100%; height: 300px; object-fit: cover; transition: transform 0.3s ease;">
                </div>
                <div class="gallery-item" style="position: relative; overflow: hidden; border-radius: 10px; box-shadow: 0 5px 15px var(--shadow); cursor: pointer;">
                    <img src="assets/images/restaurant-interior.jpg" alt="Restaurant Interior" style="width: 100%; height: 300px; object-fit: cover; transition: transform 0.3s ease;">
                </div>
            </div>
        </div>
    </section>

<?php require_once 'includes/footer.php'; ?>
