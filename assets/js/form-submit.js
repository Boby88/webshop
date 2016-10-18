function _redirect(redirectURL) {
  location.href = redirectURL;
}
function _displayMessage(form, button, data) {
  for (var key in data) {
    if ( key === "__error__") {
      button.parent().append(data[key]);
    } else if ( key === "__message__" ) {
      form.prepend(data[key]);
    } else {
      form.find('input[name="' + key + '"]').parent().append(data[key]);
    }
  }
}
// custom ajax method
function _ajaxFormSubmit(event, success) {
  event.preventDefault();
  var form = $(event.target),
      data = form.serialize(),
      url = form.attr('action'),
      button = form.find('[type="submit"]');

  startLoader(button);
  // clear errors
  form.find('.dynamic-response').remove();
  $.ajax({
    method: 'POST',
    data: data,
    url: url,
    success: function(data, textStatus, jqXHR) {
      if(jqXHR.responseJSON.success_messages !== undefined) {
        stopLoader(button);
        _displayMessage(form, button, jqXHR.responseJSON.success_messages);
      }
      if(jqXHR.responseJSON.redirect_url !== undefined)
        success(jqXHR.responseJSON.redirect_url);
    },
    error: function(jqXHR, textStatus, errorThrown) {
      stopLoader(button);
      _displayMessage(form, button, jqXHR.responseJSON.errors);
    }
  });
}
$(document).ready(function() {
  $('form').on('submit', function(e) {
    _ajaxFormSubmit(e, _redirect);
  });
});