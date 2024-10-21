<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autocomplete with Radar API</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://js.radar.com/v4.4.2/radar.css" rel="stylesheet">
    <style>
        .dropdown-menu {
            max-height: 200px;
            overflow-y: auto;
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <h1>Search for Places</h1>
        <!-- Search input -->
        <div class="mb-3 position-relative">
            <input type="text" class="form-control" id="searchInput" placeholder="Start typing an address...">
            <!-- Dropdown menu -->
            <ul class="dropdown-menu w-100" id="dropdownMenu"></ul>
        </div>
    </div>

    <h2 class="text-primary" style="color: rgba(0, 0, 0, 0.959);">3hirdK - dev</h2>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://js.radar.com/v4.4.2/radar.min.js"></script>

    <script>
        const searchInput = document.getElementById('searchInput');
        const dropdownMenu = document.getElementById('dropdownMenu');

        // Listen for input events on the search field
        searchInput.addEventListener('input', function () {
            const query = searchInput.value.trim();

            if (query.length >= 3) {
                // Create a new XMLHttpRequest
                const xhr = new XMLHttpRequest();
                xhr.open('GET', `/search?query=${encodeURIComponent(query)}`, true);

                // Set up the callback function to handle the response
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        const result = JSON.parse(xhr.responseText);
                        const allAdd = result.addresses;

                        // Clear previous results before displaying new ones
                        clearDropdown();

                        // Use a Set to track unique entries
                        const uniqueEntries = new Set();

                        // Process and display each address
                        if (allAdd && allAdd.length > 0) {
                            allAdd.forEach((item) => {
                                const country = item.country;
                                const code = item.countryCode;
                                const county = item.county && item.county.name ? item.county.name : '';
                                console.log(item.postalCode);

                                // Only display if country and country code are valid
                                if (country && code) {
                                    const response = county ? `${country} ${county} ${code}` : `${country} ${code}`;

                                    // Add to the Set to avoid duplicates
                                    uniqueEntries.add(response);
                                }
                            });

                            // Create dropdown items from unique entries
                            uniqueEntries.forEach((entry) => {
                                const li = document.createElement('li');
                                li.classList.add('dropdown-item');
                                li.textContent = entry; // Use unique response

                                // Add click event to set the selected address in the input field
                                li.addEventListener('click', () => {
                                    searchInput.value = entry; // Set selected address
                                    clearDropdown(); // Hide the dropdown after selection
                                });

                                dropdownMenu.appendChild(li); // Add the item to the dropdown menu
                            });

                            dropdownMenu.classList.add('show'); // Show the dropdown if there are valid items
                        }
                    }
                };

                // Send the request
                xhr.send();
            } else {
                // Clear the dropdown if input is less than 3 characters
                clearDropdown();
            }
        });

        // Function to clear dropdown
        function clearDropdown() {
            dropdownMenu.innerHTML = '';
            dropdownMenu.classList.remove('show');
        }
    </script>

</body>
</html>
