async function login() {
    Array.from(document.querySelectorAll('.field-error-message')).forEach((errorMessage) => {
        errorMessage.style.display = 'none';
    });
    const overlay = document.querySelector('.overlay');
    overlay.style.display = 'block';
    const email = document.querySelector('#email');
    const password = document.querySelector('#password');
    const data = JSON.stringify({
        email: email.value,
        password: password.value
    });

    const response = await fetch("../../api/login/", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: data
    });
    
    if (response.ok) {
        const token = await response.text();
        const expirationDate = new Date();
        expirationDate.setTime(expirationDate.getTime() + (7*24*60*60*1000));
        document.cookie = `r_place_login_token=${token}; expires=${expirationDate.toUTCString()}; path=/`;
        document.location.replace('../../grids/')
    } else {
        let errorIdToSelect;
        if (response.status === 401) {
            errorIdToSelect = 'invalid-credentials';
        } else {
            errorIdToSelect = 'unhandled-error';
        }
        document.querySelector(`#${errorIdToSelect}`).style.display = 'block';
    }

    overlay.style.display = 'none';
}

function init() {
    const loginButton = document.querySelector('#do-login');
    loginButton.addEventListener('click', () => {
        login();
    });
    document.body.addEventListener("keypress", (event) => {
        if (event.key === "Enter") {
            event.preventDefault();
            document.querySelector("#do-login").click();
        }
    });
}

init();
