// profile manager container
const profileCloserBtn = document.querySelectorAll('.profileCancelBtn');
const profileOpenerBtn = document.querySelector('.profileManagerBtn');
const profileManager = document.querySelector('.profileManager');

// Form elements
const profileForm = document.querySelector('.profileWrapper');
const currentPassword = document.getElementById('currentPwd');
const newPassword = document.getElementById('newPwd');
const confirmPassword = document.getElementById('ConfPwd');
const avatarDisplay = document.getElementById('AvatarDisplay');
const avatar = document.getElementById('pfpAvatar');



// # Event listeners

// Displays selected avatar
avatar.addEventListener('change', (event) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            avatarDisplay.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

// Open Profile manager
profileOpenerBtn.addEventListener('click', () => {
    profileManager.classList.add('fade-In');
    document.body.style.overflow = 'hidden';
});

// Close Profile manager
profileCloserBtn.forEach(btn => {
    btn.addEventListener('click', () => {
        profileManager.classList.remove('fade-In');
        document.body.style.overflow = 'auto';
    });
});




// Validation
profileForm.addEventListener('submit', (e) => {
    if (currentPassword.value !== '' && (newPassword.value === '' || confirmPassword.value === '')) {
        e.preventDefault();
        return getToast('Please confirm your password.', 'cross');
    }

    if (currentPassword.value !== '' && (newPassword.value !== confirmPassword.value)) {
        e.preventDefault();
        return getToast("Password does not match repeated password", 'cross');
    }
});






















// Assembles toast element with msg and given icon(from assets) 
function getToast(msg, icon) {
    const toastbox = document.getElementById('toastbox');
    let toast = document.createElement('div');
    let wrap = document.createElement('i');
    let img = document.createElement('img');
    let para = document.createElement('p');

    toast.classList.add('toast');
    img.src = `./../Assets/Icons/${icon}.png`
    para.innerText = msg;
    wrap.appendChild(img);
    toast.appendChild(wrap)
    toast.appendChild(para)
    toastbox.append(toast);
    toastbox.style.zIndex = 9999999999999999999n;


    // Delete toast on mouseover
    toast.addEventListener('mouseover', () => {
        toast.style.opacity = 0;
        setTimeout(() => {
            toast.remove();
        }, 1000);
    });


    // Dissapear // delete toast from doc
    setTimeout(() => {
        toast.style.opacity = 0;
    }, 8000);

    setTimeout(() => {
        toast.remove();
    }, 10000);


}

