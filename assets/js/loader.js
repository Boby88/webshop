/* these are the newest loader handlers */
function startLoader(element){
  $(element).parent().find('.ajax-error').remove();
  var loaderContainer = $('<div class="loader-container text-center"></div>');
  loaderContainer.append('<span class="loader-2"></span>');
  var w = $(element).outerWidth();
  var h = $(element).outerHeight();
  var float = $(element).css('float');
  loaderContainer.css({
    width: w,
    height: h,
    float: float
  });
  loaderContainer.insertBefore($(element));
  $(element).hide();
}
function stopLoader(element) {
  $(element).parent().children('.loader-container').remove();
  $(element).show();
}
function displayError(element, msg) {
  $(element).parent().append('<div class="ajax-error caption text-danger text-right">' + msg + '</div>');
}