// elements
const formSlider = document.querySelectorAll('.formSlider');
const formCloserBTN = document.getElementById('formCloserBTN');
const frmContainer = document.querySelector('.frmContainer');
const modal = document.querySelector('.modal');



// closes
formCloserBTN.addEventListener('click', (e) => {
    frmContainer.style.display = 'none';
    document.body.style.overflow = '';
});



// opens form
formSlider.forEach(element => {
    element.addEventListener('click', () => {
        modal.style.transform = 'translateY(0)';
        frmContainer.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    });
});