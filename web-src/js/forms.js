$('.form-collection').each(function(i, formCollection) {
    var $formCollection = $(formCollection),
        $elements = $formCollection.find('.form-collection-elements'),
        idx = $elements.find('.form-collection-element').length;
    $formCollection.find('.form-collection-add').on('click', function() {
        var prototype = $(this).data('prototype');
        prototype = prototype.replace(/__name__/g, idx++);
        $elements.append($(prototype));
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
    $root.find(`[data-login-type]:not([data-login-type~=${val}])`).closest('.form-group').addClass('hide');
    $root.find(`[data-login-type~=${val}]`).closest('.form-group').removeClass('hide');
}

$(document).on('change', '[data-login-type-toggle] input', function() {
    loginTypeToggle($(this));
});

$('[data-login-type-toggle] [checked]').each(function(i, input) {
    loginTypeToggle($(input));
});
