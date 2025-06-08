<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Library Management API",
 *     version="1.0.0",
 *     description="API REST pour la gestion d'une bibliothèque - Système de gestion des auteurs et livres",
 *     @OA\Contact(
 *         email="contact@example.com"
 *     )
 * )
 * 
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Use Bearer token for authentication"
 * )
 * 
 * @OA\Tag(
 *     name="Authors",
 *     description="Gestion des auteurs"
 * )
 * 
 * @OA\Tag(
 *     name="Books",
 *     description="Gestion des livres"
 * )
 */
abstract class Controller
{
    //
}
