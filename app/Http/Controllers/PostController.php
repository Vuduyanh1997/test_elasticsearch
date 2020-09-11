<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Post;
use App\User;
use Illuminate\Support\Str;
use Auth;
use DataTables;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('posts.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $time = strtotime(\Carbon\Carbon::now('Asia/Ho_Chi_Minh'));
            $data['slug'] = Str::slug($data['title'], '-') . '-' . $time;
            $check = Post::where('slug', $data['slug'])->count();

            if ($check > 0) {
                return response()->json([
                    'error'    => true,
                    'message'  => 'Bài viết đã tồn tại!'
                ]);
            }

            $data['user_id'] = Auth::user()->id;
            $data['status'] = 1;

            $post = Post::create($data);

            DB::commit();
            return response()->json([
                'error'    => false,
                'message'  => 'Thêm bài viết thành công!'
            ]);
        } catch (Exception $e) {
            
            DB::rollback();
            return response()->json([
                'error'    => true,
                'message'  => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $post = Post::where('slug', $slug)->first();
        return view('posts.edit', [
            'post' => $post,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();

            $post = Post::where('slug', $data['slug'])->first();
            if ($post == null) {
                return response()->json([
                    'error'    => true,
                    'message'  => 'Bài viết không tồn tại!'
                ]);
            }

            $data['status'] = 1;
            $post->update($data);

            DB::commit();
            return response()->json([
                'error'    => false,
                'message'  => 'Chỉnh sửa bài viết thành công!'
            ]);
        } catch (Exception $e) {
            
            DB::rollback();
            return response()->json([
                'error'    => true,
                'message'  => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {
            $post = Post::where('slug', $request->slug)->first();
            if ($post == null) {
                return response()->json([
                    'error'    => true,
                    'message'  => 'Bài viết không tồn tại!'
                ]);
            }
            $post->delete();
            DB::commit();
            return response()->json([
                'error'    => false,
                'message'  => 'Xóa bài viết thành công!'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'error'    => true,
                'message'  => $e->getMessage()
            ]);
        }
    }

    public function getList(Request $request){
        $posts = Post::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        if ($posts->count() > 0) {
            foreach ($posts as $key => $post) {
                $user = User::where('id', $post->user_id)->first();
                $post->user_name = $user->name;
            }
        }
        return DataTables::of($posts)
            ->addIndexColumn()
            ->addColumn('action', function ($post){
                $txt = "";
                $txt .= '<a href="/posts/edit/'.$post->slug.'" title="Chỉnh sửa" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>';
                $txt .= '<a data-slug="'.$post->slug.'" title="Xóa" class="btn btn-sm btn-danger btn-delete"><i style="color:white" class="fas fa-trash"></i></a>';
                return $txt;
            })
            ->addColumn('title', function ($post) {

                return $post->title;
            })
            ->addColumn('user_name', function ($post) {

                return $post->user_name;
            })
            ->editColumn('status', function ($post) {
                $txt = '';
                if ($post->status == 0) {
                    $txt = 'Ẩn';
                } else {
                    $txt = 'Hiển thị';
                }
                return $txt;
            })
            ->editColumn('created_at', function ($post) {
                return date('H:i | d/m/Y', strtotime($post->created_at));
            })
            ->rawColumns(['action'])
            ->toJson();
    }
}
