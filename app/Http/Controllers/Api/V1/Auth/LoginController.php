<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

class LoginController extends Controller
{
    #[OA\Post(
        path: '/v1/login',
        summary: 'Login',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['email', 'password'],
                properties: [
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'admin@example.com'),
                    new OA\Property(property: 'password', type: 'string', example: 'password'),
                    new OA\Property(property: 'token', type: 'boolean', description: 'Set to true to receive a Bearer token for cross-origin API auth'),
                ]
            )
        ),
        tags: ['Auth'],
        responses: [
            new OA\Response(response: 200, description: 'Success', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'user', ref: '#/components/schemas/User'),
                new OA\Property(property: 'token', type: 'string', description: 'Present when token=true'),
                new OA\Property(property: 'token_type', type: 'string', example: 'Bearer'),
            ])),
            new OA\Response(response: 422, description: 'Invalid credentials', content: new OA\JsonContent(properties: [
                new OA\Property(property: 'message', type: 'string', example: 'The provided credentials are incorrect.'),
            ])),
        ]
    )]
    public function __invoke(LoginRequest $request): JsonResponse
    {
        if (! Auth::guard('web')->attempt($request->validated())) {
            return response()->json(['message' => 'The provided credentials are incorrect.'], 422);
        }

        $user = Auth::guard('web')->user();
        $useToken = $request->boolean('token');

        if ($useToken) {
            $user->tokens()->where('name', 'spa')->delete();
            $token = $user->createToken('spa')->plainTextToken;

            return response()->json([
                'user' => new UserResource($user),
                'token' => $token,
                'token_type' => 'Bearer',
            ]);
        }

        $request->session()->regenerate();

        return response()->json(['user' => new UserResource($user)]);
    }
}
