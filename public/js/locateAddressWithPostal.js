// Initialize Radar with the project key
Radar.initialize("prj_live_pk_282bce66618b63742b0ad59cd6a9c2deda92aadd"); // Consider securing this key

// Ensure the DOM element exists before initializing
document.addEventListener("DOMContentLoaded", () => {
    const initializeAutocomplete = (
        containerId,
        cityInputId,
        stateInputId,
        codeInputId, // Include code input for postal code
        countryCodeId, // Add country code input ID
        countryNameId,
        formattedAddressId
    ) => {
        const container = document.getElementById(containerId);
        const cityInput = document.getElementById(cityInputId);
        const stateInput = document.getElementById(stateInputId);
        const codeInput = document.getElementById(codeInputId);
        const countryInput = document.getElementById(countryCodeId);
        const countryNameInput = document.getElementById(countryNameId);
        const formattedAddressInput =
            document.getElementById(formattedAddressId);

        let filteredAddresses = []; // Store filtered addresses

        if (container) {
            Radar.ui.autocomplete({
                container: containerId,
                width: "1000px", // Set desired width
                maxHeight: "600px", // Set max height of the results box
                placeholder: "Search address",
                limit: 10, // Limit the number of results to show
                minCharacters: 3, // Minimum characters before showing suggestions
                near: null, // Use default IP-based location
                debounceMS: 100, // Wait time before fetching results
                responsive: false, // Make the input responsive
                // layers: ["postalCode", "fine", "address"],

                onResults: (addresses) => {
                    // Filter addresses that have postal codes and state codes
                    filteredAddresses = addresses.filter(
                        (address) => address.postalCode
                    );

                    // Log the filtered results
                    console.log(
                        "Filtered Results (with postalCode):",
                        filteredAddresses
                    );

                    // Optional: You can manipulate the UI here based on filteredAddresses
                },
                onSelection: (address) => {
                    // Extract city and postal code from the selected address
                    const city =
                        address.city || address.county || address.state || "";
                    const postalCode = address.postalCode || "00000"; // Default postal code if missing
                    const stateCode = address.stateCode || "";
                    const countryCode = address.countryCode || "";
                    const countryName = address.country || "";

                    const formattedAddress = address.formattedAddress;
                    const addressParts = formattedAddress.split(",");
                    let trimmedAddress;

                    if (formattedAddress.length > 35) {
                        // If characters are greater than 35, trim the address before the first comma if needed
                        const firstPart = addressParts[0].trim();
                        trimmedAddress =
                            firstPart.length > 35
                                ? firstPart.slice(0, 25)
                                : firstPart;
                    } else {
                        // Use the first and second comma-separated parts if <= 35 characters
                        trimmedAddress = addressParts
                            .slice(0, 2)
                            .join(",")
                            .trim();
                    }

                    console.log(trimmedAddress);

                    let processedState = "";

                    if (stateCode) {
                        // Use state code directly if available
                        processedState = stateCode;
                    } else if (
                        address.state &&
                        typeof address.state === "string"
                    ) {
                        // Process state name if it's available and is a string
                        console.log(address.state);
                        processedState = address.state
                            .slice(0, 2)
                            .toUpperCase();
                        console.log(processedState);
                    } else {
                        processedState = countryCode; // Default if neither state nor country code is available
                        console.warn(
                            "Both state and country code are unavailable for the selected address."
                        );
                    }

                    // Update the input fields with city, state, and postal code
                    if (codeInput) codeInput.value = postalCode; // Set postal code
                    if (cityInput) cityInput.value = city; // Set city
                    if (stateInput) stateInput.value = processedState; // Set processed state
                    if (countryInput) countryInput.value = countryCode;
                    if (countryNameInput) countryNameInput.value = countryName;
                    if (formattedAddressInput)
                        formattedAddressInput.value = trimmedAddress;

                    console.log(
                        "City, state, and postal code updated successfully!"
                    ); // Success feedback
                    console.log(
                        "Selected address without postal code:",
                        address
                    );
                },
                onError: (error) => {
                    console.log("Error fetching addresses:", error);
                },
            });
        } else {
            console.error(
                `Autocomplete container with ID '${containerId}' not found. Please check your HTML.`
            );
        }
    };

    // Initialize autocomplete for both containers with the correct number of arguments
    initializeAutocomplete(
        "recipientStreet",
        "recipientCity",
        "recipientstateOrProvinceCode",
        "inputToZip",
        "toCountry", // Now correctly passed
        "recipientCountryName",
        "reFormattedAddress"
    );
    initializeAutocomplete(
        "shipperStreet",
        "shipperCity",
        "shipperstateOrProvinceCode",
        "inputFromZip",
        "fromCountry", // Now correctly passed
        "shipperCountryName",
        "shFormattedAddress"
    );
});
