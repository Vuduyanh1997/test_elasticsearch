<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\User;
use Illuminate\Support\Str;
use Auth;
use DB;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('search');
    }

    public function contentApi($request){
        $data_body = $request->getBody();
        $content = json_decode($data_body->getContents());
        return $content;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function search(Request $request){
        $client = new \GuzzleHttp\Client();
        $search = $request->search_name;
        $count_show = env('COUNT_SHOW', 1000);

        if ($search != '') {
            $data = [
                "query" => [
                    "multi_match" => [
                        "query" => $search,
                        "fields" => ["title^3", "short_content"],
                        "minimum_should_match" => "60%",
                        "fuzziness" => "AUTO"
                    ]
                ],
                "size" => $count_show,
                "sort" => [
                    "_score" => [
                        "order" => "desc"
                    ]
                ]
            ];
        }

        $request = $client->get(env('APP_ELASTICSEARCH_URL', 'http://localhost:9200') . '/posts/_search?pretty', [
            'headers'  => ['Content-Type' => 'application/json'],
            'body'     => json_encode($data)
        ]);

        $content = $this->contentApi($request);
        $arr_banks = null;

        $count = $content->hits->total->value;
        $took = $content->took;
        if ($count_show > $count) {
            $count_show = $count;
        }

        $banks = $content->hits->hits;
        if (!empty($banks)) {
            $arr_banks = $this->stdToArray($banks);
        }
        
        return response()->json([
            'arr_banks'   => $arr_banks,
            'count'       => $count,
            'count_show'  => $count_show,
            'took'        => $took/1000
        ]);
    }
    function stdToArray($obj){
        $reaged = (array)$obj;
        foreach($reaged as $key => $field){
            if(is_object($field)){
                $reaged[$key] = $this->stdToArray($field);
            }
        }
        return $reaged;
    }

    public function showContent($slug){
        $post = Post::where('slug', $slug)->where('status', 1)->first();
        if ($post == null) {
            abort(404);
        }
        $user = User::where('id', $post->user_id)->first();
        if ($user == null) {
            $post->user_name = "Không tồn tại";
        } else {
            $post->user_name = $user->name;
        }
        $post->time = date('H:i | d/m/Y', strtotime($post->created_at));
        return view('posts.show_content', [
            'post'  => $post
        ]);
    }
}
