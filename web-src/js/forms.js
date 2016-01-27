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

$('input[typeahead]').each(function(i, input) {
    $(input).typeahead(null, {
        source: new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.whitespace,
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            prefetch: {
                url: $(input).attr('typeahead'),
                cache: false
            }
        }),
        remote: {
            cache: false,
            ttl: 0
        }
    })
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

var oldFramework = '';
var oldFrameworkVersion = '';

$(document).on('change', '#appbundle_site_framework', function() {
    $('#appbundle_site_frameworkVersion').val(oldFramework==$(this).val()?oldFrameworkVersion:$(this).find('option[value='+$(this).val()+']').attr('data-framework-version'));
});

if ($("#appbundle_site_frameworkVersion").size()){
    oldFrameworkVersion = $("#appbundle_site_frameworkVersion").val();
    console.log(oldFrameworkVersion);
}
if ($("#appbundle_site_framework").size()){
    oldFramework = $("#appbundle_site_framework").val();
    $("#appbundle_site_framework").change();
}

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
