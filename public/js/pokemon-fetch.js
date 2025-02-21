document.addEventListener("DOMContentLoaded", async () => {
    const container = document.getElementById("pokemon-container");

    // Fonction pour récupérer un Pokémon aléatoire avec son nom en anglais et en français
    async function getRandomPokemon() {
        const randomId = Math.floor(Math.random() * 151) + 1;

        // Récupère les infos du Pokémon
        const responsePokemon = await fetch(`https://pokeapi.co/api/v2/pokemon/${randomId}`);
        const dataPokemon = await responsePokemon.json();
        const englishName = dataPokemon.name; // Nom en anglais pour l'image

        // Récupère le nom en français
        const responseSpecies = await fetch(`https://pokeapi.co/api/v2/pokemon-species/${randomId}`);
        const dataSpecies = await responseSpecies.json();
        const frenchName = dataSpecies.names.find(name => name.language.name === "fr").name;

        return { englishName, frenchName };
    }

    // Génère et affiche 5 Pokémon aléatoires
    async function displayPokemons() {
        for (let i = 0; i < 5; i++) {
            const { englishName, frenchName } = await getRandomPokemon();
            const imgUrl = `https://img.pokemondb.net/sprites/brilliant-diamond-shining-pearl/normal/${englishName}.png`;

            const pokemonCard = document.createElement("div");
            pokemonCard.classList.add("pokemon-card");

            pokemonCard.innerHTML = `
                <img src="${imgUrl}" alt="${frenchName}">
                <p>${frenchName}</p>
            `;

            container.appendChild(pokemonCard);
        }
    }

    displayPokemons();
});
