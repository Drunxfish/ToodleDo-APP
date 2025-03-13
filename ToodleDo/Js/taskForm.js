// elements
const formSlider = document.querySelectorAll('.formSlider');
const formCloserBTN = document.querySelectorAll('.formCloserBTN');
const frmContainer = document.querySelector('.frmContainer');
const frmEditct = document.querySelector('.frmEDIT');
const frmContent = document.querySelector('.addFRMC');



// opens form with animation
formSlider.forEach(element => {
    element.addEventListener('click', () => {
        frmContainer.style.display = 'flex';
        frmContainer.classList.add('fade-In');

        setTimeout(() => {
            frmContent.style.transform = "translateY(0%)";
        }, 500);
        document.body.style.overflow = 'hidden';
    });
});

// closes form
formCloserBTN.forEach(element => {
    element.addEventListener('click', () => {
        window.location.href = 'dashboard.php';
    });
})
