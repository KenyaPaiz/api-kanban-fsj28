<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 * title="Laravel V12 Kanban Task Management API - FSJ28",
 * version="1.0.0",
 * description="API documentation for the Kanban task management system. Laravel 12."
 * )
 *
 * @OA\Server(
 * url="/api/v1",
 * description="API V1 Base URL"
 * )
 *
 * @OA\SecurityScheme(
 * type="http",
 * description="Enter the Bearer token (JWT) returned at login.",
 * name="Authorization",
 * in="header",
 * scheme="bearer",
 * bearerFormat="JWT",
 * securityScheme="bearerAuth",
 * )
 */
abstract class Controller
{
    //
}
