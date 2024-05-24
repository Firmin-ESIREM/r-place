async function forget() {
    Array.from(document.querySelectorAll('.field-error-message')).forEach((errorMessage) => {
        errorMessage.style.display = 'none';
    });
    const response = await fetch("../../api/forgot_password/", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            email: document.querySelector('#email').value
        })
    });
    if (response.ok) {
        const resultText = await response.text();
        const userId = parseInt(resultText);

        let invalidCode = true;
        while (invalidCode) {
            const code = parseInt(prompt("Un code de sécurité vous a été envoyé par e-mail. Veuillez le renseigner ci-dessous :"));
            const response2 = await fetch("../../api/change_forgotten_password/", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    user_id: userId,
                    code: code,
                    password: document.querySelector('#password').value
                })
            });
            if (response2.ok) {
                document.location.replace('../login/');
            } else if (response2.status === 404) {
                alert("Code faux.");
            }
        }

    } else if (response.status === 404) {
        document.querySelector('#unknown-user').style.display = 'block';
    }
}

function init() {
    const loginButton = document.querySelector('#do-forget');
    loginButton.addEventListener('click', () => {
        forget();
    });
    document.body.addEventListener("keypress", (event) => {
        if (event.key === "Enter") {
            event.preventDefault();
            document.querySelector("#do-forget").click();
        }
    });
}

init();
