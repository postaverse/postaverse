document.addEventListener('DOMContentLoaded', function () {
    function createStars(id, count) {
        for (let i = 0; i < count; i++) {
            let star = document.createElement('div');
            star.className = 'star';
            star.style.top = Math.random() * window.innerHeight + 'px';
            star.style.left = Math.random() * window.innerWidth + 'px';
            let size = Math.random() * 3; // Change this value to adjust the range of sizes
            star.style.width = `${size}px`;
            star.style.height = `${size}px`;
            star.style.transform = `rotate(${Math.random() * 360}deg)`;
            document.getElementById(id).appendChild(star);
        }
    }

    createStars('stars', 400);

    const stars = document.querySelectorAll('.star');

    stars.forEach(star => {
        // Random duration between 0.5s and 2.5s
        const duration = Math.random() * 2 + 0.5;
        // Random delay up to 5s
        const delay = Math.random() * 5;

        star.style.animationDuration = `${duration}s`;
        star.style.animationDelay = `-${delay}s`;
    });
    function createConfetti(id, count) {
        for (let i = 0; i < count; i++) {
            let confettiPiece = document.createElement('div');
            confettiPiece.className = 'confetti';
            confettiPiece.style.top = `${-Math.random() * 20}px`; // Start above the screen
            confettiPiece.style.left = `${Math.random() * window.innerWidth}px`;
            confettiPiece.style.backgroundColor = `hsl(${Math.random() * 360}, 100%, 50%)`; // Random color
            let size = Math.random() * 5 + 5; // Size between 5px and 10px
            confettiPiece.style.width = `${size}px`;
            confettiPiece.style.height = `${size}px`;
            confettiPiece.style.opacity = Math.random(); // Varying opacity
            confettiPiece.style.transform = `rotate(${Math.random() * 360}deg)`;
            document.getElementById(id).appendChild(confettiPiece);
        }
    }

    let clickCount = 0;

    document.body.addEventListener('click', function (event) {
        // Check if the click is within the top left 5px by 5px area
        if (event.clientX <= 5 && event.clientY <= 5) {
            createConfetti('confetti-container', 25); // Adjust count as needed

            // Animate each confetti piece
            document.querySelectorAll('.confetti').forEach(confettiPiece => {
                const duration = Math.random() * 3 + 2; // Duration between 2s and 5s
                const delay = Math.random() * 5; // Delay up to 5s

                confettiPiece.style.animation = `fall ${duration}s linear ${delay}s infinite`;
            });
        }
    });
});
