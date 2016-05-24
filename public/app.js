$('.edit-form').submit(function(e) {
  e.preventDefault();
  var $form = $(this);
  var obj = {};

  $form.find('input, select, textarea').each(function(){
    var $input = $(this);
    var inputName = $input.attr('name');
    if (inputName) {
      obj[inputName] = $input.val();
    }
  });
  $.post({
    url: $form.attr('action'),
    data: obj,
    success: function(data) {
      $('.js-result').html(JSON.stringify(data));
    },
    error: function(err) {
      $('.js-result').html(JSON.stringify(err));
    }
  });
});
