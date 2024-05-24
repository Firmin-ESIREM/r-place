async function init() {
    let params = new URLSearchParams(window.location.search);
    const grid = document.querySelector('.grid');

    if (params.has('id')) {
        const res = await fetch("../api/pull_grid/", {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: params.get('id')
            })
        });
        const gridInfo = await res.json();
        document.querySelector('.grid-rename').value = gridInfo.name;
        gridInfo.pixels.forEach((upstreamPixel) => {
            const pixel = document.createElement('div');
            pixel.classList.add('pixel');
            pixel.dataset.color = upstreamPixel.color ? upstreamPixel.color : '1';
            pixel.dataset.x = upstreamPixel.x;
            pixel.dataset.y = upstreamPixel.y;
            pixel.dataset.owner = upstreamPixel.owner ? upstreamPixel.owner : '';
            grid.appendChild(pixel);
        });
    } else {
        const res = await fetch("../api/create_grid/", {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                grid_name: "Grille sans nom"
            })
        });

        const id = await res.text()

        window.history.pushState({}, '', `?id=${id}`);
        params = new URLSearchParams(window.location.search);
        document.querySelector('.grid-rename').value = "Grille sans nom";

        for (let x = 1 ; x <= 30 ; x++) {
            for (let y = 1 ; y <= 30 ; y++) {
                const pixel = document.createElement('div');
                pixel.classList.add('pixel');
                pixel.dataset.color = '1';
                pixel.dataset.x = `${x}`;
                pixel.dataset.y = `${y}`;
                pixel.dataset.owner = '';
                grid.appendChild(pixel);
            }
        }
    }

    Array.from(document.querySelectorAll('.color')).forEach((colorButton) => {
        colorButton.addEventListener('click', () => {
            document.querySelector('.active-color').classList.remove('active-color');
            colorButton.classList.add('active-color');
        });
    });

    Array.from(document.querySelectorAll('.pixel')).forEach((pixel) => {
        pixel.addEventListener('click', async () =>  {
            const currentColor = document.querySelector('.active-color').dataset.color;
            console.log(JSON.stringify({
                grid_id: params.get('id'),
                x: pixel.dataset.x,
                y: pixel.dataset.y,
                color: currentColor
            }));
            const response = await fetch("../api/alter_pixel/", {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    grid_id: params.get('id'),
                    x: pixel.dataset.x,
                    y: pixel.dataset.y,
                    color: currentColor
                })
            });
            const responseData = await response.json();
            if (responseData.state == 'OK') {
                pixel.dataset.color = currentColor;
                pixel.dataset.owner = responseData.owner;
            } else if (responseData.state == 'TIMEOUT') {
                alert('Ce pixel est verrouillé. Un délai de 15 secondes est appliqué entre chaque modification.');
            }
        });
        pixel.addEventListener('mouseover', () => {
            document.querySelector('.pixel-owner').innerText = pixel.dataset.owner == "" ? "Le dernier pixel survolé n’a pas encore de propriétaire." : `Le dernier pixel survolé appartient à ${pixel.dataset.owner}.`;
        });
    });

    document.querySelector('#rename-grid').addEventListener('click', async () => {
        console.log('renaming');
        const newName = document.querySelector('.grid-rename').value;
        await fetch("../api/rename_grid/", {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                id: params.get('id'),
                new_name: newName
            })
        });
    });

    document.querySelector('#delete-grid').addEventListener('click', async () => {
        if (confirm("Êtes-vous sûr de vouloir supprimer cette grille ?")) {
            await fetch("../api/delete_grid/", {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id: params.get('id'),
                })
            });
            document.location.replace('../grids/');
        }
    });

    document.querySelector('#logout').addEventListener('click', () => {
        if (document.cookie.split(';').some((item) => item.trim().startsWith('r_place_login_token' + '='))) {
            document.cookie = 'r_place_login_token' + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        }
        document.location.replace('../accounts/');
    });
}

init();
