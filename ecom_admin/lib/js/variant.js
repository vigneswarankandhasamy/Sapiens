/*-----------------------------------------------------------
                    Product Variant Functions 
------------------------------------------------------------*/

var options_array = [];
var variants_array = [];
var layout = "";


//-------- Selling Price & Actual Price & Stock Validation ---------//

$(document).on('change', '#selling_price', function() {
    var selling_price = $(this).val();
    $("#actual_price").attr({
        'min': parseInt(selling_price) + 1
    });
});

$(document).on('change', '#actual_price', function() {
    var selling_price = $("#selling_price").val();
    if (selling_price == "") {
        $(this).val("");
        Swal.fire({
            icon: 'warning',
            title: 'Selling Price Missing',
            text: 'Please enter the Selling Price first'
        })
    }
});


$(document).on('change', '#track_stock', function() {
    if ($(this).is(":checked")) {
        $("#stock").attr({
            'required': true
        });
        $("#stock_id_wrap em").show();
    } else {
        $("#stock").attr({
            'required': false
        });
        $("#stock_id_wrap em").hide();
    }
});

//-------- Add Variant Option ---------//

// Add Variant Option drop down

$(document).on('click', '.add_variant_option', function() {
    var previous_variants = $(".option_variant_" + variant_option_count).val();
    var previous_option = $(".option_title_" + variant_option_count).val();
    if (previous_variants != "" && previous_option != "") {
        variant_option_count = (variant_option_count + 1);
        var option_id = variant_option_count;
        addVaritantOption(option_id);
        $(".variant_option_select").each(function(index) {
            var current_value = $(this).val();
            var current_option = $(this).data("option");
            if (current_option != option_id) {
                $("#option_title_" + option_id + " option[value='" + current_value + "']").remove();
            }
        });
        if (variant_count_actual == 3) {
            $("#add_variant_option").hide();
        }
    } else {
        if (previous_variants == "") {
            $("#variant_error_" + variant_option_count).html("Please enter these variants ");
            $("#generateVariants").hide();
        } else {
            $("#generateVariants").show();
        }
        if (previous_option == "") {
            $("#option_error_" + variant_option_count).html("Select/ Add option");
        }
    }
});

// Print Option rows

function addVaritantOption(option_id) {
    var html = '';
    html += '<div class="row" id="option_row_' + option_id + '">';
    html += '<div class="col-md-3"><div class="form-group"><label class="form-label">Option <em>*</em></label>';
    html += '<div class="form-control-wrap">';
    html += '<select class="form-control form-control-lg variant_option_select  option_title_' + option_id + '" id="option_title_' + option_id + '" data-option="' + option_id + '" data-search="on" name="options[' + option_id + '][title]" required>';
    html += '<option value="Size">Size</option><option value="Color">Color</option><option value="Material">Material</option><option value="Style">Style</option>';
    html += '  </select><p class="help_text">* Type to add option</p><p class="text-danger" id="option_error_' + option_id + '"></p></div> </div> </div>';
    html += ' <div class="col-md-8"><div class="form-group"><label class="form-label">Enter Variants</label>';
    html += '<select multiple="multiple" data-role="tagsinput" name="options[' + option_id + '][value][]" data-option="' + option_id + '"  class="form-control tags_input option_variant_' + option_id + '"></select><p class="text-danger" id="variant_error_' + option_id + '"></p>';
    html += ' </div></div>';
    html += '<div class="col-md-1"><div class="form-group"> <label class="form-label"> &nbsp; </label>';
    html += '<p><button type="button" class="btn btn-trigger btn-icon remove_variant_option " data-toggle="tooltip" data-placement="top" data-original-title="Delete Option" data-option="' + option_id + '"> <em class="icon ni ni-trash" ></em>  </button></p></div></div></div>';
    $("#variant_options_wrap").append(html);

    $(".variant_option_select").select2({
        tags: true
    });
    $(".tags_input").tagsinput('items');
    saveOptions();
    variant_count_actual = variant_count_actual + 1;
}

// Variant Option Change function

