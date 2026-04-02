<p class="brand-serif">
    Cererea ta nu a fost aprobată.
</p>
@if ($merchant->rejection_reason)
    <p><strong>Motiv:</strong> {{ $merchant->rejection_reason }}</p>
@endif
<p>
    Pentru întrebări, contactează suportul Floraffeine Boutique.
</p>
