@extends('layouts.master')
@section('header')
@endsection
@section('content')
<div class="content-header">
<div class="container-fluid">
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1 class="m-0 text-dark">Chỉnh sửa bài viết</h1>
    </div>
  </div>
</div>
</div>
<div class="container-fluid">
	<div class="col-md-10" style="margin: auto">
		<form method="POST" id="form_edit_post">
			<input type="hidden" id="slug_post" value="{{$post->slug}}">
			<div class="form-group">
				<label for="title">Tiêu đề</label>
				<input type="text" name="title" id="title" class="form-control" placeholder="Tiêu đề" value="{{$post->title}}" disabled="true">
			</div>
			<div class="form-group">
				<label for="content">Nội dung</label>
				<textarea name="content" id="content" rows="5" class="form-control">{{$post->content}}</textarea>
			</div>
			<div class="form-group">
				<center>
					<button type="submit" class="btn btn-md btn-primary">Chỉnh sửa</button>
				</center>
			</div>
		</form>
		
	</div>
</div>
@endsection
@section('footer')
<script>
	$(document).ready(function(){
		$("#form_edit_post").validate({
			onfocusout: false,
			onkeyup: false,
			onclick: false,
			errorElement: 'span',
	        errorClass: 'error',
			rules: {
				"content": {
					required: true,
					minlength: 10
				}
			},
			messages: {
				"content": {
					required: "Vui lòng nhập nội dung cho bài viết!",
					minlength: "Vui lòng nhập tối thiểu 10 ký tự!"
				}
			}
		});
		$(document).on('submit', '#form_edit_post', function(e){
			e.preventDefault();
			$('#content').val($('#content').val().trim());
			$('#form_edit_post').valid();
			var content = $('#content').val();
			var slug = $('#slug_post').val();

			$.ajax({
                url: '/posts/update',
                type: 'POST',
                data: {
                    slug: slug,
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