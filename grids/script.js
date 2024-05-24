async function init() {
    const gridsResult = await fetch("../api/get_grids/", {
        credentials: 'same-origin'
    });
    const grids = await gridsResult.json();

    const gridsLocation = document.querySelector('.grids');

    grids.forEach((grid) => {
        if (!grid.name) return;

        const newGrid = document.createElement('div');
        newGrid.classList.add('grid-in-list');

        const newGridTitle = document.createElement('h2');
        newGridTitle.classList.add('grid-title');
        newGridTitle.innerText = grid.name;
        newGrid.appendChild(newGridTitle);

        const newGridAuthor = document.createElement('p');
        newGridAuthor.classList.add('grid-author');
        newGridAuthor.innerText = grid.owner;
        newGrid.appendChild(newGridAuthor);

        const newGridGotoDiv = document.createElement('a');
        newGridGotoDiv.href = `../grid/?id=${grid.id}`;
        const newGridGotoButton = document.createElement('button');
        newGridGotoButton.classList.add('go-to-grid');
        newGridGotoButton.innerText = 'Ouvrir';
        newGridGotoDiv.appendChild(newGridGotoButton);
        newGrid.appendChild(newGridGotoDiv);

        gridsLocation.appendChild(newGrid);
    });

    document.querySelector('#logout').addEventListener('click', () => {
        if (document.cookie.split(';').some((item) => item.trim().startsWith('r_place_login_token' + '='))) {
            document.cookie = 'r_place_login_token' + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
        }
        document.location.replace('../accounts/');
    });
}

init();