$(document).on('change', '.variant_option_select', function() {
    var value = $(this).val();
    var option = $(this).data("option");

    if (value == "") {
        $("#option_error_" + variant_option_count).html("Select/ Add option");
    } else {
        if (options_array.indexOf(value) != -1) {
            $("#option_error_" + option).html("Duplicate option - " + value);
            $("#option_title_" + option + " option[value='" + value + "']").remove();
            setTimeout(function() {
                $("#option_error_" + option).html("");
            }, 2000);

            var new_value = $(this).val();
            const index = options_array.indexOf(value);
            if (index > -1) {
                options_array.splice(index, 1);
            }
            options_array.push(value);
            saveOptions();
        } else {
            const index = options_array.indexOf(value);
            if (index > -1) {
                options_array.splice(index, 1);
            }
            options_array.push(value);
            saveOptions();
            $("#option_error_" + option).html("");
        }
    }
});

// Variant value Change function

$(document).on('change', '.tags_input', function() {
    var value = $(this).val();
    var option = $(this).data("option");

    if (value == "") {
        $("#variant_error_" + option).html("Please enter these variants ");
        $("#generateVariants").hide();
    } else {
        groupVariants();
        $("#variant_error_" + option).html("");
        $("#generateVariants").show();
    }

});

// Save the Options

function saveOptions() {
    options_array = [];
    $(".variant_option_select").each(function(index) {
        options_array.push($(this).val());
    });
}

// Has variants Checkbox

$(document).on('change', '#has_variants', function() {
    if ($(this).is(":checked")) {
        $("#variant_parent").show();
        if (variants_array.length > 0) {
            $("#variant_child").show();
        }

        $("#submit_button").attr({
            'disabled': true
        });
    } else {
        $("#variant_parent").hide();
        $("#variant_child").hide();
        $("#submit_button").attr({
            'disabled': false
        });
    }
});

//  Remove Variant Option 


$(document).on('click', '.remove_variant_option', function() {
    var current_option_id = $(this).data("option");

    var current_variant = $(".option_variant_" + current_option_id).val();
    var current_option = $(".option_title_" + current_option_id).val();

    $("#option_row_" + current_option_id).remove();
    const index = options_array.indexOf(current_option);
    if (index > -1) {
        options_array.splice(index, 1);
        saveOptions();
    }
    variant_count_actual = variant_count_actual - 1;
    if (variant_count_actual < 3) {
        $("#add_variant_option").show();
        $("#generateVariants").show();
    }
});


//-------- Variants List Generation ---------//

// Group Variants vs Option

function groupVariants() {
    layout = "";
    variants_array = [];
    $(".tags_input").each(function(index) {
        var option = $(this).data("option");
        variants_array.push($(this).val());
    });
}

// Form the Variants Multiple Combinations

function getVariantsListArray(args) {
    var r = [],
        max = args.length - 1;

    function helper(arr, i) {
        for (var j = 0, l = args[i].length; j < l; j++) {
            var a = arr.slice(0); // clone arr
            a.push(args[i][j]);
            if (i == max)
                r.push(a);
            else
                helper(a, i + 1);
        }
    }
    helper([], 0);
    return r;
}

// Generate Variants

$(document).on('click', '#generateVariants', function() {
	groupVariants();
    var previous_variants = $(".option_variant_" + variant_option_count).val();
    var previous_option = $(".option_title_" + variant_option_count).val();
    if (previous_variants != "" && previous_option != "") {
        Swal.fire({
            title: "Are you sure to generate variants?",
            text: "Once confirmed the options will be disabled. However you can edit the variants !!",
            icon: 'warning',
            showCancelButton: true,
            showCloseButton: true,
            confirmButtonText: "Yes"
        }).then((result) => {
            if (result.value) {

                $("#variant_list_body").html("");

                // Add Variant Rows
                var variants = [];
                variants = getVariantsListArray(variants_array);

                $("#variant_child").toggle();
                var item_name = "";
                var token = "";

                for (var i = 0; i < variants.length; i++) {
                    item_name = "";
                    token = "";
                    var row = variants[i];
                    for (var j = 0; j < row.length; j++) {
                        if (j == 0) {
                            var slash = "";
                            var hypen = "";
                        } else {
                            var slash = " / ";
                            var hypen = "-";
                        }
                        item_name += slash + row[j];
                        token += hypen + row[j];
                    }
                    generateVariants(item_name, token, i);
                }
                saveOptions();
                $("#variant_child").show();
                $("#variant_options_wrap").hide();
                $("#variant_actions").hide();
                $("#variant_option_edit").show();
                $("#variant_options_preview_wrap").show();
                optionsPreview();
                $("#submit_button").attr({
                    'disabled': false
                });
            }
        });
    } else {
        if (previous_variants == "") {
            $("#variant_error_" + variant_option_count).html("Please enter these variants ");
            $("#generateVariants").hide();
            $("#submit_button").attr({
                'disabled': true
            });
        } else {
            $("#generateVariants").show();
        }
        if (previous_option == "") {
            $("#option_error_" + variant_option_count).html("Select/ Add option");
        }
    }
});


