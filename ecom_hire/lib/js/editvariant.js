

// Click image thumbnail 

$(document).on('click', '.image_actions p a', function() {
    var option = $(this).data("option");

    $.ajax({
        type: "POST",
        url: core_path + "project/api/imageinfo",
        dataType: "html",
        data: { result: option },
        beforeSend: function() {
            $(".page_loading").show();
        },
        success: function(data) {

            var json = $.parseJSON(data);

            var image = json['image_path'];
            var alt = json['file_alt_name'];
            var filename = json['file_name'];
            var element = json['id'];

            $("#image_id").val(option);
            $("#image_option").val(element);
            $("#edit_image_name").val(filename);
            $("#edit_image_alt_name").val(alt);
            $("#image_object").attr({
                'src': image
            })

            $(".image_viewer_overlay").toggle();
            $(".image_viewer_wrap").toggle();

            var height = $("#image_viewer_content").innerHeight();
            var width = $("#image_viewer_content").innerWidth();

            $("#image_object").attr({
                'style': "max-height: " + (parseInt(height) - 40) + "px; width: auto"
            });
            $(".page_loading").hide();
        }
    });

});


// Update Image Name

$("#image_form").validate({
    rules: {

    },
    messages: {

    },
    submitHandler: function(form) {
        toastr.clear();
        saveImageForm();
        return false;
    }
});

// Save image

function saveImageForm() {
    var formname = document.getElementById("image_form");
    var formData = new FormData(formname);
    $.ajax({
        url: core_path + "project/api/imageedit",
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: function() {
            $(".page_loading").show();
        },
        success: function(data) {
            $(".page_loading").hide();
            var json = $.parseJSON(data);
            if (json['status'] == "ok") {
                $(".image_viewer_overlay").toggle();
                $(".image_viewer_wrap").toggle();
                $("#image_form")[0].reset();
                $("#edit_image_name").val("");
                toastr.clear();
                NioApp.Toast('<h5>Image updated successfully !!</h5>', 'success', {
                    position: 'top-right',
                    ui: 'is-light',
                    "progressBar": true,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "4000"
                });
            } else {
                toastr.clear();
                NioApp.Toast('<h5>Sorry Unexpected error occured !!</h5>', 'error', {
                    position: 'top-right',
                    ui: 'is-light',
                    "progressBar": true,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "4000"
                });
            }
        }
    });
}

// Delete Image

$(".delete_image").click(function(e) {
    Swal.fire({
        title: "Are you sure to delete the image?",
        text: "Once deleted the image cannot not be retrived !!",
        icon: 'warning',
        showCancelButton: true,
        showCloseButton: true,
        confirmButtonText: "Yes"
    }).then((result) => {
        if (result.value) {
            var image_id = $("#image_id").val();
            $.ajax({
                type: "POST",
                url: core_path + "project/api/removeImg",
                dataType: "html",
                data: { result: image_id },
                beforeSend: function() {
                    $(".page_loading").show();
                },
                success: function(data) {
                    $(".page_loading").hide();
                    var image_option = $("#image_option").val();
                    $("#image_item_" + image_option).remove();
                    $(".image_viewer_overlay").toggle();
                    $(".image_viewer_wrap").toggle();
                    $("#image_form")[0].reset();
                    $("#edit_image_name").val("");
                }
            });
        }
    });
    e.preventDefault();
    return false;
});

// Close Image Viewer

$(".closeImageViewer").click(function(e) {
    $(".image_viewer_overlay").toggle();
    $(".image_viewer_wrap").toggle();
    $("#image_form")[0].reset();
    $("#edit_image_name").val("");
    return false;
});