// Smooth color transition on hover
document.addEventListener('DOMContentLoaded', function() {
    const icons = document.querySelectorAll('.shareme-social-icons a');

    icons.forEach(icon => {
        icon.addEventListener('mouseenter', () => {
            icon.style.transition = 'color 0.3s ease-in-out, transform 0.3s ease';
            icon.style.transform = 'scale(1.2)';
        });

        icon.addEventListener('mouseleave', () => {
            icon.style.transform = 'scale(1)';
        });

        icon.addEventListener('click', (event) => {
            event.preventDefault();
            alert('Share functionality coming soon!');
        });
    });
});
