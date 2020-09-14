@extends('layouts.master')
@section('header')
<style>
	#table_post a{
		margin-right: 3px;
	}
</style>
@endsection
@section('content')
<div class="content-header">
<div class="container-fluid">
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1 class="m-0 text-dark">Quản lý bài viết</h1>
    </div>
  </div>
</div>
</div>
<div class="container-fluid">
	<a class="btn btn-md btn-success" href="/posts/create">Thêm</a>
	<br><br>
	<div class="table-responsive">
		<table class="table table-hover table-bordered" id="table_post">
			<thead>
				<tr>
					<th>STT</th>
					<th>Hành động</th>
					<th>Tiêu đề</th>
					<th>Tác giả</th>
					<th>Trạng thái</th>
					<th>Ngày tạo</th>
				</tr>
			</thead>
		</table>
	</div>
</div>
@endsection

@section('footer')
<script>
	var table = $('#table_post').DataTable({
        processing: true,
        serverSide: true,
        ordering: false,
        order: [], 
        pageLength: 30,
        lengthMenu: [[5, 30, 50, 100, 200, 500], [5, 30, 50, 100, 200, 500]],
        ajax: {
            type: 'POST',
            url: '/posts/list',
        },
        columns: [
            {data: 'DT_RowIndex', orderable: false, searchable: false, 'class':'text-center'},
            {data: 'action', name: 'action', orderable: false, searchable: false, 'class': 'text-center'},
            {data: 'title', name: 'title'},
            {data: 'user_name', name: 'user_name'},
            {data: 'status', name: 'status', 'class': 'text-center'},
            {data: 'created_at', name: 'created_at', 'class':'text-center'},
        ]
    });

	$(document).on('click', '.btn-delete', function(){
		var slug = $(this).attr('data-slug');
		swal({
            title: "Bạn có chắc chắn xóa bài viết này!",
            type: "error",
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Có",
            cancelButtonText: "Không",
            showCancelButton: true,
        },
        function() {
            $.ajax({
	            url: '/posts/destroy',
	            type: 'POST',
	            data: {
	                slug: slug
	            },
	            success: function(res) {
	            	if(!res.error){
	            		toastr.success(res.message);
	            		$('#table_post').DataTable().ajax.reload();
	            	} else {
	            		toastr.error(res.message);
	            	}
			    },
	        });
    	});
		
	})

</script>
@endsection