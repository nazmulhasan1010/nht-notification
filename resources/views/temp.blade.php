<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>{{ $data->type }} Notification Created</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
<div class="relative isolate overflow-hidden">
    <div class="absolute inset-0 -z-10 opacity-40">
        <div class="absolute -top-24 left-1/2 h-72 w-72 -translate-x-1/2 rounded-full bg-indigo-500 blur-3xl"></div>
        <div class="absolute top-60 left-1/4 h-72 w-72 rounded-full bg-emerald-500 blur-3xl"></div>
        <div class="absolute top-40 right-1/4 h-72 w-72 rounded-full bg-fuchsia-500 blur-3xl"></div>
    </div>

    <main class="mx-auto max-w-4xl px-4 py-10 sm:py-14">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-300">System • Temp Tool</p>
                <h1 class="mt-1 text-2xl sm:text-3xl font-semibold tracking-tight">
                    {{ $data->type }} Notification Recorded
                </h1>
                <p class="mt-2 text-slate-300">
                    Successfully queued <span class="font-semibold text-white">{{ $data->count }}</span> notification(s).
                </p>
            </div>

            <span class="inline-flex items-center gap-2 rounded-full bg-emerald-500/15 px-4 py-2 text-emerald-300 ring-1 ring-emerald-500/30"><span class="h-2 w-2 rounded-full bg-emerald-400"></span>Success</span>
        </div>
        <section class="mt-4 rounded-2xl bg-white/5 p-5 ring-1 ring-white/10">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-slate-200">Next steps</h3>
                    <p class="mt-1 text-sm text-slate-300">
                        Check your app/device logs or notification inbox to verify delivery.
                    </p>
                </div>

                <div class="flex gap-3">
                    <a href="javascript:history.back()"
                       class="inline-flex items-center justify-center rounded-xl bg-white/10 px-4 py-2 text-sm font-semibold text-white ring-1 ring-white/10 hover:bg-white/15">
                        Go Back
                    </a>
                    <a href="/"
                       class="inline-flex items-center justify-center rounded-xl bg-indigo-500 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-400">
                        Dashboard
                    </a>
                </div>
            </div>
        </section>
    </main>
</div>
</body>
</html>