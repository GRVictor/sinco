const mobileMenu = document.querySelector('#mobile-menu');
const closeMenu = document.querySelector('#close-menu');
const sidebar = document.querySelector('.sidebar');

if(mobileMenu) {
    mobileMenu.addEventListener('click', function() {
        sidebar.classList.toggle('show');
    });
}

if(closeMenu) {
    closeMenu.addEventListener('click', function() {
        sidebar.classList.add('hide');
        setTimeout(function() {
            sidebar.classList.remove('show');
            sidebar.classList.remove('hide');
        }, 200);
    });
}

// Delete show class in tablet and desktop view

const screenWidth = document.body.clientWidth;
window.addEventListener('resize', function() {
    const screenWidth = document.body.clientWidth;
        if(screenWidth >= 768) {
            sidebar.classList.remove('show');
        }
});

s

async function requestAPI() {
    try {
        const url = 'https://api.example.com/data';
        const response = await fetch(url);
        const services = await response.json();
        showServices(services);

    } catch (error) {
        console.log(error);
    }
}