document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    const closeBtn = document.querySelector('.close-sidebar');

    if (!sidebarToggle || !sidebar || !overlay || !closeBtn) {
        console.error('Some sidebar elements are missing');
        return;
    }

    // Open sidebar
    sidebarToggle.addEventListener('click', function(e) {
        e.preventDefault();
        openSidebar();
    });

    // Close sidebar when clicking overlay
    overlay.addEventListener('click', closeSidebar);

    // Close sidebar when clicking close button
    closeBtn.addEventListener('click', closeSidebar);

    // Close sidebar when pressing ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && sidebar.classList.contains('active')) {
            closeSidebar();
        }
    });
});

function openSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    
    if (sidebar && overlay) {
        sidebar.classList.add('active');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    
    if (sidebar && overlay) {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }
}