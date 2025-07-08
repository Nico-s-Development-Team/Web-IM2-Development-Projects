const sidebarItems = document.querySelectorAll('.sidebar li');

sidebarItems.forEach(item => {
    item.addEventListener('click', () => {
        // Remove 'active' from all items
        sidebarItems.forEach(i => i.classList.remove('active'));
        // Add 'active' to clicked item
        item.classList.add('active');
    });
});
