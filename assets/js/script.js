// Modern JavaScript with ES6+ features and animations

class ApartmentApp {
    constructor() {
        this.init();
    }

    init() {
        this.setupAnimations();
        this.setupInteractions();
        this.setupFormValidation();
        this.setupScrollEffects();
        this.setupParticles();
    }

    // Scroll-triggered animations
    setupScrollEffects() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });
    }

    // List view interactions
    setupListView() {
        document.querySelectorAll('.apartment-list-item').forEach(item => {
            item.addEventListener('mouseenter', this.listItemHoverIn);
            item.addEventListener('mouseleave', this.listItemHoverOut);
        });
    }

    listItemHoverIn(e) {
        const item = e.currentTarget;
        const image = item.querySelector('.apartment-image');
        if (image) {
            image.style.transform = 'scale(1.05)';
        }
    }

    listItemHoverOut(e) {
        const item = e.currentTarget;
        const image = item.querySelector('.apartment-image');
        if (image) {
            image.style.transform = 'scale(1)';
        }
    }

    // Card hover animations
    setupAnimations() {
        document.querySelectorAll('.apartment-card, .apartment-list-item').forEach(card => {
            card.addEventListener('mouseenter', this.cardHoverIn);
            card.addEventListener('mouseleave', this.cardHoverOut);
        });

        // Setup list view specific animations
        this.setupListView();
        
        // Stagger animation for items
        this.staggerItems();
    }

    cardHoverIn(e) {
        const card = e.currentTarget;
        card.style.transform = 'translateY(-15px) scale(1.03)';
        card.style.boxShadow = '0 25px 50px rgba(0,0,0,0.2)';
        
        // Add ripple effect
        const ripple = document.createElement('div');
        ripple.className = 'ripple';
        ripple.style.cssText = `
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
            transform: scale(0);
            animation: ripple 0.6s linear;
            pointer-events: none;
        `;
        
        const rect = card.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = (rect.width / 2 - size / 2) + 'px';
        ripple.style.top = (rect.height / 2 - size / 2) + 'px';
        
        card.appendChild(ripple);
        setTimeout(() => ripple.remove(), 600);
    }

    cardHoverOut(e) {
        const card = e.currentTarget;
        card.style.transform = 'translateY(0) scale(1)';
        card.style.boxShadow = '0 10px 30px rgba(0,0,0,0.1)';
    }

    staggerItems() {
        document.querySelectorAll('.apartment-card, .apartment-list-item').forEach((item, index) => {
            item.style.animationDelay = `${index * 0.1}s`;
            item.classList.add('fade-in');
        });
    }

    // Interactive form elements
    setupInteractions() {
        // Floating labels
        document.querySelectorAll('.form-control, .form-select').forEach(input => {
            input.addEventListener('focus', this.inputFocus);
            input.addEventListener('blur', this.inputBlur);
        });

        // Search form enhancements
        this.setupSearchForm();
        
        // Rating interactions
        this.setupRatingSystem();
        
        // Button animations
        this.setupButtonAnimations();
    }

    inputFocus(e) {
        const input = e.target;
        input.parentElement.classList.add('focused');
        input.style.transform = 'translateY(-2px)';
    }

    inputBlur(e) {
        const input = e.target;
        if (!input.value) {
            input.parentElement.classList.remove('focused');
        }
        input.style.transform = 'translateY(0)';
    }

    setupSearchForm() {
        const searchForm = document.querySelector('.search-form');
        if (!searchForm) return;

        const inputs = searchForm.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('change', this.updateSearchPreview);
        });
    }

    updateSearchPreview() {
        const checkIn = document.querySelector('input[name="check_in"]')?.value;
        const checkOut = document.querySelector('input[name="check_out"]')?.value;
        const guests = document.querySelector('select[name="guests"]')?.value;

        if (checkIn && checkOut) {
            const nights = this.calculateNights(checkIn, checkOut);
            this.showSearchPreview(nights, guests);
        }
    }

    calculateNights(checkIn, checkOut) {
        const start = new Date(checkIn);
        const end = new Date(checkOut);
        return Math.ceil((end - start) / (1000 * 60 * 60 * 24));
    }

    showSearchPreview(nights, guests) {
        let preview = document.querySelector('.search-preview');
        if (!preview) {
            preview = document.createElement('div');
            preview.className = 'search-preview mt-3 p-3 rounded';
            preview.style.cssText = `
                background: rgba(255,255,255,0.1);
                backdrop-filter: blur(10px);
                border-radius: 15px;
                animation: slideInUp 0.3s ease;
            `;
            document.querySelector('.search-form').appendChild(preview);
        }
        
        preview.innerHTML = `
            <small class="text-white">
                <i class="fas fa-info-circle"></i> 
                ${nights} night${nights > 1 ? 's' : ''} for ${guests} guest${guests > 1 ? 's' : ''}
            </small>
        `;
    }

    setupRatingSystem() {
        document.querySelectorAll('.rating').forEach(rating => {
            const stars = rating.querySelectorAll('label');
            stars.forEach((star, index) => {
                star.addEventListener('mouseenter', () => {
                    stars.forEach((s, i) => {
                        s.style.color = i >= index ? '#ffc107' : '#ddd';
                        s.style.transform = i >= index ? 'scale(1.2)' : 'scale(1)';
                    });
                });
                
                star.addEventListener('mouseleave', () => {
                    stars.forEach(s => {
                        s.style.transform = 'scale(1)';
                    });
                });
            });
        });
    }

    setupButtonAnimations() {
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                ripple.className = 'ripple';
                
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.cssText = `
                    position: absolute;
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}px;
                    top: ${y}px;
                    background: rgba(255,255,255,0.5);
                    border-radius: 50%;
                    transform: scale(0);
                    animation: ripple 0.6s linear;
                    pointer-events: none;
                `;
                
                this.appendChild(ripple);
                setTimeout(() => ripple.remove(), 600);
            });
        });
    }

    // Form validation with animations
    setupFormValidation() {
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', this.validateForm);
        });
    }

    validateForm(e) {
        const form = e.target;
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                this.showFieldError(field);
            } else {
                this.clearFieldError(field);
            }
        });

        if (!isValid) {
            e.preventDefault();
            this.showFormError('Please fill in all required fields');
        }
    }

    showFieldError(field) {
        field.style.borderColor = '#ff6b6b';
        field.style.animation = 'shake 0.5s ease-in-out';
        
        setTimeout(() => {
            field.style.animation = '';
        }, 500);
    }

    clearFieldError(field) {
        field.style.borderColor = '';
    }

    showFormError(message) {
        const alert = document.createElement('div');
        alert.className = 'alert alert-danger';
        alert.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            animation: slideInRight 0.3s ease;
        `;
        alert.textContent = message;
        
        document.body.appendChild(alert);
        setTimeout(() => alert.remove(), 3000);
    }

    // Particle background effect
    setupParticles() {
        const canvas = document.createElement('canvas');
        canvas.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
            opacity: 0.1;
        `;
        document.body.appendChild(canvas);

        const ctx = canvas.getContext('2d');
        const particles = [];

        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }

        function createParticle() {
            return {
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height,
                vx: (Math.random() - 0.5) * 0.5,
                vy: (Math.random() - 0.5) * 0.5,
                size: Math.random() * 2 + 1
            };
        }

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            particles.forEach((particle, index) => {
                particle.x += particle.vx;
                particle.y += particle.vy;
                
                if (particle.x < 0 || particle.x > canvas.width) particle.vx *= -1;
                if (particle.y < 0 || particle.y > canvas.height) particle.vy *= -1;
                
                ctx.beginPath();
                ctx.arc(particle.x, particle.y, particle.size, 0, Math.PI * 2);
                ctx.fillStyle = 'rgba(255,255,255,0.5)';
                ctx.fill();
            });
            
            requestAnimationFrame(animate);
        }

        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        for (let i = 0; i < 50; i++) {
            particles.push(createParticle());
        }

        animate();
    }
}

// CSS Animations
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
`;
document.head.appendChild(style);

// Initialize app when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new ApartmentApp();
});

// Utility functions
const utils = {
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    },

    formatCurrency(amount) {
        return new Intl.NumberFormat('en-PH', {
            style: 'currency',
            currency: 'PHP'
        }).format(amount);
    }
};

// Export for use in other scripts
window.ApartmentApp = ApartmentApp;
window.utils = utils;
