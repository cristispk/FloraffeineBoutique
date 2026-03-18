(function(){
  const qs = (s, el=document) => el.querySelector(s);
  const qsa = (s, el=document) => Array.from(el.querySelectorAll(s));

  function bindThumbs(){
    qsa('.product-gallery-set').forEach(set => {
      const main = qs('.product-main img', set);
      const splRoot = qs('.product-thumbs', set);
      if (!main || !splRoot) return;
      splRoot.addEventListener('click', (e)=>{
        const b = e.target.closest('.thumb');
        if (!b) return;
        const src = b.getAttribute('data-img');
        if (src) main.src = src;
        qsa('.thumb', splRoot).forEach(t=>t.classList.remove('is-active'));
        b.classList.add('is-active');
      });
      if (window.Splide){
        try {
          const slides = qsa('.splide__slide', splRoot);
          const barWrap = qs('.thumbs-progress', set);
          const ul = qs('.splide__list', splRoot);

          const spl = new Splide(splRoot, { type: 'slide', perPage: 6, gap: '6px', pagination: false, arrows: false,
            breakpoints: { 1200:{ perPage:6 }, 992:{ perPage:5 }, 768:{ perPage:4 }, 576:{ perPage:3 } }
          });
          const bar = qs('.thumbs-progress-bar', set);
          if (bar && barWrap){
            const update = ()=>{
              try{ const end = spl.Components.Controller.getEnd()||0; const ratio = end>0 ? Math.min(1,(spl.index||0)/end) : 0; const IND=20; bar.style.width = IND+'%'; const maxLeft=100-IND; bar.style.left = Math.min(maxLeft, Math.max(0, ratio*maxLeft))+'%'; }catch(_){}}
            spl.on('mounted move', update);
            const seek=(x)=>{ const rect = barWrap.getBoundingClientRect(); const dx = Math.min(Math.max(x-rect.left,0), rect.width); const r = rect.width? dx/rect.width : 0; const end = spl.Components.Controller.getEnd()||0; spl.go(Math.round(r*end)); };
            let down=false; barWrap.addEventListener('mousedown', (e)=>{down=true; barWrap.classList.add('is-dragging'); seek(e.clientX);});
            document.addEventListener('mousemove', (e)=>{ if(down) seek(e.clientX); });
            document.addEventListener('mouseup', ()=>{ if(down){down=false; barWrap.classList.remove('is-dragging'); } });
          }
          spl.on('mounted', ()=>{
            try {
              const per = spl.options.perPage || 6;
              const show = slides.length > per;
              if (barWrap) barWrap.style.display = show ? 'block' : 'none';
              if (ul) { ul.style.display = show ? '' : 'flex'; ul.style.justifyContent = show ? '' : 'center'; }
            } catch(_) {}
          });
          spl.mount();
        } catch(_) {}
      }
    });
  }

  function initPickers(){
    qsa('.size-picker').forEach(p => {
      p.addEventListener('click', (e)=>{
        const opt = e.target.closest('.size-option');
        if (!opt || !p.contains(opt)) return;
        qsa('.size-option', p).forEach(o=>o.classList.remove('is-selected'));
        opt.classList.add('is-selected');
      });
    });

    const delivery = qs('#subDelivery');
    if (delivery){
      delivery.addEventListener('click', ()=>{
        const isPickup = delivery.querySelector('.size-option.is-selected')?.getAttribute('data-value') === 'ridicare';
        const addr = qs('.delivery-address');
        const pickup = qs('.delivery-pickup');
        if (addr) addr.style.display = isPickup ? 'none' : '';
        if (pickup) pickup.style.display = isPickup ? '' : 'none';
      });
    }

    const donation = qs('#donationToggle');
    if (donation){
      donation.addEventListener('change', ()=>{
        const extra = qs('.donation-extra');
        if (extra) extra.style.display = donation.checked ? '' : 'none';
      });
    }
  }

  function initAddToCart(){
    const btn = qs('#subAddToCart');
    if (!btn) return;
    const totalOut = qs('#subTotal');
    const fmt = new Intl.NumberFormat('ro-RO');

    function computeTotal(){
      const base = 89;
      const freq = document.querySelector('#subFrequency .size-option.is-selected')?.getAttribute('data-value');
      const multiplier = freq === 'saptamanal' ? 4 : (freq === 'bilunar' ? 2 : 1);
      const months = parseInt(document.querySelector('#subDuration .size-option.is-selected')?.getAttribute('data-value')||'3',10);
      const monthly = base * multiplier;
      const donation = parseInt(qs('#donationAmount')?.value||'0',10) || 0;
      const clampedDonation = Math.min(10000, Math.max(0, donation));
      const total = (monthly + (qs('#donationToggle')?.checked ? clampedDonation : 0)) * months;
      const subM = qs('#subMonthly');
      const monthlyWithDonation = monthly + (qs('#donationToggle')?.checked ? clampedDonation : 0);
      if (subM) subM.textContent = 'Preț lunar: ' + fmt.format(monthlyWithDonation) + ' RON';
      if (totalOut) totalOut.textContent = fmt.format(total) + ' RON';
      return total;
    }

    document.addEventListener('click', (e)=>{ if (e.target.closest('.size-picker')) computeTotal(); });
    const donateInput = qs('#donationAmount');
    if (donateInput) donateInput.addEventListener('input', computeTotal);
    computeTotal();

    btn.addEventListener('click', ()=>{
      const title = qs('h1.brand-serif')?.textContent?.trim() || 'Abonament';
      const imgEl = qs('.product-main img');
      if (window.FloraffeineCart && window.FloraffeineCart.add){
        const price = (qs('#subTotal')?.textContent||'0').replace(/[^0-9]/g,'');
        window.FloraffeineCart.add({ id: title, title: title, price: price, imgEl });
      }
    });
  }

  document.addEventListener('DOMContentLoaded', function(){
    if (!document.body.classList.contains('page-subscription')) return;
    bindThumbs();
    initPickers();
    initAddToCart();
  });
})();


