@props([
    'active' => false,
    'href' => '#'
])
<a href="{{ $href }}" {{ $attributes->merge(['class' => ($active ? 'text-indigo-600 font-bold' : 'text-gray-700') . ' px-3 py-2 rounded-md transition hover:text-indigo-800']) }}>
    {{ $slot }}
</a>
