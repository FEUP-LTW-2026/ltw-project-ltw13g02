document.addEventListener('DOMContentLoaded', () => {
    const navLinks = document.querySelectorAll('.admin-nav a');
    const sections = document.querySelectorAll('.dashboard-section');

    // Ensure only the active section is visible on page load
    sections.forEach(section => {
        if (section.classList.contains('active')) {
        section.style.display = 'block';
        } else {
        section.style.display = 'none';
        }
    });

    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
        e.preventDefault();
        
        // Update active link styling
        navLinks.forEach(l => l.classList.remove('active'));
        link.classList.add('active');

        // Hide all sections and show the targeted one
        const targetId = link.getAttribute('href').substring(1);
        sections.forEach(sec => {
            if (sec.id === targetId) {
            sec.style.display = 'block';
            sec.classList.add('active');
            } else {
            sec.style.display = 'none';
            sec.classList.remove('active');
            }
        });
        });
    });
});