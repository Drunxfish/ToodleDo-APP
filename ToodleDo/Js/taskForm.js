// elements
const formSlider = document.querySelectorAll('.formSlider');
const formCloserBTN = document.querySelectorAll('.formCloserBTN');
const frmContainer = document.querySelector('.frmContainer');
const frmEditct = document.querySelector('.frmEDIT');
const frmContent = document.querySelector('.addFRMC');



// opens form with animation
formSlider.forEach(element => {
    element.addEventListener('click', () => {
        document.body.style.overflow = 'hidden';
        frmContainer.style.display = 'flex';
        frmContainer.classList.add('fade-In');
        setTimeout(() => {
            frmContent.style.transform = "translateY(0%)";
        }, 500);
    });
});

// closes form
formCloserBTN.forEach(element => {
    element.addEventListener('click', () => {
        document.body.style.overflow = '';
        window.location.href = 'dashboard.php';
    });
})


