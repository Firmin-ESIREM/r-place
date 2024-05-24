async function signup() {
    Array.from(document.querySelectorAll('.field-error-message')).forEach((errorMessage) => {
        errorMessage.style.display = 'none';
    });
    const overlay = document.querySelector('.overlay');
    overlay.style.display = 'block';
    const email = document.querySelector('#email');
    const username = document.querySelector('#username');
    const password = document.querySelector('#password');
    const passwordValidate = document.querySelector('#password-validate');
    if (password.value != passwordValidate.value) {
        document.querySelector('#passwords-do-not-match').style.display = 'block';
        overlay.style.display = 'none';
        return;
    }
    const data = JSON.stringify({
        email: email.value,
        username: username.value,
        password: password.value
    });

    console.log(data);

    const response = await fetch("../../api/signup/", {
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
        document.location.replace('../login/');
    } else {
        let errorIdToSelect;
        if (response.status === 401) {
            errorIdToSelect = 'email-already-in-use';
        }
        else {
            errorIdToSelect = 'unhandled-error';
        }
        document.querySelector(`#${errorIdToSelect}`).style.display = 'block';
    }

    overlay.style.display = 'none';
}

function init() {
    const signupButton = document.querySelector('#do-signup');
    signupButton.addEventListener('click', () => {
        signup();
    });
    document.body.addEventListener("keypress", (event) => {
        if (event.key === "Enter") {
            event.preventDefault();
            document.querySelector("#do-signup").click();
        }
    });
}

init();
