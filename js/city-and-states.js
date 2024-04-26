jQuery(document).ready(function ($) {
  $("#city-state-form").on("submit", function (e) {
    e.preventDefault();

    var state = $("#state-input").val();
    var city = $("#city-input").val();
    var postal_code = $("#postal-code-input").val();

    $.ajax({
      type: "POST",
      url: city_state_ajax.ajaxurl,
      data: {
        action: "get_city_and_state_ajax",
        state: state,
        city: city,
        postal_code: postal_code,
      },
      success: function (response) {
        var result = JSON.parse(response);

        console.log(result)
        // $("#city-state-result").html(
        //   "City: " + result.city + "<br>State: " + result.state
        // );
      },
      error: function (error) {
        console.error("errorings", error);
      },
    });
  });
});
