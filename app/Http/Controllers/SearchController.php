<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        if ($search != '') {
            $data = [
                "query"=> [
                    "multi_match" => [
                        "query" => $search,
                        "fields" => ["title^3", "content^2", "user_name"],
                        "fuzziness" => 1
                    ]
                ],
                "size"=> 10,
                // "sort"=> [
                //     "created_at"=> [
                //         "order" => "desc"
                //     ]
                // ]
            ];
        }
        $request = $client->get(env('APP_ELASTICSEARCH_URL') . '/posts/_search?pretty', [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode($data)
        ]);

        $content = $this->contentApi($request);
        $arr_banks = null;
        $banks = $content->hits->hits;
        if (!empty($banks)) {
            $arr_banks = $this->stdToArray($banks);
        }
        
        return response()->json([
            'arr_banks'  => $arr_banks
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
}
