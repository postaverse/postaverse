import './bootstrap';

function createStars(id, count) {
    for (let i = 0; i < count; i++) {
        let star = document.createElement('div');
        star.className = 'star';
        star.style.top = Math.random() * window.innerHeight + 'px';
        star.style.left = Math.random() * window.innerWidth + 'px';
        let size = Math.random() * 3; // Change this value to adjust the range of sizes
        star.style.width = `${size}px`;
        star.style.height = `${size}px`;
        document.getElementById(id).appendChild(star);
    }
}

window.onload = function() {
    createStars('stars1', 400);
    createStars('stars2', 300);
    createStars('stars3', 200);
};