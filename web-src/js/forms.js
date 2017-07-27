var simplemde = new SimpleMDE({ element: document.getElementById("site_notes") });

$('#site_siteCompletedAt, #site_slaEndAt').datetimepicker({
    useCurrent: false,
    format: 'DD.MM.YYYY'
});

$("#site_responsibleManager, #site_developer, #site_client").select2();

$("#site_project").select2({
    placeholder: 'Project Name',
    minimumInputLength: 3,
    cache: true,
    ajax: {
        url:  "/app_dev.php/project/typeahead",
        dataType: "json",
        type: "GET",
        delay: 1000,
        data: function (params) {
            return {
                q: params.term
            };
        },
        processResults: function (data) {
            return {
                results: $.map(data, function (item) {
                    return {
                        text: item.name,
                        id: item.id
                    }
                })
            };
        }
    }
}).on('change', function () {
    $.ajax({
        type: 'GET',
        url: "/app_dev.php/project/members",
        data: {
          project:  $('#site_project').val()
        },
        success: function (data) {
            $('#site_developer, #site_responsibleManager').val('');
            $('#site_developer option, #site_responsibleManager option').each(function () {
                if($(this).attr('value') !=''){
                    $(this).remove();
                }
            }).trigger('change');
            if (data.client) {
                $('#site_client').val(data.client.id).trigger('change');
            }

            data.developers.forEach(function (item) {
                $('#site_developer').append('<option value="'+item.id+'">'+item.name+'</option>')
            });

            data.managers.forEach(function (item) {
                $('#site_responsibleManager').append('<option value="'+item.id+'">'+item.name+'</option>')
            });
        }
    });
    $('#site_responsibleManager').removeAttr('disabled');
    $('#site_developer').removeAttr('disabled');
});

$('#site_client').on('change', function () {
    if ($(this).val() == '') {
        $('#site_newClient').show();
    } else {
        $('#site_newClient').hide();
    }
}).change();

(function($) {
    $.fn.closest_descendent = function(filter) {
        var $found = $(),
            $currentSet = this; // Current place
        while ($currentSet.length) {
            $found = $currentSet.filter(filter);
            if ($found.length) break;  // At least one match: break loop
            // Get all children of the current set
            $currentSet = $currentSet.children();
        }
        return $found.first(); // Return first match of the collection
    }
})(jQuery);
$('.form-collection').each(function(i, formCollection) {
    var $formCollection = $(formCollection),
        $elements = $formCollection.find('.form-collection-elements'),
        idx = $elements.find('.form-collection-element').length;
    $formCollection.find('.form-collection-add').on('click', function() {
        var prototype = $(this).data('prototype');
        prototype = prototype.replace(/__name__/g, idx++);
        $(prototype).appendTo($(this).closest('.form-collection').find('.form-collection-elements'));
        refreshTogles($(this).closest('.form-collection').find('.form-collection-element:last-child'));
    });
    $elements.on('click', '.form-collection-delete', function() {
        $(this).parents('.form-collection-element').remove();
    });
});

function loginTypeToggle($input) {
    var val = $input.val(),
        $root = $input.closest('.form-group').parent();

    $root.find('> .form-group > div > [data-login-type]:not([data-login-type~="'+val+'"])').each(function() {
        $(this).closest('.form-group').addClass('hide');
    });

    $root.find('> .form-group > div > [data-login-type~="'+val+'"]').each(function() {
        $(this).closest('.form-group').removeClass('hide');
    });

}

$(document).on('change', '[data-login-type-toggle] input', function() {
    loginTypeToggle($(this));
});

$('#site_framework').on('change', function() {
    $('#site_frameworkVersion').val($(this).find('option[value='+$(this).val()+']').attr('data-framework-version'));
}).change();

function refreshTogles(elem) {
    if (elem) {
        elem.find('[data-login-type-toggle] [checked]').each(function(i, input) {
            loginTypeToggle($(input));
        });
    } else {
        $('[data-login-type-toggle] [checked]').each(function(i, input) {
            loginTypeToggle($(input));
        });
    }
}
refreshTogles(false);