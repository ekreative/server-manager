$('.form-collection').each(function(i, formCollection) {
    var $formCollection = $(formCollection),
        $elements = $formCollection.find('.form-collection-elements'),
        idx = $elements.find('.form-collection-element').length;
    $formCollection.find('.form-collection-add').on('click', function() {
        var prototype = $(this).data('prototype');
        prototype = prototype.replace(/__name__/g, idx++);
        $elements.append($(prototype));
        refreshTogles();
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
            prefetch: $(input).attr('typeahead')
        })
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

function refreshTogles() {
    $('[data-login-type-toggle] [checked]').each(function(i, input) {
        loginTypeToggle($(input));
    });
}
refreshTogles();
