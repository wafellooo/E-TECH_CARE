document.addEventListener('DOMContentLoaded', function () {

    // ── NAVBAR SCROLL ACTION
    const navbar = document.getElementById('navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 40);
        });
    }

    // ── MOBILE HAMBURGER MENU
    const hamburger = document.getElementById('hamburger');
    const navLinks  = document.getElementById('navLinks');

    if (hamburger && navLinks) {
        hamburger.addEventListener('click', () => {
            hamburger.classList.toggle('open');
            navLinks.classList.toggle('open');
        });

        navLinks.querySelectorAll('a').forEach(a => {
            a.addEventListener('click', () => {
                hamburger.classList.remove('open');
                navLinks.classList.remove('open');
            });
        });
    }

    // ── MODAL CONTROLLER
    const modal      = document.getElementById('loginModal');
    const modalCard  = document.getElementById('modalCard');
    const openBtns   = [
        document.getElementById('openLoginModal'),
        document.getElementById('heroSignIn'),
        document.getElementById('ctaSignIn'),
    ];
    const closeBtn = document.getElementById('closeModal');

    function openModal() {
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                const emailInput = document.getElementById('email');
                if (emailInput) emailInput.focus();
            }, 200);
        }
    }

    function closeModal() {
        if (modalCard && modal) {
            modalCard.classList.add('closing');
            setTimeout(() => {
                modal.classList.remove('active');
                modalCard.classList.remove('closing');
                document.body.style.overflow = '';
            }, 280);
        }
    }

    openBtns.forEach(btn => {
        if (btn) btn.addEventListener('click', openModal);
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', closeModal);
    }

    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal && modal.classList.contains('active')) {
            closeModal();
        }
    });

    // ── AUTO-OPEN ON LARAVEL ERRORS
    if (modal && modal.getAttribute('data-auto-open') === 'true') {
        openModal();
    }

    // ── PASSWORD VISIBILITY TOGGLE
    const togglePw   = document.getElementById('togglePw');
    const pwInput    = document.getElementById('password');
    const pwEyeIcon  = document.getElementById('pwEyeIcon');

    if (togglePw && pwInput && pwEyeIcon) {
        togglePw.addEventListener('click', () => {
            const isText = pwInput.type === 'text';
            pwInput.type = isText ? 'password' : 'text';
            pwEyeIcon.classList.toggle('fa-eye', isText);
            pwEyeIcon.classList.toggle('fa-eye-slash', !isText);
        });
    }

    // ── FADE-UP ON SCROLL ANIMATIONS
    const fadeEls = document.querySelectorAll('.fade-up');
    if (fadeEls.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, i) => {
                if (entry.isIntersecting) {
                    setTimeout(() => entry.target.classList.add('visible'), i * 80);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        fadeEls.forEach(el => observer.observe(el));
    }

});