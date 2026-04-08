<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use NH\Notification\Notifications\Notification;

class NotificationController extends Controller
{
    /**
     * @param $page
     * @return JsonResponse
     */
    public function get($page): JsonResponse
    {
        $guard = request('guard');
        $user = $guard ? Auth::guard($guard)->user() : Auth::user();
        $skip = $page * 10;
        $notifications = Notification::get($user)->all->skip($skip)->take(10);
        $messages = null;

        foreach ($notifications as $notify) {
            $ndt = (object)$notify->data;
            $bg = $ndt->type ?? 'secondary';
            $read = $notify->read_at ? 'read' : null;

            $title = e($ndt->title ?? null);
            $message = e($ndt->message ?? null);
            $link = $ndt->link ?? '#';
            $isLinkDisabled = empty($ndt->link) ? 'disabled' : null;
            $isReadDisabled = $notify->read_at ? 'disabled' : null;

            $createdAt = Carbon::parse($notify->created_at)->diffForHumans(['short' => true, 'parts' => 1]);
            $markUrl = route('nh-notification.read', $notify->id);
            $deleteUrl = route('nh-notification.delete', $notify->id);

            $messages .= <<<HTML
                <div class="n-item $read">
                    <div class="n-dot bg-$bg"></div>
                    <div>
                        <div class="n-title-line d-flex justify-content-between">
                            <p>$title</p>
                            <span class="n-meta">$createdAt</span>
                        </div>
                        <div class="n-msg" id="msg-$notify->id">
                            <span class="text">$message</span>
                            <span class="before">...</span>
                            <button class="read-more notify-read-more" data-expand="false">Read more</button>
                        </div>
                        <div class="d-flex align-items-stretch">
                            <a href="$link" title="Open the page" class="n-cta mt-2" $isLinkDisabled>
                                <i class="bi bi-link-45deg"></i>
                            </a>
                            <a role="button" data-href="$markUrl"
                               title="Mark as read" class="n-cta mt-2 mx-1 mark-as-read" $isReadDisabled>
                                <i class="bi bi-check2-square"></i>
                            </a>
                            <a role="button" data-href="$deleteUrl"
                               title="Delete" class="n-cta mt-2 mx-1 notify-delete text-danger">
                                <i class="bi bi-trash3"></i>
                            </a>
                        </div>
                    </div>
                </div>
            HTML;
        }

        return response()->json([
            'status' => 'success',
            'html' => $messages,
            'next' => route('notification.show-more', ['page' => ($page + 1), 'guard' => $guard]),
            'rem' => Notification::get($user)->all->skip($skip + 10)->count(),
        ]);
    }

    /**
     * @param null $date
     * @return JsonResponse
     */
    public function notifications($date = null): JsonResponse
    {
        $guard = request('guard');
        $user = $guard ? Auth::guard($guard)->user() : Auth::user();
        $notifications = null;
        if ($date) {
            $date = Carbon::parse($date)->toDateString();
            $notifications = (object)[
                'all' => Notification::get($user)->all->filter(function ($notification) use ($date) {
                    return $notification->created_at->isSameDay($date);
                })->values(),
                'unread' => Notification::get($user)->unread->filter(function ($notification) use ($date) {
                    return $notification->created_at->isSameDay($date);
                })->values()
            ];
        }
        return response()->json([
            'result' => 1,
            'response' => 'Notifications list.',
            'notifications' => $notifications ?? Notification::get($user),
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function statistics(): JsonResponse
    {
        $guard = request('guard');
        $user = $guard ? Auth::guard($guard)->user() : Auth::user();
        return response()->json([
            'result' => 1,
            'response' => 'Notifications statistics.',
            'data' => [
                'total_notifications' => $user?->notifications()->count(),
                'unread_notifications' => $user?->unreadNotifications()->count(),
                'read_notifications' => $user?->readNotifications()->count(),
                'notifications_today' => $user?->notifications()->whereDate('created_at', today())->count(),
                'notifications_this_week' => $user?->notifications()->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'notifications_this_month' => $user?->notifications()->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
            ],
        ]);
    }

    /**
     * @param $item
     * @param null $guard
     * @return JsonResponse
     */
    public function read($item, $guard = null): JsonResponse
    {
        $guard = $guard ?? request('guard');
        $user = $guard ? Auth::guard($guard)->user() : Auth::user();
        $response = 1;
        if ($item === 'all') {
            foreach (Notification::get($user)->unread as $unread) {
                $unread->markAsRead();
            }
            $response = 'all';
        }
        Notification::get($user)->unread->where('id', $item)?->markAsRead();

        return response()->json([
            'result' => 1,
            'response' => $response,
            'unread' => $user?->unreadNotifications()->count(),
        ]);
    }

    /**
     * @param $id
     * @param null $guard
     * @return JsonResponse
     */
    public function delete($id, $guard = null): JsonResponse
    {
        $guard = $guard ?? request('guard');
        $user = $guard ? Auth::guard($guard)->user() : Auth::user();
        $response = 1;
        if ($id === 'all') {
            $user?->notifications()->take(10)->delete();
            $response = 'all';
        }
        $user?->notifications()->where('id', $id)?->delete();

        return response()->json([
            'result' => 1,
            'response' => $response,
            'unread' => $user?->unreadNotifications()->count(),
        ]);
    }
}
