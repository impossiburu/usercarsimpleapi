<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Car;
use App\Models\User;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return UserResource::collection(User::with('car')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:users',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::create($request->all());
        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new UserResource(User::with('car')->findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'name' => 'unique:users',
            'email' => 'email',
        ]);

        
        if (!$request->car_id) {
            $user->update($request->all());
        } else {
            $car = Car::where('id', $request->car_id)->first();
            if (!$car->user_id) {
                $car->user_id = $user->id;
                $car->update();
            } else {
                return [
                    'message' => 'Car' . $car->id . ' is used by user_id: ' . $car->user_id
                ];
            }
        }

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $car = Car::where('user_id', $user->id)->first();
        
        $car->user_id = 0;
        if ($car->update()) {
           $user->delete(); 
        }
        
        return response(null, HttpResponse::HTTP_NO_CONTENT);
    }
}
