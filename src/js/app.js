const mobileMenu = document.querySelector('#mobile-menu');
const sidebar = document.querySelector('.sidebar');

if(mobileMenu) {
    mobileMenu.addEventListener('click', function() {
        sidebar.classList.toggle('show');
    });
}