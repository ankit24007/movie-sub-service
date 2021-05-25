<?php

namespace API;

use Core\Request;
use \GuzzleHttp\Client;
use Core\DB;

class Subscription {

    protected $request;
    protected $client;
    protected $db;

    public function __construct() {
        $this->request = Request::data();    
        $this->client = new Client(['base_uri' => 'https://star-wars-api.herokuapp.com']);
        $this->db = new DB;
    }

    public function add()
    {
        $movie_id = $this->request->movie_id;
        $user_id = 1; // lets assume the logged in user id is 1

        $response = $this->client->request('GET', 'films');
        $data = json_decode((string) $response->getBody());

        $title = null;
        $desc = null;
        foreach($data as $movie){
            if($movie->id == $movie_id){
                $title = $movie->fields->title;
                $desc = $movie->fields->opening_crawl;
                break;
            }
        }

        if(!$title && !$desc){
            echo json_encode([
                'status' => 'failed',
                'message' => 'Movie Id not match with any record!'
            ]);
            return;
        }
        
        $this->db->prepare("INSERT INTO `user_subscriptions` (`user_id`, `movie_id`, `title`, `description`, `start`, `end`) VALUES (:user_id, :movie_id, :title, :desc, :start, :end);")->execute([
            'user_id' => $user_id,
            'movie_id' => $movie_id,
            'title' => $title,
            'desc' => $desc,
            'start' => date('Y-m-d h:i:s'),
            'end' => date('Y-m-d h:i:s', strtotime('+30 days'))
        ]);

        echo json_encode([
            'status' => 'success',
            'message' => 'Subscribed for movie successfully!'
        ]);
        return;
        
    }

    public function remove()
    {
        $movie_id = $this->request->movie_id;
        $user_id = 1; // lets assume the logged in user id is 1

        $this->db->delete("DELETE FROM `user_subscriptions` WHERE user_id=$user_id and movie_id=$movie_id");

        echo json_encode([
            'status' => 'success',
            'message' => 'Movie unsubscribed successfully!'
        ]);
        return;
    }

    public function view()
    {
        $user_id = 1; // lets assume the logged in user id is 1

        $data = $this->db->fetch("SELECT * FROM users AS u JOIN user_subscriptions AS us ON u.id = us.user_id WHERE user_id=$user_id");

        echo json_encode([
            'status' => 'success',
            'data' => $data
        ]);
        return;
    }


}

