document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggle-btn');
    const overlay = document.getElementById('sidebar-overlay');
    
    const isMobile = () => window.innerWidth < 768;

    function toggleSidebar() {
        if (isMobile()) {
            // Mobile Logic: Slide in/out
            const isOpen = sidebar.style.transform === 'translateX(0%)';
            if (isOpen) {
                sidebar.style.transform = 'translateX(-100%)';
                overlay.classList.add('hidden');
            } else {
                sidebar.style.transform = 'translateX(0%)';
                overlay.classList.remove('hidden');
            }
        } else {
            // Desktop Logic: Toggle .collapsed class
            sidebar.classList.toggle('collapsed');
        }
    }

    if (toggleBtn) {
        toggleBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleSidebar();
        });
    }

    // Handle Resize to reset states
    window.addEventListener('resize', () => {
        if (isMobile()) {
            sidebar.style.width = '16rem';
            sidebar.style.transform = 'translateX(-100%)';
            sidebar.classList.remove('collapsed');
        } else {
            sidebar.style.transform = 'translateX(0%)';
            overlay.classList.add('hidden');
        }
    });
});