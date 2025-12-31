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
        $skip = $page * 10;
        $notifications = Notification::get()->all->skip($skip)->take(10);
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
            'next' => route('notification.show-more', ($page + 1)),
            'rem' => Notification::get()->all->skip($skip + 10)->count(),
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function notifications(): JsonResponse
    {
        return response()->json([
            'result' => 1,
            'response' => 'Notifications list.',
            'notifications' =>  Notification::get(),
        ]);
    }

    /**
     * @param $item
     * @return JsonResponse
     */
    public function read($item): JsonResponse
    {
        $response = 1;
        if ($item === 'all') {
            foreach (Notification::get()->unread as $unread) {
                $unread->markAsRead();
            }
            $response = 'all';
        }
        Notification::get()->unread->where('id', $item)?->markAsRead();

        return response()->json([
            'result' => 1,
            'response' => $response,
            'unread' => Auth::user()->unreadNotifications()->count(),
        ]);
    }

    /**
     * @param $item
     * @return JsonResponse
     */
    public function delete($item): JsonResponse
    {
        $user = Auth::user();
        $response = 1;
        if ($item === 'all') {
            $user->notifications()->take(10)->delete();
            $response = 'all';
        }
        $user->notifications()->where('id', $item)?->delete();

        return response()->json([
            'result' => 1,
            'response' => $response,
            'unread' => Auth::user()->unreadNotifications()->count(),
        ]);
    }
}
