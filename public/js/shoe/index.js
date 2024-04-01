$(document).ready(function () {
    var typeTimer;

    var factoryDatalistUrl = $(".search-factory").attr("data-datalist");
    var categoryDatalistUrl = $(".search-category").attr("data-datalist");
    var colorDatalistUrl = $(".search-color").attr("data-datalist");
    console.log(categoryDatalistUrl);

    $.get(factoryDatalistUrl, [], function (data) {
        $("#site-content").append(data);
    });

    $.get(categoryDatalistUrl, [], function (data) {
        $("#site-content").append(data);
    });

    $.get(colorDatalistUrl, [], function (data) {
        $("#site-content").append(data);
    });

    document.addEventListener("input", function (e) {
        if (e.target.getAttribute("form") != "search-form") {
            return;
        }
        clearTimeout(typeTimer);
        typeTimer = setTimeout(function () {
            var form = $("#search-form");
            var url = form.prop("target");
            var data = form.serialize();
            var path = url + "?" + data;
            $.get(path, [], function (data) {
                $("#inventory-table tbody").html(data.tbody);
                $("#pagination-wrapper").html(data.pagination);
                window.history.pushState({}, "", path);
            });
        }, 1000);
    });

    $(".shoe-image-link").click(function (e) {
        e.preventDefault();
        var originalImagePath = $(this).find("img").prop("src");
        $("#shoe-image-modal img").prop("src", originalImagePath);
    });

    $(document).on(
        "change",
        "#shoe-factory, #shoe-category, #shoe-color",
        function (e) {
            if ($(this).val() == "") {
                $(this).val($(this).data("oldval"));
                return;
            }
            var datalistId = "#" + $(this).attr("list");
            var option = $(
                datalistId + ' option[value="' + $(this).val() + '"]'
            );
            if (option.length == 0) {
                var model = "";
                if ($(this).prop("id") == "shoe-factory") {
                    model = "মহাজন";
                } else if ($(this).prop("id") == "shoe-category") {
                    model = "জুতার টাইপ";
                } else {
                    model = "রং";
                }
                alert("এই নামে কোন " + model + " নেই।");
                $(this).val($(this).data("oldval"));
                $(this).focus();
                return;
            }
            $(this).next().val(option.attr("data-id"));
            $(this).data("oldval", $(this).val());
        }
    );
});

function formModalAfterCallback() {
    $("#shoe-factory").data("oldval", $("#shoe-factory").val());
    $("#shoe-category").data("oldval", $("#shoe-category").val());
    $("#shoe-color").data("oldval", $("#shoe-color").val());
}

document.getElementById("check_all").addEventListener("click", function () {
    var checkboxes = document.querySelectorAll(
        'input[name="selected_shoes[]"]'
    );
    checkboxes.forEach(function (checkbox) {
        checkbox.checked = document.getElementById("check_all").checked;
    });
});



$("#download-images-btn").on("click", function (event) {
    event.preventDefault();
    var selectedShoes = $('input[name="selected_shoes[]"]:checked');
    var selectedShoeIds = [];
    var downloadUrl = $(this).attr("href");

    selectedShoes.each(function () {
        selectedShoeIds.push($(this).val());
    });

    var token = $('meta[name="csrf-token"]').attr("content");
    $.ajax({
        url: downloadUrl,
        type: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": token,
        },
        data: JSON.stringify({ id: selectedShoeIds }),
        success: function (data) {
            if (data.success) {
                window.location.href = data.file_name;

                setTimeout(function () {
                    var token = $('meta[name="csrf-token"]').attr("content");
                    $.ajax({
                        url: "shoe/download/delete",
                        type: "POST",
                        headers: {
                            "X-CSRF-TOKEN": token,
                        },
                        data: { file_url: data.file_name },
                        success: function (response) {
                            console.log("Zip file deleted successfully");
                        },
                        error: function (xhr, status, error) {
                            console.error("Error deleting zip file:", error);
                        },
                    });
                }, 3000); // 3 seconds
            } else {
                console.error("Error:", data.error);
                // $("#error-message").text("Error: " + data.error);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error:", error);
            // $("#error-message").text("Error: " + error);
        },
    });
});
