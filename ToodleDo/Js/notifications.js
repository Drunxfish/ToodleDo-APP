const notifViewBtn = document.querySelector('.notifViewBtn');
const notifContainer = document.querySelector('.notifsView');
const notifCloser = document.querySelector('.notifsCloserBtn');

// open notifications
notifViewBtn.addEventListener('click', () => {
    notifContainer.style.display = 'flex';
    document.body.style.overflow = 'hidden';
});



if (notifCloser) {
    notifCloser.addEventListener('click', () => {
        notifContainer.style.display = 'none';
        document.body.style.overflow = 'auto';
    });
}