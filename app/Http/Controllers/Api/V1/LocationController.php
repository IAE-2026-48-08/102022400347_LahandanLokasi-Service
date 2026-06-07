<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class LocationController extends Controller
{
    #[OA\Get(
        path: '/api/v1/locations',
        summary: 'Get all parking locations with real-time empty slots',
        tags: ['Locations'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 1),
                            new OA\Property(property: 'name', type: 'string', example: 'ParkSmart Braga'),
                            new OA\Property(property: 'address', type: 'string', example: 'Jl. Braga No. 5 Bandung'),
                            new OA\Property(property: 'capacity_car', type: 'integer', example: 40),
                            new OA\Property(property: 'capacity_motor', type: 'integer', example: 80),
                            new OA\Property(property: 'occupied_car', type: 'integer', example: 32),
                            new OA\Property(property: 'occupied_motor', type: 'integer', example: 45),
                            new OA\Property(property: 'available_car_slots', type: 'integer', example: 8),
                            new OA\Property(property: 'available_motor_slots', type: 'integer', example: 35),
                            new OA\Property(property: 'tariff_car', type: 'string', example: '5000.00'),
                            new OA\Property(property: 'tariff_motor', type: 'string', example: '2000.00'),
                            new OA\Property(property: 'operating_hours', type: 'string', example: '06:00 - 23:00')
                        ]
                    )
                )
            )
        ]
    )]
    public function index()
    {
        $locations = Location::all();
        return response()->json([
            'success' => true,
            'data' => $locations
        ], 200);
    }

    #[OA\Get(
        path: '/api/v1/locations/{id}',
        summary: 'Get specific location details by ID',
        tags: ['Locations'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'Location ID',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'name', type: 'string', example: 'ParkSmart Braga'),
                        new OA\Property(property: 'address', type: 'string', example: 'Jl. Braga No. 5 Bandung'),
                        new OA\Property(property: 'capacity_car', type: 'integer', example: 40),
                        new OA\Property(property: 'capacity_motor', type: 'integer', example: 80),
                        new OA\Property(property: 'occupied_car', type: 'integer', example: 32),
                        new OA\Property(property: 'occupied_motor', type: 'integer', example: 45),
                        new OA\Property(property: 'available_car_slots', type: 'integer', example: 8),
                        new OA\Property(property: 'available_motor_slots', type: 'integer', example: 35),
                        new OA\Property(property: 'tariff_car', type: 'string', example: '5000.00'),
                        new OA\Property(property: 'tariff_motor', type: 'string', example: '2000.00'),
                        new OA\Property(property: 'operating_hours', type: 'string', example: '06:00 - 23:00')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Location not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Location not found')
                    ]
                )
            )
        ]
    )]
    public function show($id)
    {
        $location = Location::find($id);

        if (!$location) {
            return response()->json([
                'success' => false,
                'message' => 'Location not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $location
        ], 200);
    }

    #[OA\Post(
        path: '/api/v1/locations',
        summary: 'Add a new parking location (Admin)',
        tags: ['Locations'],
        security: [['ApiKeyAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name', 'address', 'capacity_car', 'capacity_motor', 'tariff_car', 'tariff_motor', 'operating_hours'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'ParkSmart Pasteur'),
                    new OA\Property(property: 'address', type: 'string', example: 'Jl. Pasteur No. 12 Bandung'),
                    new OA\Property(property: 'capacity_car', type: 'integer', example: 30),
                    new OA\Property(property: 'capacity_motor', type: 'integer', example: 60),
                    new OA\Property(property: 'tariff_car', type: 'number', format: 'float', example: 5000.00),
                    new OA\Property(property: 'tariff_motor', type: 'number', format: 'float', example: 2000.00),
                    new OA\Property(property: 'operating_hours', type: 'string', example: '06:00 - 23:00')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Location created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'data', type: 'object')
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Validation errors occurred'),
                        new OA\Property(property: 'errors', type: 'object')
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthorized: Invalid or missing X-API-KEY')
                    ]
                )
            )
        ]
    )]
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'capacity_car' => 'required|integer|min:0',
            'capacity_motor' => 'required|integer|min:0',
            'tariff_car' => 'required|numeric|min:0',
            'tariff_motor' => 'required|numeric|min:0',
            'operating_hours' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors occurred',
                'errors' => $validator->errors()
            ], 400);
        }

        $location = Location::create([
            'name' => $request->name,
            'address' => $request->address,
            'capacity_car' => $request->capacity_car,
            'capacity_motor' => $request->capacity_motor,
            'occupied_car' => 0,
            'occupied_motor' => 0,
            'tariff_car' => $request->tariff_car,
            'tariff_motor' => $request->tariff_motor,
            'operating_hours' => $request->operating_hours,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Location added successfully',
            'data' => $location
        ], 201);
    }
}
