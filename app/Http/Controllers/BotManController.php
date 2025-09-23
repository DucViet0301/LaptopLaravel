<?php

namespace App\Http\Controllers;
use App\Conversations\NameConversation;

class BotManController extends Controller
{
   public function handle(){
    $botman = app('botman');
    $botman->hears('hi|hello|xin chào', function($bot){
        $bot->startConversation( new NameConversation);     
    });
    $botman->fallback(function($bot){
      $bot->reply('Xin vui lòng chỉ nhập: hi, hello, hoặc xin chào!');
    });
     $botman->listen();
   }
}