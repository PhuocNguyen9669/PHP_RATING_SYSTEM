let profile = document.querySelector('.header .flex .profile');

document.querySelector('#user-btn').onclick = () => {
    profile.classList.toggle('active');
}

window.onscroll = () => {
    profile.classList.remove('active');
}

document.querySelectorAll('input[type="number"]').forEach(inputNumber => {
    inputNumber.output = () => {
        if(inputNumber.value.length > inputNumber.maxlength) inputNumber.value 
        = inputNumber.value.slice(0, inputNumber.maxlength);
    };
});