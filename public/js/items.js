document.addEventListener("DOMContentLoaded", () => {
    const pokeBallsContainer = document.getElementById("pokeBallsContainer");
    const itemsContainer = document.getElementById("itemsContainer");

    async function fetchItems() {
        try {
            const response = await fetch("https://pokeapi.co/api/v2/item/"); // Fetch all items
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

            const data = await response.json();
            console.log("Fetched data:", data); // Debugging

            if (!data.results || data.results.length === 0) {
                console.error("No items found");
                return;
            }

            // Process each item
            for (const item of data.results) {
                try {
                    const itemResponse = await fetch(item.url);
                    const itemDetails = await itemResponse.json();

                    // Get French name
                    const frenchName = itemDetails.names.find(name => name.language.name === "fr")?.name || "Nom inconnu";

                    // Generate a random price
                    const randomPrice = Math.floor(Math.random() * (5000 - 100 + 1)) + 100;

                    // Create item card
                    const itemCard = document.createElement("div");
                    itemCard.classList.add("item-card");

                    itemCard.innerHTML = `
                        <img src="${itemDetails.sprites?.default || '/images/default.png'}" alt="${frenchName}">
                        <p>${frenchName} :</p>
                        <p>${randomPrice} $</p>
                    `;

                    // Check if item is a Pokéball
                    if (frenchName.toLowerCase().includes("ball") || itemDetails.id === 21) {
                        pokeBallsContainer.appendChild(itemCard);
                    } else {
                        itemsContainer.appendChild(itemCard);
                    }
                } catch (error) {
                    console.error("Error fetching item details:", error);
                }
            }
        } catch (error) {
            console.error("Error fetching items:", error);
        }
    }

    fetchItems();
});
