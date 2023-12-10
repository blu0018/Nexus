<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Category;
use App\Models\CategoryChat;
use App\Models\CategoryMessage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function chat(Request $request)
    {  
        $data = $request->only('chat_id','category_id','message');
        $rule = [
            'chat_id' => 'required',
            'category_id' => 'required',
            'message' => 'required',
        ];

        $user_id = auth()->user()->id;
        $chat_id = $data['chat_id'];
        $category_id = $data['category_id'];
        $message = $data['message'];

        $validator = validator::make($data, $rule);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($chat_id == 0) {
            $categoryChat = CategoryChat::where(['category_id' => $category_id, 'user_id' => $user_id])->first();
        
            if (!$categoryChat) {
                $categoryChat = new CategoryChat();
                $categoryChat->chat_id = 1;        
            } else { 
                $categoryChat = CategoryChat::where(['category_id' => $category_id, 'user_id' => $user_id])->get();
                $lastchat_id = $categoryChat->last()->chat_id;
                if($lastchat_id){
                    $categoryChat = new CategoryChat();
                    $categoryChat->chat_id = $lastchat_id + 1;
                }
            }
            $categoryChat->category_id = $category_id;
            $categoryChat->user_id = $user_id; 
            $categoryChat->save();
        } else {
            $categoryChat = CategoryChat::where(['category_id' => $category_id, 'user_id' => $user_id])->get();
            $lastchat_id = @$categoryChat->last()->chat_id;

            if (!$categoryChat || $lastchat_id < $chat_id) {
                $categoryChat = new CategoryChat();
                $categoryChat->chat_id = $lastchat_id ? $lastchat_id + 1 : 1;        
            } else {  
                $categoryChat = CategoryChat::where(['category_id' => $category_id, 'chat_id' => $chat_id, 'user_id' => $user_id])->first();
                $categoryChat->chat_id = $chat_id;
            }
            $categoryChat->category_id = $category_id;
            $categoryChat->user_id = $user_id; 
            $categoryChat->save();
        }
        $characterChat_id = $categoryChat->chat_id;

        $aimessage = Str::random(10);

        if(!empty($characterChat_id)){

            $charData = [
                [
                    'by' => 'U',
                    'message' => $message
                ],
                [
                    'by' => 'C',
                    'message' => $aimessage
                ]
            ];

            foreach ($charData as $char) {
                $categoryMessage = new CategoryMessage();
                $categoryMessage->chat_id = $characterChat_id;   
                $categoryMessage->category_id = $category_id;
                $categoryMessage->message = $char['message']; 
                $categoryMessage->by =  $char['by']; 
                $categoryMessage->user_id = $user_id; 
                $categoryMessage->save();
            }
            $msg_id = $categoryMessage->id;

            return response()->json(['chat_id' => $characterChat_id, 'msg_id' => $msg_id, 'message' => $aimessage ]);
        }
    }

    public function chatlist(Request $request)
    {   
        $user_id = auth()->user()->id;
        $categoryChats = CategoryChat::with('category')->where(['user_id' => $user_id])->get();
        $list = [];
        
        foreach ($categoryChats as $chat)
        {
            $chatData = [];
            $chatMsg = CategoryMessage::where(['chat_id' => $chat->chat_id, 'category_id' => $chat->category_id, 'by' => 'U', 'user_id' => $user_id])->get();
            $chatMsg = $chatMsg->last();
            $chatData = $chat;
            $chatData['chat_message'] = $chatMsg;
            $list[] = $chatData;
        }       
        return $list;
    }

}