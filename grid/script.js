async function init() {




    Array.from(document.querySelectorAll('.color')).forEach((colorButton) => {
        colorButton.addEventListener('click', () => {
            document.querySelector('.active-color').classList.remove('active-color');
            colorButton.classList.add('active-color');
        });
    });

    document.querySelector('#logout').addEventListener('click', () => {
        if (document.cookie.split(';').some((item) => item.trim().startsWith('r_place_login_token' + '='))) {
            document.cookie = 'r_place_login_token' + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        }
        document.location.replace('../accounts/');
    });
}

init();
