<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Post;
use App\User;
use Illuminate\Support\Str;
use Auth;

class AutoUpdatePostToElasticsearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:elasticsearch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cập nhật dữ liệu elasticsearch';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $posts = Post::all();

        $client = new \GuzzleHttp\Client();
        $client->delete(env('APP_ELASTICSEARCH_URL') . '/posts');
        $client->put(env('APP_ELASTICSEARCH_URL') . '/posts');
        foreach ($posts as $key => $post) {
            $user = User::where('id', $post->user_id)->first();

            $data = [
                "id"           => $post->id,
                "slug"         => $post->slug,
                "title"        => $post->title,
                "content"      => $post->content,
                "user_name"    => $user->name,
                "created_at"   =>  date('H:i | d/m/Y', strtotime($user->created_at))
            ];

            $client->put(env('APP_ELASTICSEARCH_URL') . '/posts/_doc/' . $post->id .'?pretty', [
                'headers' => ['Content-Type' => 'application/json'],
                'body' => json_encode($data)
            ]);
        }
    }
}