// Create the variant rows

function generateVariants(variant_name, token, row) {
    var selling_price = $("#selling_price").val();
    var actual_price = $("#actual_price").val();
    var html = "";
    html += '<tr id="variant_row_' + row + '">';
    html += '<td><p id="item_value_' + row + '">' + variant_name + '</p>';
    html += '<input type="hidden" name="variants[' + row + '][token]" value="' + token + '" > <input type="hidden" name="variants[' + row + '][variant_name]" value="' + variant_name + '"> </td>';
    html += '<td><input type="number" min="1" name="variants[' + row + '][selling_price]" value="' + selling_price + '" class="form-control" placeholder="" required></td>';
    html += '<td><input type="number" min="1" name="variants[' + row + '][actual_price]" value="' + actual_price + '"  class="form-control" placeholder="" ></td>';
    html += '<td><input type="number" min="0" val="0" name="variants[' + row + '][stock]" class="form-control" placeholder="" ></td>';
    html += '<td><input type="text" name="variants[' + row + '][sku]" class="form-control" placeholder=""></td>';
    html += '<td><button type="button" class="btn btn-trigger btn-icon delete_variant" data-option="' + row + '" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete Variant"><em class="icon ni ni-trash-fill"></em></button></td></tr>';
    $("#variant_list_body").append(html);
}

// Delete Individual Variants

$(document).on('click', '.delete_variant', function() {
    var option = $(this).data("option");
    Swal.fire({
        title: "Are you sure to remove the variant?",
        text: "Once confirmed the current variant will be deleted permanently. You need to recreate the variants again by clicking edit variant option !!",
        icon: 'warning',
        showCancelButton: true,
        showCloseButton: true,
        confirmButtonText: "Yes"
    }).then((result) => {
        if (result.value) {
            $("#variant_row_" + option).remove();
        }
    });
});

// Edit Variants

$(document).on('click', '#editVariants', function() {
    Swal.fire({
        title: "Are you sure to edit the variants?",
        text: "Once confirmed the current variants will be deleted permanently. You need to recreate the variants again !!",
        icon: 'warning',
        showCancelButton: true,
        showCloseButton: true,
        confirmButtonText: "Yes"
    }).then((result) => {
        if (result.value) {
            $("#variant_child").hide();
            $("#variant_option_edit").hide();
            $("#variant_options_preview_wrap").hide();
            $("#variant_options_wrap").show();
            $("#variant_actions").show();
            $("#variant_list_body").html("");
            $("#variant_option_preview").html("");
            $("#submit_button").attr({
                'disabled': true
            });
        }
    });
});


// Create the Options preview

function optionsPreview() {
    var html = '';
    for (var i = 0; i < options_array.length; i++) {
        var item_name = "";
        var row = variants_array[i];
        for (var j = 0; j < row.length; j++) {
            if (j == 0) {
                var comma = "";
            } else {
                var comma = ", ";
            }
            item_name += comma + row[j];
        }
        html += '<tr><td>' + options_array[i] + '</td>';
        html += '<td> ' + item_name + '</td></tr>';
    }
    $("#variant_option_preview").append(html);
}

//-------- Category Validation ---------//

$(document).on('click', '.category_type', function() {
    var value = $(this).val();
    if (value == "sub") {
        $("#sub_category_wrap").show();
        $("#main_category_wrap").hide();
    } else {
        $("#sub_category_wrap").hide();
        $("#main_category_wrap").show();
    }
});


/*-----------------------------------------------------------
           	Product Attributes Functions 
------------------------------------------------------------*/


// Get Current rows

function getAttributeRowsCount() {
    var i = 0;
    $(".attribute_row").each(function(index) {
        i++;
    });
    return i + 1;
}

var attribute_row = getAttributeRowsCount();
var attribute_array = [];

// Add Attributes

