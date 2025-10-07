/*-----------------------------------------------------------
                    Common Functions 
------------------------------------------------------------*/

// Remove the backslash at end
$(".form-control").keyup(function() {
    var string = $(this).val();
    var clean = replaceAllBackSlash(string);
    $(this).val(clean);
});

function replaceAllBackSlash(targetStr) {
    var index = targetStr.indexOf("\\");
    while (index >= 0) {
        targetStr = targetStr.replace("\\", "");
        index = targetStr.indexOf("\\");
    }
    return targetStr;
}

function replaceAllFrontSlash(targetStr) {
    var index = targetStr.indexOf("/");
    return targetStr.replace("/", '');
}

// Toggle Form Panel

function toggleFormPanel(form_class, form_id, type) {
    $(".form_panel_overlay").toggle();
    $("." + form_class).toggle();
    if (type == 'reset') {
        $("#" + form_id)[0].reset();
    }
    return false;
}

// Open Form Panel Cick

$(".openFormPanel").click(function(e) {
    var formclass = $(this).data("formclass");
    var form = $(this).data("form");
    toggleFormPanel(formclass, form, type = "reset");
    return false;
});

// Close Form Panel

$(".closeFormPanel").click(function(e) {
    var formclass = $(this).data("formclass");
    var form = $(this).data("form");
    Swal.fire({
        title: "Are you sure to quit?",
        text: "The changes made will not be saved",
        icon: 'warning',
        showCancelButton: true,
        showCloseButton: true,
        confirmButtonText: "Yes"
    }).then((result) => {
        if (result.value) {
            toggleFormPanel(formclass, form, type = "reset");
        }
    });
    e.preventDefault();
    return false;
});

// Cancel Submit Button 

$(".cancelSubmission").click(function(e) {
    var url = $(this).data("url");
    Swal.fire({
        title: "Are you sure to quit?",
        text: "The changes made will not be saved",
        icon: 'warning',
        showCancelButton: true,
        showCloseButton: true,
        confirmButtonText: "Yes"
    }).then((result) => {
        if (result.value) {
            window.location = url;
        }
    });
    e.preventDefault();
    return false;
});

// SEO Content Pre pop pulate

$("#title_name").blur(function() {
    var string = $(this).val();
    var string = string.replace(/[^a-zA-Z ]/g, "");
    var next = string.trim();
    var url = next.replace(/ +/g, '-').toLowerCase();
    $('#page_url').val(url);
});

$("#title_name").blur(function() {
    var string = $('#title_name').val();
    if ($('#meta_title').val() == "") {
        $('#meta_title').val(string);
    }
    if ($('#meta_keyword').val() == "") {
        $('#meta_keyword').val(string);
    }
    if ($('#meta_description').val() == "") {
        $('#meta_description').val(string);
    }
    if ($('#image_name').val() == "") {
        $('#image_name').val(string);
    }
    if ($('#image_alt_name').val() == "") {
        $('#image_alt_name').val(string);
    }

});

$("#page_url").blur(function() {
    var string = $(this).val();
    var next = string.trim();
    var url = next.replace(/ +/g, '-').toLowerCase();
    $('#page_url').val(url);
});


$('#draft_button').change(function() {
    if ($(this).prop("checked") == true) {
        $("#submit_button").html("<em class='icon ni ni-check-thick'></em> Save as Draft");
    } else if ($(this).prop("checked") == false) {
        $("#submit_button").html("<em class='icon ni ni-check-thick'></em> Save & Publish");
    }
});