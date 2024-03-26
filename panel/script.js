document.addEventListener('DOMContentLoaded', function() {
    const sidebarItems = document.querySelectorAll('.sidebar-item');
    const sections = document.querySelectorAll('.section');

    sidebarItems.forEach(item => {
        item.addEventListener('click', function() {
            const sectionId = this.getAttribute('data-section');

            // Hide all sections
            sections.forEach(section => {
                section.classList.remove('active');
            });

            // Show the related section
            const activeSection = document.getElementById(sectionId);
            activeSection.classList.add('active');

            // Highlight the clicked sidebar item
            sidebarItems.forEach(item => {
                item.classList.remove('active');
            });
            this.classList.add('active');
        });
    });

    // Activate the first sidebar item and its related section by default
    sidebarItems[0].classList.add('active');
    sections[0].classList.add('active');
});
