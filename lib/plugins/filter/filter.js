$(document).ready(function() {




});

// Form Filter tokens

function tokenize_query_string() {
    var tokens = {};
    var query_string = window.location.search;
    var token_strings = query_string.replace('?', '').split('&');
    for (var i = 0; i < token_strings.length; i++) {
        if (token_strings[i] != '') {
            var values = token_strings[i].split('=');
            var token_values = values[1].split(",");
            tokens[values[0]] = token_values;
        }
    }
    return tokens;
}

function generate_query_string_from_tokens() {
    var query_string = '?';
    for (var key in QUERY_TOKENS) {
        query_string = query_string + key + '=' + QUERY_TOKENS[key].join(",") + '&';
    }
    return query_string.substring(0, query_string.length - 1); //Chopping off the extra '&' at the end
}

function remove_value_from_query_tokens(key, value) {
    var value = value.replace('%2B', '+');
    if (!(key in QUERY_TOKENS)) {
        return;
    }
    var index = QUERY_TOKENS[key].indexOf(value);
    if (index != -1) {
        QUERY_TOKENS[key].splice(index, 1);
    }
    if (QUERY_TOKENS[key].length == 0) {
        delete QUERY_TOKENS[key];
    }
}

function add_value_to_query_tokens(key, value) {
    if (key in QUERY_TOKENS) {
        QUERY_TOKENS[key].push(value);
    } else {
        QUERY_TOKENS[key] = [value];
    }
}

$('.filter_option').off('click').on('click', function() {
    var facet_id = $(this).parents('.ecom_filter_type').attr('id').replace('_ecom_filter', '');
    var rawvalue = $(this).data('value');
    var val = encodeURIComponent(rawvalue);
    if ($(this).attr('checked')) {
        $(this).removeAttr('checked');
        remove_value_from_query_tokens(facet_id, val);
        $(".page_loading").show();
        window.location = window.location.pathname + generate_query_string_from_tokens(QUERY_TOKENS);
    } else {
        $(this).addClass('selected');
        add_value_to_query_tokens(facet_id, val);
        $(".page_loading").show();
        window.location = window.location.pathname + generate_query_string_from_tokens(QUERY_TOKENS);
    }
});

//Fliter remove
$('.alumbook_remove_filter').click(function(e) {
    e.preventDefault();
    //alert($(this).data('value'));
    remove_value_from_query_tokens($(this).data('filter-id'), encodeURIComponent($(this).data('value')));
    $(".page_loading").show();
    window.location = window.location.pathname + generate_query_string_from_tokens(QUERY_TOKENS);
});

var QUERY_TOKENS = tokenize_query_string();
var page_num = 1;
var gotallresults = false;
//console.log(QUERY_TOKENS);

function reinitMasonary(load) {
    if (load) {
        $('.directory_list').masonry();
    }
}

function add_search_to_query_tokens(key, value) {
    if (key in QUERY_TOKENS) {
        QUERY_TOKENS[key] = [value];
    } else {
        QUERY_TOKENS[key] = [value];
    }
}

function filter_string() {
    var tokens = {};
    var query_string = window.location.search;
    var token_strings = query_string.replace('?', '').split('&');
    for (var i = 0; i < token_strings.length; i++) {
        if (token_strings[i] != '') {
            var values = token_strings[i].split('=');
            var token_values = values[1];
            tokens[values[0]] = token_values;
        }
    }
    return tokens;
}
var FILTER_TOKENS = filter_string();

// Load More Data

var track_load = 1; //total loaded record group(s)
var loading = false; //to prevents multipal ajax loads
//var total_groups = $("#count").val();

