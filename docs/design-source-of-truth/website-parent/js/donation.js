(function(){
  const qs = (s, el=document) => el.querySelector(s);
  const qsa = (s, el=document) => Array.from(el.querySelectorAll(s));

  function bindThumbs(){
    qsa('.product-gallery-set').forEach(set => {
      const main = qs('.product-main img', set);
      const root = qs('.product-thumbs', set);
      if (!main || !root) return;
      root.addEventListener('click', (e)=>{
        const b = e.target.closest('.thumb');
        if (!b) return;
        const src = b.getAttribute('data-img');
        if (src) main.src = src;
        qsa('.thumb', root).forEach(t=>t.classList.remove('is-active'));
        b.classList.add('is-active');
      });
      if (window.Splide){
        try {
          const slides = qsa('.splide__slide', root);
          const ul = qs('.splide__list', root);
          const barWrap = qs('.thumbs-progress', set);
          const spl = new Splide(root, { type: 'slide', perPage: 6, gap: '6px', pagination: false, arrows: false,
            breakpoints: { 1200:{ perPage:6 }, 992:{ perPage:5 }, 768:{ perPage:4 }, 576:{ perPage:3 } }
          });
          const bar = qs('.thumbs-progress-bar', set);
          if (bar && barWrap){
            const update = ()=>{ try { const end = spl.Components.Controller.getEnd()||0; const ratio = end>0 ? Math.min(1,(spl.index||0)/end):0; const IND=20; bar.style.width=IND+'%'; const max=100-IND; bar.style.left = Math.min(max, Math.max(0, ratio*max))+'%'; } catch(_){} };
            spl.on('mounted move', update);
            const seek=(clientX)=>{ const rect = barWrap.getBoundingClientRect(); const x = Math.min(Math.max(clientX - rect.left, 0), rect.width); const ratio = rect.width ? x/rect.width : 0; const end = spl.Components.Controller.getEnd()||0; spl.go(Math.round(ratio*end)); };
            let down=false;
            barWrap.addEventListener('mousedown', (e)=>{ down=true; barWrap.classList.add('is-dragging'); seek(e.clientX); });
            document.addEventListener('mousemove', (e)=>{ if(down) seek(e.clientX); });
            document.addEventListener('mouseup', ()=>{ if(down){ down=false; barWrap.classList.remove('is-dragging'); } });
            barWrap.addEventListener('touchstart', (e)=>{ down=true; barWrap.classList.add('is-dragging'); seek(e.touches[0].clientX); }, { passive: true });
            document.addEventListener('touchmove', (e)=>{ if(down) seek(e.touches[0].clientX); }, { passive: true });
            document.addEventListener('touchend', ()=>{ if(down){ down=false; barWrap.classList.remove('is-dragging'); } });
          }
          spl.on('mounted', ()=>{ try { const show = slides.length > (spl.options.perPage||6); if (barWrap) barWrap.style.display = show?'block':'none'; if (ul){ ul.style.display = show?'':'flex'; ul.style.justifyContent = show?'':'center'; } } catch(_){} });
          spl.mount();
        } catch(_) {}
      }
    });
  }

  function initAmount(){
    const select = qs('#donAmountSelect');
    const input = qs('#donCustom');
    const chosen = qs('#donChosen');
    const fmt = new Intl.NumberFormat('ro-RO');
    function update(){
      let val = select.value === 'custom' ? parseInt(input.value||'0',10)||0 : parseInt(select.value||'0',10)||0;
      val = Math.min(10000, Math.max(10, val));
      if (select.value === 'custom'){ input.style.display = ''; input.value = String(val); } else { input.style.display = 'none'; }
      if (chosen) chosen.textContent = fmt.format(val) + ' RON';
      return val;
    }
    if (select){ select.addEventListener('change', update); }
    if (input){ input.addEventListener('input', update); }
    update();

    const btn = qs('#donAddToCart');
    if (btn){ btn.addEventListener('click', ()=>{
      const price = (update()).toString();
      const title = qs('h1.brand-serif')?.textContent?.trim() || 'Donație';
      const imgEl = qs('.product-main img');
      if (window.FloraffeineCart && window.FloraffeineCart.add){ window.FloraffeineCart.add({ id: title, title: title, price: price, imgEl }); }
    }); }
  }

  document.addEventListener('DOMContentLoaded', function(){
    if (!document.body.classList.contains('page-donation')) return;
    bindThumbs();
    initAmount();
    try {
      if (window.Splide){
        const splide = new Splide('#related-splide', { type: 'loop', perPage: 5, perMove: 1, gap: '22px', pagination: false, arrows: false, breakpoints: { 1200:{ perPage:4 }, 992:{ perPage:3 }, 576:{ perPage:2 } } });
        splide.mount();
        const prev = document.querySelector('.related-prev');
        const next = document.querySelector('.related-next');
        if (prev) prev.addEventListener('click', ()=>splide.go('<'));
        if (next) next.addEventListener('click', ()=>splide.go('>'));
      }
    } catch(_){}
  });
})();


