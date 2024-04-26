jQuery(document).ready(function ($) {
  $("#city-state-form").on("submit", function (e) {
    e.preventDefault();

    var formData = new FormData(this);
    fetch(city_state_rest.rest_url, {
        method: "POST",
        body: formData
    })
    .then(response => response.json()) // Parse JSON response
    .then(data => {
        console.log('success', data)
    })
    .catch(error => console.error("Error:", error)); // Log any errors
  });
});