function reIntFilter() {
    $('.filter_option').off('click').on('click', function() {
        var facet_id = $(this).parents('.ecom_filter_type').attr('id').replace('_ecom_filter', '');
        var rawvalue = $(this).data('value');
        var val = encodeURIComponent(rawvalue);
        if ($(this).attr('checked')) {
            $(this).removeAttr('checked');
            remove_value_from_query_tokens(facet_id, val);
            $(".page_loading").show();
            window.location = window.location.pathname + generate_query_string_from_tokens(QUERY_TOKENS);
        } else {
            $(this).addClass('selected');
            add_value_to_query_tokens(facet_id, val);
            $(".page_loading").show();
            window.location = window.location.pathname + generate_query_string_from_tokens(QUERY_TOKENS);
        }
    });

    //Fliter remove
    $('.alumbook_remove_filter').click(function(e) {
        e.preventDefault();
        //alert($(this).data('value'));
        remove_value_from_query_tokens($(this).data('filter-id'), encodeURIComponent($(this).data('value')));
        $(".page_loading").show();
        window.location = window.location.pathname + generate_query_string_from_tokens(QUERY_TOKENS);
    });

    $("#loadbtn").click(function() {
        //alert("ff");
        var total_groups = $("#count").val();
        //alert(track_load + " < " + total_groups);
        if (track_load <= total_groups && loading == false) {
            load = false;
            var filter_load = window.location.search;
            var filter_url = filter_load.replace('?', '');
            loading = true;
            $('.loadmore').show();
            $.ajax({
                type: "POST",
                url: user_path + "resource/user_ajax_redirect.php?page=loadMoreSearch",
                dataType: "html",
                data: { result: JSON.stringify(FILTER_TOKENS), count: track_load },
                beforeSend: function(argument) {
                    $('#loadbtn').hide();
                },
                success: function(data) {
                    $(".directory_list").append(data);
                    $('.loadmore').hide();
                    $('#loadbtn').show();
                    //reinitMasonary().off();                       
                    //$('#reload').load(document.URL +  ' #reload');
                    //reinitMasonary(load);
                    track_load++;
                    loading = false;
                }
            });
        }
    });

    $("#mobile_loadbtn").click(function() {
        //alert("ff");
        var total_groups = $("#count").val();
        //alert(track_load + " < " + total_groups);
        if (track_load <= total_groups && loading == false) {
            load = false;
            var filter_load = window.location.search;
            var filter_url = filter_load.replace('?', '');
            loading = true;
            $('.loadmore').show();
            $.ajax({
                type: "POST",
                url: user_path + "resource/user_ajax_redirect.php?page=mobileLoadMoreSearch",
                dataType: "html",
                data: { result: JSON.stringify(FILTER_TOKENS), count: track_load },
                beforeSend: function(argument) {
                    $('#loadbtn').hide();
                },
                success: function(data) {
                    $(".directory_list").append(data);
                    $('.loadmore').hide();
                    $('#loadbtn').show();
                    //reinitMasonary().off();                       
                    //$('#reload').load(document.URL +  ' #reload');
                    //reinitMasonary(load);
                    track_load++;
                    loading = false;
                }
            });
        }
    });

}

// Search Form

$("form[name='search_directory']").submit(function() {
    var flag = true;
    var keyword = $("#search_key").val();
    var query = keyword.replace(' ', '+');
    if (keyword == "") {
        $("#search_key").addClass("err");
        flag = false;
    }
    if (flag) {
        $(".page_loading").show();
        add_search_to_query_tokens("k", query);
        $(".page_loading").show();
        window.location = window.location.pathname + generate_query_string_from_tokens(QUERY_TOKENS);
    }
    return false;
});

$("form[name='mobile_search_directory']").submit(function() {
    var flag = true;
    var keyword = $("#mobile_search_key").val();
    var query = keyword.replace(' ', '+');
    if (keyword == "") {
        $("#search_key").addClass("err");
        flag = false;
    }
    if (flag) {
        $(".page_loading").show();
        add_search_to_query_tokens("k", query);
        $(".page_loading").show();
        window.location = window.location.pathname + generate_query_string_from_tokens(QUERY_TOKENS);
    }
    return false;
});