function addAttribute() {
    if (attribute_row > 1) {
        var last_option = $(".attribute_row:last-child").data("option");
        var last_value = $('input[name=\'product_attribute[' + last_option + '][name]\']').val();
        if (last_value == "") {
            $("#error_" + last_option).html("Please enter this value");
            var check = false;
        } else {
            var check = true;
        }
    } else {
        var check = true;
    }

    if (check) {
        html = '<tr id="attribute-row' + attribute_row + '" class="attribute_row" data-option="' + attribute_row + '">';
        html += '  <td><div class="autocomplete_item"><input type="text" name="product_attribute[' + attribute_row + '][name]" placeholder="" class="form-control" required/><input type="hidden" name="product_attribute[' + attribute_row + '][attribute_id]" data-option="' + attribute_row + '" class="product_attribute" value="" /><input type="hidden" name="product_attribute[' + attribute_row + '][attribute_group_id]" value="" /><p class="text-danger" id="error_' + attribute_row + '"></p></div></td>';
        html += '  <td>';
        html += '<input type="text" name="product_attribute[' + attribute_row + '][product_attribute_description]" rows="1" placeholder="Enter Attribute Description" class="form-control" required />';
        html += '  </td>';
        html += '  <td><button type="button" onclick="$(\'#attribute-row' + attribute_row + '\').remove();" class="btn btn-trigger btn-icon remove_variant_option " > <em class="icon ni ni-trash" ></em>  </button></td>';
        html += '</tr>';
        $('#attribute tbody').append(html);
        attributeautocomplete(attribute_row);
        attribute_row++;
    }
}

// Attribute Auto Complete

function attributeautocomplete(attribute_row) {
    $('input[name=\'product_attribute[' + attribute_row + '][name]\']').autocomplete({
        'source': function(request, response) {
            $.ajax({
                url: core_path + "products/api/attributes_autocomplete?filter_name=" + encodeURIComponent(request),
                dataType: 'json',
                success: function(json) {

                    response($.map(json, function(item) {
                        return {
                            category: item.attribute_group,
                            label: item.name,
                            value: item.attribute_id,
                            group_id: item.attribute_group_id
                        }
                    }));
                }

            });
        },
        'select': function(item) {
            $("#error_" + attribute_row).html("");
            $('input[name=\'product_attribute[' + attribute_row + '][name]\']').val(item['label']);
            $('input[name=\'product_attribute[' + attribute_row + '][attribute_id]\']').val(item['value']);
            $('input[name=\'product_attribute[' + attribute_row + '][attribute_group_id]\']').val(item['group_id']);
            checkPrevious(item['value'], attribute_row);
        }
    });
}

$(".editAttr").focus(function() {
    var option = $(this).data("option");
    attributeautocomplete(option);
});

// Check previous for attribute

function checkPrevious(attribute_id, row) {
    attribute_array = [];
    $(".product_attribute").each(function(index) {
        var value = $(this).val();
        var option = $(this).data("option");
        if (option != row) {
            attribute_array.push(value);
            $("#error_" + row).html("");
        } else {
            const index = attribute_array.indexOf(attribute_id);
            if (index > -1) {
                $('input[name=\'product_attribute[' + row + '][name]\']').val("");
                $('input[name=\'product_attribute[' + row + '][attribute_id]\']').val("");
                $('input[name=\'product_attribute[' + row + '][attribute_group_id]\']').val("");
                $("#error_" + row).html("Duplicate attribute !!");
            }
        }
    });
}

/*-----------------------------------------------------------
           	Product Related Functions 
------------------------------------------------------------*/

// Search Related products function

$('#search_related_items').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: core_path + "products/api/products_autocomplete?filter_name=" + encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        category: item['category'],
                        label: item['name'],
                        value: item['product_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('input[name=\'shipping_location\']').val('');
        $('#related_items_' + item['value']).remove();
        $('#related_items_list').append('<tr id="related_items_' + item['value'] + '"><td> ' + item['label'] + '<input type="hidden" name="related_items[' + item['value'] + '][item_id]" value="' + item['value'] + '"/></td><td><select class="form-select" name="related_items[' + item['value'] + '][item_type]"> <option value="related">Related Items</option><option value="bought_together">Bought Together</option></select></td><td><button class="btn btn-icon remove_related" data-option="' + item['value'] + '"><em class="icon ni ni-trash-fill"></em></button></td></tr>');
        $(".form-select").select2({
            tags: true
        });
    }
});

// Remove Related items

$(document).on('click', '.remove_related', function() {
    var option = $(this).data("option");
    $("#related_items_" + option).remove();
});