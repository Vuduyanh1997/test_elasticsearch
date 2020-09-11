@extends('layouts.master')
@section('link')
@endsection
@section('content')
<div class="content-header">
<div class="container-fluid">
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1 class="m-0 text-dark">Thêm mới bài viết</h1>
    </div>
  </div>
</div>
</div>
<div class="container-fluid">
	<div class="col-md-10" style="margin: auto">
		<form method="POST" id="form_create_post">
			<div class="form-group">
				<label for="title">Tiêu đề</label>
				<input type="text" name="title" id="title" class="form-control" placeholder="Tiêu đề">
			</div>
			<div class="form-group">
				<label for="title">Nội dung</label>
				<textarea name="content" id="content" rows="5" class="form-control"></textarea>
			</div>
			<div class="form-group">
				<center>
					<button type="submit" class="btn btn-md btn-primary">Tạo</button>
				</center>
			</div>
		</form>
		
	</div>
</div>
@endsection
@section('footer')
<script>
	$(document).ready(function(){
		$("#form_create_post").validate({
			onfocusout: false,
			onkeyup: false,
			onclick: false,
			errorElement: 'span',
	        errorClass: 'error',
			rules: {
				"title": {
					required: true,
					maxlength: 255,
					minlength: 5
				},
				"content": {
					required: true,
					minlength: 10
				}
			},
			messages: {
				"title": {
					required: "Vui lòng thêm tiêu đề cho bài viết!",
					maxlength: "Vui lòng nhập tối đa 255 ký tự!",
					minlength: "Vui lòng nhập tối thiểu 5 ký tự!"
				},
				"content": {
					required: "Vui lòng thêm nội dung cho bài viết!",
					minlength: "Vui lòng nhập tối thiểu 10 ký tự!"
				}
			}
		});
		$(document).on('submit', '#form_create_post', function(e){
			e.preventDefault();

			$('#title').val($('#title').val().trim());
			$('#content').val($('#content').val().trim());

			$('#form_create_post').valid();

			var title = $('#title').val();
			var content = $('#content').val();

			$.ajax({
                url: '/posts/store',
                type: 'POST',
                data: {
                    title: title,
                    content: content
                },
                success: function(res) {
                	if(!res.error){
                		toastr.success(res.message);
                		setTimeout(function(){
                			document.location.href= '/posts';
                		}, 300);
                	} else {
                		toastr.error(res.message);
                	}
			    },
            });
		})
	})
</script>
@endsection