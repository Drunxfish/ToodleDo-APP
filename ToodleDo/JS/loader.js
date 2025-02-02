const preloader = document.createElement('div');

// objective style assignment
Object.assign(preloader.style, {
    backgroundColor: 'white',
    position: 'fixed',
    top: '0',
    left: '0',
    width: '100%',
    height: '100%',
    display: 'flex',
    justifyContent: 'center',
    alignItems: 'center',
    zIndex: '9999'
});


// Asseble spinner
const spinner = document.createElement('img');
spinner.src = './../Assets/Images/preloader.gif';
spinner.alt = 'Bezig met laden...';
preloader.appendChild(spinner);
document.body.appendChild(preloader);


// Remove spinnder as the body loads 
window.onload = function () {
    document.body.removeChild(preloader);
};