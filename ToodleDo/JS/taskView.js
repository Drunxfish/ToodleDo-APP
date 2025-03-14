const tasksDisplayBtn = document.querySelectorAll('.tskDispTr');
const displayCloserBtn = document.querySelector('.tskCloseTr');
const tasksDisplay = document.querySelector('.tasks-display');
const displayChild = document.querySelector(".tasksWrapper");



// Opens Display
tasksDisplayBtn.forEach(element => {
    element.addEventListener('click', () => {
        tasksDisplay.style.display = 'flex';
        tasksDisplay.classList.add('fade-In');
        displayChild.classList.add("fade-In");
        displayChild.style.transform = 'translateY(0vh)';
        document.body.style.overflow = 'hidden';
    })
});

// closes Display
displayCloserBtn.addEventListener('click', () => {
    displayChild.style.transform = 'translateY(150vh)';
    document.body.style.overflow = '';
    setTimeout(() => {
        displayChild.classList.remove("fade-In");
        tasksDisplay.classList.remove('fade-In');
        tasksDisplay.style.display = 'none';
    }, 1000);
});