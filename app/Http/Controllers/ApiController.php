<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PhpParser\Node\Expr\PostDec;

class ApiController extends Controller
{

    public function login(Request $request){
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->api_token;
                $response = [
                    'user' => $user->username,
                    'token' => $token
                ];
                return response($response, 200);
            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }
        } else {
            $response = ["message" =>'User does not exist'];
            return response($response, 422);
        }
    }

    public function register (Request $request) {

        $user = new User();
        $user->name = $request->firstName;
        $user->lastName = $request->lastName;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->dateOfBirth = Carbon::parse($request->dateOfBirth);
        $user->gender = $request->gender;
        $user->api_token = Str::random(60);
        $user->save();

        $response = ['token' => $user->api_token];

        return response($response, 200);
    }

    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }

    public function checkUser($usernameInput){

        $user = User::where('username',$usernameInput)->first();

        if($user){
            return response()->json(['status' => true]);
        }else{
            return response()->json(['status' => false]);
        }
    }

    public function insertPost(Request $request){
        $user = User::where('api_token',$request->api_token)->first();

        $base64 = 'data:'. $request->type . ';base64,' . $request->media;

        $post = new Post();
        $post->media = $base64;
        $post->description = $request->description;
        $post->location = $request->location;
        $post->likes = 0;
        $post->user_id = $user->id;
        $post->save();

        $users = User::all();
        foreach($users as $user){
            $notification = new Notification();
            $notification->create(['user_id' => $user->id, 'post_id' => $post->id]);
        }

        return response()->json(null,200);
    }

    public function getFeed(){
        $feed = Post::with('user','comments.user')
            ->orderBy('id','desc')
            ->get();

        return response()->json($feed);
    }

    public function makeComment(Request $request){
        $user = User::where('api_token',$request->api_token)->first();


        $comment = new Comment();
        $comment->post_id = $request->post_id;
        $comment->user_id = $user->id;
        $comment->comment = $request->comment;
        $comment->save();

        $comment = Comment::with('user')->find($comment->id);

        return response()->json($comment,200);
    }

    public function getMyProfileUser($username){
        $user = User::where('username',$username)->first();
        $posts = $user->posts->count();

        return response()->json([
            'user' => $user,
            'countPosts' => $posts,
        ]);

    }

    public function getMyProfile($username){
        $user = User::where('username',$username)->first();

        return response()->json($user->posts);

    }

    public function changeAvatar(Request $request){

        $user = $request->user();

        $base64 = 'data:'. $request->type . ';base64,' . $request->media;
        $user->avatar = $base64;
        $user->save();

        return response()->json(null,200);

    }

    public function forgotPassword($email){
        $user = User::where('email',$email)->first();
        $token = Str::random(60);
        $user->sendPasswordResetNotification($token);
        return response()->json(null,200);
    }
}
