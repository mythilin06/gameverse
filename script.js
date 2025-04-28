document.addEventListener('DOMContentLoaded', function() {
    const cookieConsent = document.getElementById('cookieConsent');
    const acceptCookiesBtn = document.getElementById('acceptCookies');
    if (!localStorage.getItem('cookiesAccepted')) {
        const toast = new bootstrap.Toast(cookieConsent);
        toast.show();
    }
    if (acceptCookiesBtn) {
        acceptCookiesBtn.addEventListener('click', function() {
            localStorage.setItem('cookiesAccepted', 'true');
            const toast = bootstrap.Toast.getInstance(cookieConsent);
            toast.hide();
        });
    }
    const ratingStars = document.querySelectorAll('.star-rating .rating-star');
    if (ratingStars.length > 0) {
        ratingStars.forEach(star => {
            star.addEventListener('click', function() {
                const value = this.getAttribute('data-value');
                document.getElementById('rating-input').value = value;
                ratingStars.forEach(s => {
                    const starValue = s.getAttribute('data-value');
                    if (starValue <= value) {
                        s.classList.remove('inactive');
                        s.classList.add('active');
                    } else {
                        s.classList.remove('active');
                        s.classList.add('inactive');
                    }
                });
            });
        });
    }
    
    const forms = document.querySelectorAll('.needs-validation');
    
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    });
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    const gameFilter = document.getElementById('game-filter');
    if (gameFilter) {
        gameFilter.addEventListener('input', function() {
            const filterValue = this.value.toLowerCase();
            const gameCards = document.querySelectorAll('.game-item');
            
            gameCards.forEach(card => {
                const title = card.querySelector('.game-title').textContent.toLowerCase();
                const visible = title.includes(filterValue);
                card.style.display = visible ? '' : 'none';
            });
        });
    }
    // Also display a success message banner at the top of the page
window.scrollTo(0, 0); // Scroll to top to ensure user sees the message
});