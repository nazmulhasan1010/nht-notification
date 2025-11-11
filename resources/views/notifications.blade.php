<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>nht-notification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/nht-scss/styles.css') }}">
</head>
<body>

@php
    use Illuminate\Support\Carbon;
    use NH\Notification\Notifications\Notification;
    $pushNotify = Notification::get();
    $ntUnr = $pushNotify?->unread?->count();
@endphp

<button id="notifToggle" class="notification-btn bg-transparent border-0">
    <i class="bi bi-bell"></i>
    <span @class(['notification-badge', 'd-none' => !$ntUnr])>{{ ($ntUnr ?? 0) > 99 ? '99+' : $ntUnr }}</span>
</button>

<div id="notifOverlay" class="n-overlay" style="display:none;"></div>

<aside id="notifDrawer" class="n-drawer" aria-hidden="true">
    <div class="n-header">
        <div class="n-title"><i class="bi bi-bell"></i> Notifications ({{ $pushNotify?->all->count() }})</div>
        <div class="n-actions">
            <button data-href="{{ route('nh-notification.read', 'all') }}" class="n-link mark-as-read"
                    title="Mark all as read" id="markAll" @disabled($pushNotify?->unread->isEmpty())><i
                        class="bi bi-check2-square"></i></button>
            <button data-href="{{ route('nh-notification.delete', 'all') }}" class="n-link notify-delete text-danger"
                    title="Clear all" id="deleteAll" @disabled($pushNotify?->all->isEmpty())><i
                        class="bi bi-trash3"></i></button>
            <button id="closeDrawer" class="n-icon" aria-label="Close"><i class="bi bi-x-lg"></i></button>
        </div>
    </div>
    <div id="notifList" class="n-list">
        <div id="notifList" class="n-list">
            @forelse($pushNotify?->all->take(10) as $notify)
                @php $ndt = (object) $notify->data; $bg = $ndt->type ?? null; @endphp
                <div @class(['n-item', 'read' => $notify->read_at])>
                    <div @class(['n-dot', "bg-$bg"])></div>
                    <div>
                        <div class="n-title-line d-flex justify-content-between">
                            <p>{{ $ndt->title }}</p>
                            <span class="n-meta">
                                {{ Carbon::parse($notify->created_at)?->diffForHumans(['short' => true, 'parts' => 1]) }}
                            </span>
                        </div>
                        <div class="n-msg" id="msg-{{ $notify->id }}">
                            <span class="text">{{ $ndt->message }}</span>
                            <span class="before">...</span>
                            <button class="read-more notify-read-more" data-expand="false">Read more</button>
                        </div>
                        <div class="d-flex align-items-stretch">
                            <a href="{{ $ndt->link ?? '#' }}" title="Open the page"
                               class="n-cta mt-2" @disabled(!($ndt->link ?? null))><i class="bi bi-link-45deg"></i>
                            </a>
                            <a role="button" data-href="{{ route('nh-notification.read', $notify->id) }}"
                               title="Mark as read" class="n-cta mt-2 mx-1 mark-as-read" @disabled($notify->read_at)><i
                                        class="bi bi-check2-square"></i></a>
                            <a role="button" data-href="{{ route('nh-notification.delete', $notify->id) }}"
                               title="Delete" class="n-cta mt-2 mx-1 notify-delete text-danger"><i
                                        class="bi bi-trash3"></i></a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="h-100 text-center text-muted w-100"><small>Notifications will appear here</small></p>
            @endforelse
        </div>
    </div>
    @php
        $remaining = max(($pushNotify?->all->count() ?? 0) - 10, 0);
    @endphp
    <div class="n-footer">
        <a role="button" id="loadMore" data-href="{{ route('notification.show-more', 1) }}"
           class="n-btn-outline w-100 notify-load-more" @disabled($remaining === 0)>See previous notifications</a>
    </div>
</aside>

<div class="modal fade" id="deleteNotifyConfirm" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteNotifyModalLabel">Delete Notification</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <i class="bi bi-exclamation-triangle text-danger" style="font-size: 48px;"></i>
                <p class="mt-3 mb-0 fw-semibold">Are you sure you want to delete this notification?</p>
                <small class="text-muted d-block">This action cannot be undone.</small>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="confirm-notify-delete" class="btn btn-danger">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
<script>
    $(function ($) {
        $(document).on('click', '.notify-load-more', function () {
            let url = $(this).data('href');
            let thisEl = $(this);
            axios.get(url).then(response => {
                const data = response.data;
                const notifyBox = $('#notifList');
                if (data.status === 'success') {
                    $(notifyBox).append(data.html).animate({
                        scrollTop: $(notifyBox)[0].scrollHeight
                    }, 400);
                    $(thisEl).attr('data-href', data.next).attr('disabled', data.rem === 0);
                }
            })
        })

        $(document).on('click', '.mark-as-read', function () {
            let url = $(this).data('href');
            let thisEl = $(this);
            let notifToggle = $('#notifToggle .notification-badge');
            axios.get(url).then(response => {
                let unread = response.data['unread'];
                if (response.data.response === 1) {
                    $(thisEl).closest('.n-item').addClass('read');
                    $(thisEl).attr('disabled', 'true');
                    $(notifToggle).text(unread).toggle(unread !== 0);
                }
                if (response.data.response === 'all') {
                    $('.n-item').addClass('read');
                    $('.mark-as-read').attr('disabled', 'true');
                    $(notifToggle).hide();
                }
            })
        })

        let deletion = {url: null, trigger: null};
        let deleteConfirm = $('#deleteNotifyConfirm');
        $(document).on('click', '.notify-delete', function (e) {
            e.preventDefault();
            deletion.url = $(this).data('href');
            deletion.trigger = this;
            $(deleteConfirm).modal('show');
        })

        $('[data-bs-dismiss="modal"]').click(() => {
            deletion.url = null;
            deletion.trigger = null;
            $(deleteConfirm).modal('hide')
        })

        $('#confirm-notify-delete').click(function (e) {
            e.preventDefault();
            let notifToggle = $('#notifToggle .notification-badge');
            axios.delete(deletion.url).then(response => {
                let unread = response.data['unread'];
                if (response.data.response === 1) {
                    $(deletion.trigger).closest('.n-item').remove();
                    $(notifToggle).text(unread).toggle(unread !== 0);
                }
                if (response.data.response === 'all') {
                    $('.n-item').remove();
                    $(notifToggle).hide();
                }
            }).finally(() => {
                deletion.url = null;
                deletion.trigger = null;
                $(deleteConfirm).modal('hide')
            })
        });

        $(document).on('click', '.notify-read-more', function () {
            let expand = $(this).data('expand');
            $(this).siblings('.text').toggleClass('expanded');
            $(this).siblings('.before').toggle(expand);
            $(this).toggleClass('no-position').text(expand ? 'Read more' : 'Read less');
            $(this).data('expand', !expand);
        })

        function toggleDrawer(open) {
            $('#notifOverlay').toggle(open);
            $('#notifDrawer').toggleClass('open').attr('aria-hidden', open);
        }


        $('#notifToggle').on('click', function () {
            toggleDrawer(true);
        });

        $('#closeDrawer, #notifOverlay').on('click', function () {
            toggleDrawer(false);
        });
    })
</script>
</body>
</html>