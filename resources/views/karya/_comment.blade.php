{{--
    Partial satu komentar / satu balasan.
    Dipakai di karya/detail.blade.php DAN di response AJAX (KaryaController@comment),
    jadi tampilannya selalu sama.
    Variabel: $c (Comment), $reply (bool)
--}}
@php
    $u      = $c->user;
    $name   = $u->name ?? 'Anonim';
    $av     = $u->avatar ?? null;
    $avUrl  = $av
        ? (str_starts_with($av, 'http') ? $av : (str_starts_with($av, '/storage') ? asset($av) : asset('storage/' . $av)))
        : null;
    $rootId = $reply ? $c->parent_id : $c->id; // balasan selalu menempel ke komentar induk (gaya IG)
@endphp

<div class="{{ $reply ? 'reply-row' : 'comment-item' }}" @unless($reply) data-id="{{ $c->id }}" @endunless>
    <div class="flex items-start gap-2.5">
        <div class="{{ $reply ? 'w-8 h-8 bg-sky-300 text-[10px]' : 'w-10 h-10 bg-yellow-300 text-xs' }} relative overflow-hidden shrink-0 border-2 border-zinc-900 rounded-xl flex items-center justify-center font-black text-zinc-900 shadow-[2px_2px_0px_#18181b]">
            @if($avUrl)
                <img src="{{ $avUrl }}" alt="{{ $name }}" class="absolute inset-0 w-full h-full object-cover">
            @else
                {{ strtoupper(substr($name, 0, 1)) }}
            @endif
        </div>

        <div class="flex-1 min-w-0">
            <div class="bg-zinc-100 dark:bg-zinc-800 border-2 border-zinc-900 dark:border-zinc-100 rounded-2xl p-3 shadow-[3px_3px_0px_#18181b] dark:shadow-[3px_3px_0px_#f4f4f5]">
                <div class="flex items-center justify-between gap-2 mb-1">
                    <span class="font-black text-xs truncate">{{ $name }}</span>
                    <span class="text-[10px] font-bold text-zinc-500 shrink-0">{{ $c->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-xs font-bold leading-relaxed break-words whitespace-pre-line">{{ $c->body }}</p>

                <!-- @if($c->type === 'gif')
                    <img src="{{ $c->attachment }}" alt="GIF" loading="lazy" class="mt-1 w-full max-w-[200px] rounded-xl border-2 border-zinc-900 dark:border-zinc-100">
                @else
                    <p class="text-xs font-bold leading-relaxed break-words whitespace-pre-line">{{ $c->body }}</p>
                @endif -->
            </div>

            <button type="button" data-reply data-id="{{ $rootId }}" data-name="{{ $name }}"
                    class="mt-1.5 ml-1 text-[11px] font-black text-zinc-500 hover:text-sky-600 cursor-pointer">
                Balas
            </button>
        </div>
    </div>

    @unless($reply)
        <div class="replies-wrap ml-5 mt-3 pl-4 border-l-[3px] border-zinc-900 dark:border-zinc-100 {{ $c->replies->isEmpty() ? 'hidden' : '' }}">
            <button type="button" data-toggle class="text-[11px] font-black text-zinc-500 hover:text-zinc-900 dark:hover:text-white cursor-pointer">Lihat {{ $c->replies->count() }} balasan</button>
            <div class="replies-list hidden space-y-3 mt-3">
                @foreach($c->replies as $r)
                    @include('karya._comment', ['c' => $r, 'reply' => true])
                @endforeach
            </div>
        </div>
    @endunless
</div>