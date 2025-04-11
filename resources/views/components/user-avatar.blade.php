
@props([
    'avatar' => null,
    'name',
    'circle' => true
])



<flux:avatar
    circle
    src="{{ $avatar
            ? (strpos($avatar, 'storage/') === 0 ? asset($avatar) : $avatar)
            : "https://ui-avatars.com/api/?name=" . urlencode($name) . "&background=random" }}"
    title="{{ $name }}"
/>
