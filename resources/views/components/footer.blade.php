@props([
    'company' => 'Rumah Sakit',
    'year' => true,
    'powered' => 'mLITE',
    'bgColor' => '#0264d6',
    'textColor' => '#fff'
])

<footer class="footer" style="background-color: {{ $bgColor }}; color: {{ $textColor }};">
    <div class="footer-left">
        @if($year)
            © {{ date('Y') }} {{ $company }}
        @else
            {{ $company }}
        @endif
    </div>

    <div class="footer-center">
        <!-- Additional links if needed -->
    </div>

    <div class="footer-right">
        @if($powered)
            Powered by <a href="https://github.com/basoro/mlite" target="_blank" style="color: inherit; text-decoration: underline;">{{ $powered }}</a>
        @endif
    </div>
</footer>

<style>
.footer {
    padding: 12px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13px;
    gap: 20px;
}

.footer-left,
.footer-right {
    flex: 1;
}

.footer-center {
    flex: 2;
    text-align: center;
}

.footer-right {
    text-align: right;
}

@media (max-width: 768px) {
    .footer {
        flex-direction: column;
        gap: 10px;
        text-align: center;
        padding: 10px 20px;
    }
    
    .footer-left,
    .footer-center,
    .footer-right {
        flex: auto;
    }
}
</style>