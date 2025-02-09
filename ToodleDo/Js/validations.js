const form = document.getElementById('form');
const username_input = document.getElementById('username-input');
const email_input = document.getElementById('email-input');
const password_input = document.getElementById('password-input');
const repeat_password_input = document.getElementById('repeat-password-input');


// assebles toast element with msg and given icon(from assets) 
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


    // Delete toast on mouseover
    toast.addEventListener('mouseover', () => {
        toast.style.opacity = 0;
        setTimeout(() => {
            toast.remove();
        }, 1000);
    });


    // dissapear // delete toast from doc
    setTimeout(() => {
        toast.style.opacity = 0;
    }, 8000);

    setTimeout(() => {
        toast.remove();
    }, 10000);


}



// Both signup/login validations 
form.addEventListener('submit', (e) => {
    const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    // removes all the other incorrect classes from the elements
    document.querySelectorAll('.incorrect').forEach(element => {
        element.classList.remove('incorrect');
    })


    // Sign_up
    if (username_input) {
        if(username_input.value === null || username_input.value === ''){
            e.preventDefault();
            username_input.parentElement.classList.add('incorrect');
            return getToast('You must choose an username', 'cross');
        }
    }

    
    // Sign_up // Login
    if (email_input.value === null || email_input.value === '') {
        e.preventDefault();
        email_input.parentElement.classList.add('incorrect');
        return getToast('You must provide an email address', 'cross');
    }

    // Sign_up // Login
    if (!regex.test(email_input.value)) {
        e.preventDefault();
        email_input.parentElement.classList.add('incorrect');
        return getToast('Your email must be in a proper format (e.g., user@example.com).', 'cross');
    }




    // Sign_up // Login
    if (password_input.value === null || password_input.value === '') {
        e.preventDefault();
        password_input.parentElement.classList.add('incorrect');
        return getToast('You must provide a password.', 'cross');
    }

    // Sign_up
    if (repeat_password_input.value === null || repeat_password_input.value === '') {
        e.preventDefault();
        repeat_password_input.parentElement.classList.add('incorrect');
        return getToast('Please confirm your password.', 'cross');
    }

    if (password_input.value !== repeat_password_input.value) {
        e.preventDefault();
        password_input.parentElement.classList.add('incorrect');
        repeat_password_input.parentElement.classList.add('incorrect');
        return getToast("Password does not match repeated password", 'cross')
    }

})











