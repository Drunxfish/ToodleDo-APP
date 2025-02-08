// elements
const formSlider = document.querySelectorAll('.formSlider');
const formCloserBTN = document.querySelectorAll('.formCloserBTN');
const frmContainer = document.querySelector('.frmContainer');
const frmEditct = document.querySelector('.frmEDIT');
const modal = document.querySelector('.modal');


// closes form
formCloserBTN.forEach(element => {
    element.addEventListener('click', (e) => {
        frmContainer.style.display = 'none';
        document.body.style.overflow = '';
        window.location.href = 'dashboard.php';
    });
})


// opens form
formSlider.forEach(element => {
    element.addEventListener('click', () => {
        modal.style.transform = 'translateY(0)';
        frmContainer.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    });
});