// MetaGen Static JavaScript File
console.log("Admin static script.js loaded.");

document.addEventListener('DOMContentLoaded', function () {
    // Example: Sidebar active state based on URL hash (for static navigation)
    function setActiveSidebarLink() {
        const currentHash = window.location.hash || '#overzicht'; // Default to #overzicht
        const sidebarLinks = document.querySelectorAll('.sidebar-item');

        sidebarLinks.forEach(link => {
            link.classList.remove('active');
            // Check if the link's href (or a data attribute) matches the current hash
            // For this static example, we'll assume hrefs like "#overzicht", "#inkomend"
            if (link.getAttribute('href') === currentHash) {
                link.classList.add('active');
            }
        });
    }

    // Example: Tab functionality
    const tabContainer = document.querySelector('[data-tab-container]');
    if (tabContainer) {
        const tabButtons = tabContainer.querySelectorAll('.tab-button');
        const tabPanels = tabContainer.querySelectorAll('[role="tabpanel"]');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Deactivate all buttons and hide all panels
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabPanels.forEach(panel => panel.classList.add('hidden'));

                // Activate clicked button and show corresponding panel
                button.classList.add('active');
                const targetPanelId = button.getAttribute('aria-controls');
                const targetPanel = document.getElementById(targetPanelId);
                if (targetPanel) {
                    targetPanel.classList.remove('hidden');
                }
            });
        });

        // Activate the first tab by default if none are active
        if (tabButtons.length > 0 && !tabContainer.querySelector('.tab-button.active')) {
            tabButtons[0].click(); // Programmatically click the first tab
        }
    }

    // Set active link on initial load and on hash change
    setActiveSidebarLink();
    window.addEventListener('hashchange', setActiveSidebarLink);

    // Dropdown toggles for user menu and administration switcher
    const userMenuButton = document.getElementById('user-menu-button');
    const userMenuDropdown = document.getElementById('user-menu-dropdown');

    if (userMenuButton && userMenuDropdown) {
        userMenuButton.addEventListener('click', function (event) {
            userMenuDropdown.classList.toggle('hidden');
            event.stopPropagation(); // Prevent click from bubbling to document
        });
    }

    const adminSwitchButton = document.getElementById('admin-switch-button');
    const adminSwitchDropdown = document.getElementById('admin-switch-dropdown');

    if (adminSwitchButton && adminSwitchDropdown) {
        adminSwitchButton.addEventListener('click', function (event) {
            adminSwitchDropdown.classList.toggle('hidden');
            event.stopPropagation();
        });
    }

    // Close dropdowns if clicked outside
    document.addEventListener('click', function (event) {
        if (userMenuDropdown && !userMenuDropdown.classList.contains('hidden') && !userMenuButton.contains(event.target) && !userMenuDropdown.contains(event.target)) {
            userMenuDropdown.classList.add('hidden');
        }
        if (adminSwitchDropdown && !adminSwitchDropdown.classList.contains('hidden') && !adminSwitchButton.contains(event.target) && !adminSwitchDropdown.contains(event.target)) {
            adminSwitchDropdown.classList.add('hidden');
        }
    });

});
