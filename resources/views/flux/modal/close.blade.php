<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center']) }}>
    {{ $slot }}
</button>
