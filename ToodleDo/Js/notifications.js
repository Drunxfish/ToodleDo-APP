const notifViewBtn = document.querySelector('.notifViewBtn');
const notifContainer = document.querySelector('.notifsView');
const notifCloser = document.querySelector('.notifsCloserBtn');
const box = document.querySelector('.notifWrapper');


// open notifications
notifViewBtn.addEventListener('click', () => {
    notifContainer.style.display = 'flex';
    notifContainer.style.scale = '1';
    document.body.style.overflow = 'hidden';
    box.style.display = 'block';
    box.classList.add('fade-In');
    box.style.transform = 'translateY(0vh)';
});


// close notifications
if (notifCloser) {
    notifCloser.addEventListener('click', () => {
        box.style.transform = 'translateY(150vh)';
        setTimeout(() => {
            box.classList.remove('fade-In');
            notifContainer.style.scale = '0';
            setTimeout(() => {
                notifContainer.style.display = 'none';
                document.body.style.overflow = '';
            }, 1300);
        }, 800);
    });
}
