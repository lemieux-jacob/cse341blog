// Comment Modal
$('#CommentModal').on('show.bs.modal', function (event) {
  console.log('here');
  var button = $(event.relatedTarget)
  var post = button.data('post')
  var user = button.data('user')
  var body = button.data('body')
  var comment = button.data('comment')
  $('#postID').val(post)
  $('#userID').val(user)
  $('#bodyText').val(body)
  $('#commentID').val(comment)
})