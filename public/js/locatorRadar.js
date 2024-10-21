console.log("hello");

// Initialize Radar with the project key
Radar.initialize("prj_live_pk_282bce66618b63742b0ad59cd6a9c2deda92aadd");

// Ensure the DOM element exists before initializing
document.addEventListener("DOMContentLoaded", () => {
    const initializeAutocomplete = (containerId, cityInputId, stateInputId) => {
        const container = document.getElementById(containerId);
        const cityInput = document.getElementById(cityInputId);
        const stateInput = document.getElementById(stateInputId);

        if (container) {
            Radar.ui.autocomplete({
                container: containerId, // The ID of the container
                showMarkers: true, // Show markers on the map
                markerColor: "#ACBDC8", // Marker color
                responsive: true, // Enable responsiveness
                width: "600px", // Set width of the input box
                maxHeight: "600px", // Set max height of the results box
                placeholder: "Search address", // Placeholder for the input
                limit: 8, // Limit the number of results to show
                minCharacters: 3, // Minimum characters before showing suggestions
                near: null, // Use default IP-based location
                onResults: (addresses) => {
                    console.log(addresses);

                    if (addresses.length == 1) {
                        console.log(addresses);

                        const firstResult = addresses[0]; // Get the first result
                        const city = firstResult["county"] || "";
                        const state = firstResult["state"] || "";

                        const countyState = `${city} ${state}`;
                        const stateCode = firstResult["stateCode"] || "";

                        if (cityInput) cityInput.value = countyState;
                        if (stateInput) stateInput.value = stateCode;

                        console.log(`${countyState} ${stateCode}`);
                        Radar.ui.autocomplete.onSelection(firstResult); // Only call once
                    } else {
                        console.log("hello");
                    }
                    // console.log("Results:", addresses);
                },
                onSelection: (address) => {
                    if (address.postalCode) {
                        console.log(address);
                    } else {
                        console.log("not found!");
                    }

                    // Handle the selected address
                    console.log("Selected address:", address);

                    // Extract city and state from the selected address
                    // const city = address.county || ""; // Set default if not available
                    // const state = address.state || ""; // Set default if not available

                    // // Update the input fields with city and state
                    // if (cityInput) cityInput.value = city;
                    // if (stateInput) stateInput.value = state;
                },
            });
        } else {
            console.error(
                `Autocomplete container with ID '${containerId}' not found. Please check your HTML.`
            );
        }
    };

    // Initialize autocomplete for both containers
    initializeAutocomplete(
        "recipientStreet",
        "recipientCity",
        "recipientstateOrProvinceCode"
    );
    initializeAutocomplete(
        "shipperStreet",
        "shipperCity",
        "shipperstateOrProvinceCode"
    );
});
