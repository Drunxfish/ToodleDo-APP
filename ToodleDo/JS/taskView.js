const tasksDisplayBtn = document.querySelector('.tskDispTr');
const displayCloserBtn = document.querySelector('.tskCloseTr');
const tasksDisplay = document.querySelector('.tasks-display');
const displayChild = document.querySelector(".tasksWrapper");



// Opens Display
tasksDisplayBtn.addEventListener('click', () => {
    tasksDisplay.style.display = 'flex';
    tasksDisplay.classList.add('fade-In');
    displayChild.classList.add("fade-In");
    displayChild.style.transform = 'translateY(0vh)';
});

// closes Display
displayCloserBtn.addEventListener('click', () => {
    displayChild.style.transform = 'translateY(150vh)';
    setTimeout(() => {
        displayChild.classList.remove("fade-In");
        tasksDisplay.classList.remove('fade-In');
        tasksDisplay.style.display = 'none';
    }, 1000);
});