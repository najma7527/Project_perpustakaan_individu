// DROPDOWN PROFILE
const userDropdown = document.querySelector('.user-dropdown');
const dropdownMenu = document.querySelector('.dropdown');

userDropdown.addEventListener('click', function (e) {
    e.stopPropagation();
    dropdownMenu.classList.toggle('show');
});

// KLIK DI LUAR → DROPDOWN TUTUP
document.addEventListener('click', function () {
    dropdownMenu.classList.remove('show');
});


// TOGGLE SIDEBAR (ICON BAR)
const toggleBtn = document.querySelector('.fa-bars');
const sidebar = document.querySelector('.sidebar');

toggleBtn.addEventListener('click', function () {
    sidebar.classList.toggle('hide');
});
