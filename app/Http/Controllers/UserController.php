<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\ImageRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\UserResource;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    private $imageRepository;


    public function __construct(ImageRepositoryInterface $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->can('god mode')) {
            $users = QueryBuilder::for(User::class)
                ->allowedIncludes(['images'])
                ->allowedFilters('email')
                ->get();

            return response([ 'users' => UserResource::collection($users)], 200);
        } else {
            $current_user = User::where('id', auth()->user()->id);

            $users = QueryBuilder::for($current_user)
                ->allowedIncludes(['images'])
                ->get();

            return response([ 'users' => new UserResource($users)], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $user = User::create($data);

        $token = $user->createToken('LoginGrant')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $user_record = User::where('id', $user->id);

        if (auth()->user()->can('god mode')) {
            $users = QueryBuilder::for($user_record)
                ->allowedIncludes(['images'])
                ->allowedFilters('email')
                ->get();

            return response([ 'users' => UserResource::collection($users)], 200);
        } else {
            $user_record = User::where('id', auth()->user()->id);

            $users = QueryBuilder::for($user_record)
                ->allowedIncludes(['images'])
                ->get();

            return response([ 'users' => new UserResource($users)], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'min:3|max:255',
            'email' => 'email|unique:users',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        if (!auth()->user()->can('god mode')) {
            if ($user->id !== auth()->user()->id) {
                return response()->json(['error' => 'you can only update your own account']);
            }
        }

        $user->update($request->all());

        return response(['user' => new UserResource($user), 'auth_user_id' => auth()->user()->id], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if (!auth()->user()->can('god mode')) {
            if ($user->id !== auth()->user()->id) {
                return response()->json(['error' => 'you can only update your own account']);
            }
        }

        $user->delete();

        return response()->json(['response' => 'User successfully deleted'], 200);
    }
}
