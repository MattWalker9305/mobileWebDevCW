$(document).ready(function() {

    $("#edit-profile").click(function() {
        $(".profile-display").hide();
        $(".profile-input").show();
        $("#edit-profile").hide();
        $("#save-profile, #cancel-edit").show();
    });

    $("#cancel-edit").click(function() {
        // Reset inputs back to original displayed values before hiding
        $(".profile-input").each(function() {
            var displayId = "#display-" + $(this).attr("id");
            $(this).val($(displayId).text());
        });
        $(".profile-input").hide();
        $(".profile-display").show();
        $("#save-profile, #cancel-edit").hide();
        $("#edit-profile").show();
        $("#profile-message").html("");
    });

    $("#save-profile").click(function() {
        $(this).prop("disabled", true).text("Saving...");

        $.ajax({
            url: "update_profile.php",
            method: "POST",
            dataType: "json",
            data: {
                fname:           $("#fname").val(),
                lname:           $("#lname").val(),
                defaultCurrency: $("#defaultCurrency").val()
            },
            success: function(response) {
                if (response.success) {
                    // Update the display spans with new values
                    $("#display-fname").text($("#fname").val());
                    $("#display-lname").text($("#lname").val());
                    $("#display-defaultCurrency").text($("#defaultCurrency").val());

                    // Swap back to display mode
                    $(".profile-input").hide();
                    $(".profile-display").show();
                    $("#save-profile, #cancel-edit").hide();
                    $("#edit-profile").show();

                    $("#profile-message").html("<p class='text-success'>" + response.message + "</p>");
                } else {
                    $("#profile-message").html("<p class='text-danger'>" + response.message + "</p>");
                }
            },
            error: function() {
                $("#profile-message").html("<p class='text-danger'>Something went wrong. Please try again.</p>");
            },
            complete: function() {
                $("#save-profile").prop("disabled", false).text("Save Changes");
            }
        });
    });

});