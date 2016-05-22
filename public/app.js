$('.edit-form').submit(function(e) {
  e.preventDefault();
  var $form = $(this);

  var postForm = function(obj) {
    if(!obj) {
      obj = {};
    }
    $form.find('input, select, textarea').each(function(){
      var $input = $(this);
      var inputName = $input.attr('name');
      if (inputName) {
        obj[inputName] = $input.val();
      }
    });
    console.log(obj);
    $.post({
      url: $form.attr('action'),
      data: obj,
      success: function(data) {
        console.log(data);
        $('.js-result').html(JSON.stringify(data));
      },
      error: function(err) {
        $('.js-result').html(JSON.stringify(err));
      }
    });
  };

  if($form.data('get-action')) {
    $.get({
      url: $form.data('get-action'),
      dataType: 'json',
      success: function(obj) {
      },
      error: function(err) {
        $('.js-result').html(JSON.stringify(err));
      }
    });
  } else {
    postForm();
  }
});
