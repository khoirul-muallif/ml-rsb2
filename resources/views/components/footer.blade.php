{{-- Generic Footer Component --}}
@props([
    'company' => 'Rumah Sakit',
    'year' => true,
    'powered' => 'mLITE',
    'links' => [],
    'bgColor' => '#0264d6',
    'textColor' => '#fff'
])

<footer class="shared-footer" style="background-color: {{ $bgColor }}; color: {{ $textColor }};">
    <div class="footer-content">
        <div class="footer-left">
            @if($year)
                © {{ date('Y') }} {{ $company }}
            @else
                {{ $company }}
            @endif
        </div>

        <div class="footer-center">
            @if(count($links) > 0)
                @foreach($links as $link)
                    <a href="{{ $link['url'] }}" target="{{ $link['target'] ?? '_self' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            @endif
        </div>

        <div class="footer-right">
            @if($powered)
                Powered by <strong>{{ $powered }}</strong>
            @endif
        </div>
    </div>
</footer>

<style>
.shared-footer {
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13px;
    position: relative;
    z-index: 100;
}

.footer-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    gap: 20px;
}

.footer-left, .footer-right {
    flex: 1;
}

.footer-center {
    display: flex;
    gap: 20px;
}

.footer-center a {
    color: inherit;
    text-decoration: none;
    transition: opacity 0.3s ease;
}

.footer-center a:hover {
    opacity: 0.8;
    text-decoration: underline;
}

@media (max-width: 768px) {
    .shared-footer {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }
    
    .footer-left, .footer-center, .footer-right {
        flex: auto;
    }
}
</style>