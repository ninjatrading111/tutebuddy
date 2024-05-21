@for($r = 1; $r <= $rating; $r++)
<span class="rating__item">
    <span class="material-icons">star</span>
</span>
@endfor

@if($rating > ($r-1))
<span class="rating__item">
    <span class="material-icons">star_half</span>
</span>
@elseif(($r-1) < 5)
<span class="rating__item">
    <span class="material-icons">star_border</span>
</span>
@endif

@for($r_a = $r; $r_a < 5; $r_a++) 
<span class="rating__item">
    <span class="material-icons">star_border</span>
</span>
@endfor