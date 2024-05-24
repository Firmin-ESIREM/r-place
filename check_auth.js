accountsLocations = ['accounts', 'login', 'signup'];

async function checkAuth(currentPageDepth) {
    const result = await fetch(`./${'../'.repeat(currentPageDepth)}api/check_auth/`, {
        credentials: 'same-origin'
    });
    const resultJson = await result.json();

    console.log(resultJson);
    let currentLocation = document.location.pathname.split('/');
    currentLocation = currentLocation[currentLocation.length - 2];
    if (accountsLocations.includes(currentLocation)) {
        if (resultJson.state === 'OK') {
            document.location.replace(`./${'../'.repeat(currentPageDepth)}grids/`);
        } else {
            if (document.cookie.split(';').some((item) => item.trim().startsWith('r_place_login_token' + '='))) {
                document.cookie = 'r_place_login_token' + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
            }
        }
    } else if (resultJson.state != 'OK') {
        if (document.cookie.split(';').some((item) => item.trim().startsWith('r_place_login_token' + '='))) {
            document.cookie = 'r_place_login_token' + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        }
        document.location.replace(`./${'../'.repeat(currentPageDepth)}accounts/`);
    }
}
