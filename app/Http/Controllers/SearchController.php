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
        $firstname = $request->search_name;
        if ($firstname != '') {
            $data = ["query"=> ["bool" => ["should" => [["match"=> ["firstname"=> $firstname]], ["match"=> ["lastname"=> $firstname]]]]],"size"=> 10,"sort"=> ["balance"=> ["order"=> "desc"]]];
        }
        $request = $client->get(env('APP_ELASTICSEARCH_URL') . '/bank/_search?pretty', [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode($data)
        ]);

        $content = $this->contentApi($request);

        $banks = $content->hits->hits;
        if (!empty($banks)) {
            $arr_banks = $this->stdToArray($banks);
        }
        // dd($arr_banks);
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
