@if (!isset($show) || $show)
    <div {{ $attributes->merge(['class' => 'badge badge-'.$type]) }} >
        {{ $message }}
    </div>
@endif