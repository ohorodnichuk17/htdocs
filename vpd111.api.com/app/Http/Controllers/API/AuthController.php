<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     tags={"Auth"},
     *     path="/api/register",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"email", "lastName", "name", "phone", "image", "password", "password_confirmation"},
     *                 @OA\Property(
     *                     property="image",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="lastName",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password_confirmation",
     *                     type="string"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response="200", description="Add Category.")
     * )
     */
    public function register(Request $request) {
        $input = $request->all();
        $validation = Validator::make($input, [
            "name"=>"required|string",
            "lastName"=>"required|string",
            "image"=>"required|string",
            "phone"=>"required|string",
            "email"=>"required|email",
            "password"=>"required|string",
        ]);
        if($validation->fails()) {
            return response()->json($validation->errors(), Response::HTTP_BAD_REQUEST);
        }
        $imageName = uniqid() . ".webp";
        $sizes = [50, 150, 300, 600, 1200];
        $manager = new ImageManager(new Driver());
        $folderName = "upload";
        $folderPath = public_path($folderName);
        if (!file_exists($folderPath) && !is_dir($folderPath))
            mkdir($folderPath, 0777);
        foreach ($sizes as $size) {
            $imageSave = $manager->read($input["image"]);
            $imageSave->scale(width: $size);
            $imageSave->toWebp()->save($folderPath."/".$size."_".$imageName);
        }
        $user = User::create(array_merge(
            $validation->validated(),
            ['password'=>bcrypt($input['password']), 'image'=>$imageName]
        ));
        return response()->json(["user"=>$user], Response::HTTP_OK);
    }


    /**
     * @OA\Post(
     *   path="/api/login",
     *   tags={"Auth"},
     *   summary="Login",
     *   operationId="login",
     *   @OA\RequestBody(
     *     required=true,
     *     description="User login data",
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         required={"email", "password"},
     *         @OA\Property(property="email", type="string"),
     *         @OA\Property(property="password", type="string"),
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\MediaType(
     *       mediaType="application/json"
     *     )
     *   ),
     *   @OA\Response(
     *     response=401,
     *     description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *     response=400,
     *     description="Bad Request"
     *   ),
     *   @OA\Response(
     *     response=404,
     *     description="Not Found"
     *   ),
     *   @OA\Response(
     *     response=403,
     *     description="Forbidden"
     *   )
     * )
     */
    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "email"=>"required|email",
            "password"=>"required|string",
        ], [
            'email.required' => 'Mail is a required field.',
            'email.email' => 'Mail is invalid.',
            'password.required' => 'Password can not be empty.',
            'password.min' => 'The length of the password must be at least 6 characters.',
        ]);
        if($validation->fails()) {
            return response()->json($validation->errors(), Response::HTTP_BAD_REQUEST);
        }
        if(!$token = auth()->attempt($validation->validated())) {
            return response()->json(['error'=>'The data is incorrect'], Response::HTTP_UNAUTHORIZED);
        }
        return response()->json(['token'=>$token], Response::HTTP_OK);
    }



    /**
     * Redirect the user to the OAuth Provider.
     *
     * @param  string  $provider
     * @return \Illuminate\Http\RedirectResponse
     */

    /**
     * @OA\Post(
     *     tags={"Auth"},
     *     path="/api/oauth/{provider}",
     *     summary="Redirect the user to the OAuth Provider.",
     *     operationId="redirectToProvider",
     *     @OA\Parameter(
     *         name="provider",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         ),
     *         description="OAuth provider name."
     *     ),
     *     @OA\Response(
     *         response=302,
     *         description="Redirect to OAuth Provider."
     *     )
     * )
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from the OAuth Provider.
     *
     * @param  string  $provider
     * @return \Illuminate\Http\JsonResponse
     */

    /**
     * @OA\Get(
     *     tags={"Auth"},
     *     path="/api/oauth/{provider}/callback",
     *     summary="Obtain the user information from the OAuth Provider.",
     *     operationId="handleProviderCallback",
     *     @OA\Parameter(
     *         name="provider",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         ),
     *         description="OAuth provider name."
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User information obtained from the OAuth Provider.",
     *         @OA\MediaType(
     *             mediaType="application/json"
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unable to authenticate."
     *     )
     * )
     */
    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to authenticate.'], Response::HTTP_UNAUTHORIZED);
        }

        $existingUser = User::where('email', $user->getEmail())->first();

        if ($existingUser) {
            $token = auth()->login($existingUser);
        } else {
            $newUser = User::create([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => bcrypt(str_random(16)),
            ]);

            $token = auth()->login($newUser);
        }

        return response()->json(['token' => $token], Response::HTTP_OK);
    }

}
