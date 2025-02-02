// grabs all the toasts from the body
let toast = document.querySelectorAll(".toast");


// removes all the toast elements 1 by one (extra)
if (toast.length > 0) {
    for (let i = 0; i < toast.length; i++) {
        setTimeout(() => {
            toast[i].style.opacity = 0; 
        }, 8000);    

        setTimeout(() => {
            toast[i].remove();
        }, 10000);    
    }    
}    

