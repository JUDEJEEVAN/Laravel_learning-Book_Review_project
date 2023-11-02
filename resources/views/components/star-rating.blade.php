@if($rating)
    @for ($i=0; $i<5; $i++)
        {{ $i < round($rating) ? '⭐' : ' '}}
    @endfor
@else
    No ratings yet!
@endif
