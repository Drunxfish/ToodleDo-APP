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


// opens form with animation
formSlider.forEach(element => {
    element.addEventListener('click', () => {
        frmContainer.style.display = 'flex';
        frmContainer.style.opacity = 0;
        setTimeout(() => {
            frmContainer.style.opacity = 1;
            modal.style.transform = "translateY(0%)";
        }, 10);
        document.body.style.overflow = 'hidden';
    });
});