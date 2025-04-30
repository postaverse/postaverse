document.addEventListener('DOMContentLoaded', function () {
    function createStars(id, count) {
        const starsContainer = document.getElementById(id);
        if (!starsContainer) return;
        
        for (let i = 0; i < count; i++) {
            let star = document.createElement('div');
            star.className = 'star';
            star.style.top = Math.random() * window.innerHeight + 'px';
            star.style.left = Math.random() * window.innerWidth + 'px';
            
            // Create varying star sizes with a bias toward smaller stars
            let sizeRand = Math.random();
            let size;
            
            if (sizeRand > 0.97) {
                // 3% chance of larger stars (shooting stars)
                size = 3 + Math.random() * 1;
                star.classList.add('shooting-star');
            } else if (sizeRand > 0.85) {
                // 12% chance of medium stars
                size = 1.5 + Math.random() * 1;
            } else {
                // 85% chance of small stars
                size = 0.5 + Math.random() * 1;
            }
            
            star.style.width = `${size}px`;
            star.style.height = `${size}px`;
            
            // Add a subtle color tint to some stars
            if (Math.random() > 0.7) {
                const hue = Math.random() > 0.5 ? '210' : Math.random() > 0.7 ? '45' : '0'; // Blue, yellow, or red tint
                const saturation = 50 + Math.random() * 50;
                star.style.backgroundColor = `hsla(${hue}, ${saturation}%, 95%, 1)`;
                star.style.boxShadow = `0 0 ${size * 2}px hsla(${hue}, ${saturation}%, 80%, 0.8)`;
            }
            
            starsContainer.appendChild(star);
        }
    }

    // Initialize stars
    createStars('stars', 250);

    // Configure star animation properties
    const stars = document.querySelectorAll('.star');
    stars.forEach(star => {
        // Randomize twinkle duration between 1s and 4s
        const duration = Math.random() * 3 + 1;
        // Random delay up to 5s for twinkling offset
        const delay = Math.random() * 5;
        
        star.style.animationDuration = `${duration}s`;
        star.style.animationDelay = `-${delay}s`;
        
        // Add shooting star effect to larger stars
        if (star.classList.contains('shooting-star') && Math.random() > 0.7) {
            const shootingDuration = Math.random() * 3 + 4;
            const shootingDelay = Math.random() * 10 + 5;
            
            // Apply a shooting star animation that runs occasionally
            star.style.animation = `twinkle ${duration}s infinite, 
                                  shooting-star ${shootingDuration}s linear ${shootingDelay}s infinite`;
        }
    });
    
    // Handle responsive resizing - reposition stars on window resize
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            stars.forEach(star => {
                star.style.top = Math.random() * window.innerHeight + 'px';
                star.style.left = Math.random() * window.innerWidth + 'px';
            });
        }, 500);
    });
});
