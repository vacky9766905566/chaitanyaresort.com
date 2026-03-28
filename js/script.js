(function () {
  'use strict';

  var yearEl = document.getElementById('footer-year');
  if (yearEl) yearEl.textContent = new Date().getFullYear();

  var hamburger = document.getElementById('hamburger');
  var navMenu = document.getElementById('navMenu');
  if (hamburger && navMenu) {
    hamburger.addEventListener('click', function () {
      hamburger.classList.toggle('active');
      navMenu.classList.toggle('active');
    });
    navMenu.querySelectorAll('a').forEach(function (a) {
      a.addEventListener('click', function () {
        hamburger.classList.remove('active');
        navMenu.classList.remove('active');
      });
    });
  }

  var galleryModal = document.getElementById('galleryModal');
  var modalImage = document.getElementById('modalImage');
  var modalCaption = document.querySelector('.modal-caption');
  var closeModal = document.querySelector('.close-modal');
  document.querySelectorAll('.gallery-item').forEach(function (item) {
    item.addEventListener('click', function () {
      var img = item.querySelector('img');
      if (!img || !galleryModal || !modalImage) return;
      modalImage.src = img.src;
      modalImage.alt = img.alt || '';
      if (modalCaption) modalCaption.textContent = img.alt || '';
      galleryModal.classList.add('active');
    });
  });
  if (closeModal && galleryModal) {
    closeModal.addEventListener('click', function () {
      galleryModal.classList.remove('active');
    });
    galleryModal.addEventListener('click', function (e) {
      if (e.target === galleryModal) galleryModal.classList.remove('active');
    });
  }

  var videoModal = document.getElementById('videoModal');
  var modalVideo = document.getElementById('modalVideo');
  var closeVideo = document.querySelector('.close-video-modal');
  document.querySelectorAll('.video-clickable').forEach(function (wrap) {
    wrap.addEventListener('click', function (e) {
      var src = wrap.getAttribute('data-video-src');
      var source = wrap.querySelector('video source');
      var url = src || (source && source.getAttribute('src'));
      if (!url || !videoModal || !modalVideo) return;
      e.preventDefault();
      modalVideo.innerHTML = '<source src="' + url + '" type="video/mp4">';
      modalVideo.load();
      videoModal.classList.add('active');
      modalVideo.play().catch(function () {});
    });
  });
  if (closeVideo && videoModal && modalVideo) {
    closeVideo.addEventListener('click', function () {
      modalVideo.pause();
      videoModal.classList.remove('active');
    });
    videoModal.addEventListener('click', function (e) {
      if (e.target === videoModal) {
        modalVideo.pause();
        videoModal.classList.remove('active');
      }
    });
  }

  var visitorModal = document.getElementById('visitorModal');
  var visitorForm = document.getElementById('visitorForm');
  if (visitorModal && visitorForm) {
    var vKey = 'chaitanya_visitor_ok';
    try {
      if (!localStorage.getItem(vKey)) visitorModal.classList.add('active');
    } catch (err) {
      visitorModal.classList.add('active');
    }
    visitorForm.addEventListener('submit', function (e) {
      e.preventDefault();
      try {
        localStorage.setItem(vKey, '1');
      } catch (err2) {}
      visitorModal.classList.remove('active');
    });
  }

  var feedbackForm = document.getElementById('feedbackForm');
  if (feedbackForm) {
    feedbackForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var spinner = document.getElementById('feedbackSpinner');
      var btn = feedbackForm.querySelector('button[type="submit"]');
      if (spinner) spinner.style.display = 'inline-block';
      if (btn) btn.classList.add('loading');
      window.setTimeout(function () {
        if (spinner) spinner.style.display = 'none';
        if (btn) btn.classList.remove('loading');
        alert('धन्यवाद! आपला संदेश प्राप्त झाला. लवकरच संपर्क साधू.');
        feedbackForm.reset();
      }, 600);
    });
  }

  document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') return;
    if (galleryModal && galleryModal.classList.contains('active')) {
      galleryModal.classList.remove('active');
    }
    if (videoModal && videoModal.classList.contains('active') && modalVideo) {
      modalVideo.pause();
      videoModal.classList.remove('active');
    }
  });
})();
