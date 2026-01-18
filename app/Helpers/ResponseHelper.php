<?php

namespace App\Helpers;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ResponseHelper
{
    public static function genericResponse(bool $success, string $message, int $statusCode): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'message' => $message
        ], $statusCode);
    }

    public static function genericDataResponse(
        bool $success,
        string $message,
        mixed $data,
        int $statusCode
    ): JsonResponse {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    public static function genericResponseWithReason(
        bool $success,
        string $message,
        string $reason,
        int $statusCode
    ): JsonResponse {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'reason' => $reason,
        ], $statusCode);
    }

    public static function genericResponseWithErrors(
        bool $success,
        string $message,
        array $errors,
        int $statusCode
    ): JsonResponse {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }

    public static function genericException(Exception $e): JsonResponse
    {
        return self::genericResponse(false, $e->getMessage(), ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
    }

    public static function genericValidationException(Exception $e): JsonResponse
    {
        return self::genericResponseWithErrors(false, $e->getMessage(), $e->errors(), ResponseAlias::HTTP_BAD_REQUEST);
    }

    public static function genericSuccessResponse($message, $data): JsonResponse
    {
        return self::genericDataResponse(true, $message, $data, ResponseAlias::HTTP_OK);
    }

    public static function genericDataIsEmpty(): JsonResponse
    {
        return self::genericResponse(true, 'No Content Provided', ResponseAlias::HTTP_NO_CONTENT);
    }

    public static function genericDataNotFound(ModelNotFoundException $e): JsonResponse
    {
        return self::genericResponseWithReason(false, 'Data not found', $e->getMessage(), ResponseAlias::HTTP_NOT_FOUND);
    }

    public static function genericDataUpdated($type): JsonResponse
    {
        return self::genericResponse(true, $type . ' updated successfully', ResponseAlias::HTTP_OK);
    }

    public static function genericDataDeleted($type): JsonResponse
    {
        return self::genericResponse(true, $type . ' deleted successfully!', ResponseAlias::HTTP_OK);
    }

    public static function genericDataCreated($type): JsonResponse
    {
        return self::genericResponse(true, $type . ' created successfully!', ResponseAlias::HTTP_CREATED);
    }
}
