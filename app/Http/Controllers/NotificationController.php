<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $user = Auth::guard('sanctum')->user();
            $notifications = $user->notifications()->paginate(10);
            return response()->json([
                'data' => NotificationResource::collection($notifications),
                'message' => 'Notifikasi berhasil diambil'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function markAsRead($id): JsonResponse
    {
        try {
            $user = Auth::guard('sanctum')->user();
            $notification = $user->notifications()->findOrFail($id);
            $notification->markAsRead();
            return response()->json([
                'message' => 'Notifikasi berhasil ditandai sebagai dibaca'
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Notifikasi tidak ditemukan'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function markAllAsRead(): JsonResponse
    {
        try {
            $user = Auth::guard('sanctum')->user();
            $user->unreadNotifications()->update(['read_at' => now()]);
            return response()->json([
                'message' => 'Notifikasi berhasil ditandai sebagai dibaca'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
