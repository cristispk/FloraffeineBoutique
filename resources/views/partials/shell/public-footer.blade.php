<footer class="shell-public-footer">
    <x-ui.shell-page-container variant="public">
        <div class="shell-public-footer-grid">
            <section>
                <h3 class="shell-footer-title">Floraffeine Boutique</h3>
                <p>Platformă dedicată creatorilor și comunității Floraffeine.</p>
            </section>
            <section>
                <h3 class="shell-footer-title">Navigare</h3>
                <ul class="shell-footer-list">
                    <li><a href="{{ url('/') }}">Acasă</a></li>
                    <li><span class="is-disabled">Despre Boutique</span></li>
                    <li><span class="is-disabled">Creatori / Comercianți</span></li>
                </ul>
            </section>
            <section>
                <h3 class="shell-footer-title">Informații</h3>
                <ul class="shell-footer-list">
                    <li><span class="is-disabled">Evenimente / Showcase</span></li>
                    <li><span class="is-disabled">Cum funcționează</span></li>
                    <li><a href="{{ Route::has('login') ? route('login') : '#' }}">Autentificare</a></li>
                </ul>
            </section>
        </div>
        @hasSection('public_footer_extra')
            <div class="shell-public-footer-extra">
                @yield('public_footer_extra')
            </div>
        @endif
        <div class="shell-public-footer-copy">© {{ date('Y') }} Floraffeine Boutique</div>
    </x-ui.shell-page-container>
</footer>
