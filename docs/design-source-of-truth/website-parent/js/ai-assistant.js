(function(){
  document.addEventListener('DOMContentLoaded', function(){
    if (!document.body.classList.contains('page-buchet-ai')) return;
    var range = document.getElementById('budgetRange');
    var curr = document.getElementById('budgetCurr');
    if (range && curr) {
      var fmt = new Intl.NumberFormat('ro-RO');
      var update = function(){ curr.textContent = fmt.format(parseInt(range.value || '0', 10)) + ' RON'; };
      range.addEventListener('input', update);
      update();
    }

    var form = document.querySelector('.ai-form');
    if (!form) return;

    form.addEventListener('submit', function(e){
      e.preventDefault();
      startGenerating();
      setTimeout(function(){ showResults(); }, 1800);
    });

    function startGenerating(){
      var section = document.querySelector('.ai-form-section');
      if (!section) return;
      
      var formCard = document.querySelector('.ai-form .form-card');
      if (formCard) formCard.classList.add('is-hidden');
      section.classList.add('is-loading');
    }

    function showResults(){
      var section = document.querySelector('.ai-form-section');
      var results = document.getElementById('aiStep2');
      if (section) { section.classList.remove('is-loading'); section.classList.add('is-hidden'); }
      if (results) {
        results.classList.remove('is-hidden');
        initSelectionBehavior();
        initFinalAddToCart();
      }
    }

    function initSelectionBehavior(){
      var grid = document.querySelector('.ai-rec-grid');
      var resultsWrap = document.querySelector('#aiStep2.ai-results') || document.querySelector('.ai-results');
      var backBtn = document.querySelector('#aiStep2 .ai-back');
      if (!grid || !resultsWrap) return;

      grid.addEventListener('click', function(e){
        var card = e.target.closest('[data-ai-product]');
        if (!card) return;
        e.preventDefault();
        resultsWrap.classList.add('has-selected');
        if (backBtn) backBtn.hidden = false;
        var detail = resultsWrap.querySelector('.ai-detail');
        if (detail) detail.classList.remove('is-hidden');
        
        grid.querySelectorAll('[data-ai-product]').forEach(function(c){ c.classList.toggle('is-selected', c === card); });
        var overlay = card.querySelector('.thumb-overlay-link');
        if (overlay) { overlay.dataset.wasHidden = '1'; overlay.style.display = 'none'; }
        
        var title = card.querySelector('.product-title')?.textContent?.trim() || '';
        var price = card.querySelector('.price')?.textContent?.trim() || '';
        var imgSrc = card.querySelector('img')?.src || '';
        var detailTitle = document.getElementById('aiDetailTitle');
        if (detailTitle) detailTitle.textContent = title;
        
        var mainImg = document.getElementById('aiMainImg');
        if (mainImg && imgSrc) mainImg.src = imgSrc;
        initAIGalleryThumbs();
        
        var priceOld = document.getElementById('aiDetailPriceOld');
        var priceNew = document.getElementById('aiDetailPriceNew');
        if (priceNew) priceNew.textContent = price || '';
        if (priceOld && price) {
          try {
            var p = parseInt((price||'').replace(/[^0-9]/g,''),10);
            if (!isNaN(p)) priceOld.textContent = (p + Math.max(20, Math.round(p*0.2))) + ' RON';
          } catch(_) {}
        }
      });

      function handleBack(){
        resultsWrap.classList.remove('has-selected');
        var detail = resultsWrap.querySelector('.ai-detail');
        if (detail) detail.classList.add('is-hidden');
        if (backBtn) backBtn.hidden = true;
          grid.querySelectorAll('[data-ai-product]').forEach(function(c){ c.classList.remove('is-selected'); });
          
          grid.querySelectorAll('.thumb-overlay-link').forEach(function(link){ if (link.dataset.wasHidden) { link.style.display = ''; delete link.dataset.wasHidden; } });
      }

      if (backBtn) backBtn.addEventListener('click', handleBack);
      
      resultsWrap.addEventListener('click', function(ev){
        var t = ev.target.closest('.ai-back');
        if (t) { ev.preventDefault(); handleBack(); }
      });
    }

    function ensureSelectedCentered(card){
      try { card.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'center' }); } catch(_) {}
    }

    function showBackToList(){ /* no-op: now using a single static button */ }

    function initFinalAddToCart(){
      var btn = document.getElementById('addToCartFinal');
      if (!btn) return;
      btn.addEventListener('click', function(){
        var selected = document.querySelector('.ai-rec-grid .is-selected');
        if (!selected) { try { btn.classList.add('shake'); setTimeout(function(){ btn.classList.remove('shake'); }, 500); } catch(_) {} return; }
        var title = selected.querySelector('.product-title')?.textContent?.trim() || 'Produs';
        var priceText = selected.querySelector('.price')?.textContent?.replace(/[^0-9]/g, '') || '0';
        var id = selected.getAttribute('data-ai-product') || title;
        
        var imgEl = document.querySelector('#aiDetailMedia img') || selected.querySelector('.product-thumb img');
        if (window.FloraffeineCart && window.FloraffeineCart.add) {
          window.FloraffeineCart.add({ id: id, title: title, price: priceText, imgEl: imgEl });
        }
      });

      
      var toggleBtn = document.querySelector('[data-ai-toggle="greet"]');
      var textarea = document.getElementById('greetText');
      if (toggleBtn && textarea) {
        toggleBtn.addEventListener('click', function(){
          var isReadOnly = textarea.hasAttribute('readonly');
          if (isReadOnly) {
            textarea.removeAttribute('readonly');
            toggleBtn.textContent = 'Salvează';
            textarea.focus();
          } else {
            textarea.setAttribute('readonly', '');
            toggleBtn.textContent = 'Editează';
          }
        });

      
      var grid = document.querySelector('.ai-addons-grid');
      if (grid) {
        grid.addEventListener('mouseenter', function(e){
          var item = e.target.closest('.addon');
          if (!item) return;
        }, true);
        grid.addEventListener('mouseover', function(e){
          var item = e.target.closest('.addon');
          if (!item || !grid.contains(item)) return;
          showPopover(item);
        });
        grid.addEventListener('mouseout', function(e){
          var item = e.target.closest('.addon');
          if (!item || !grid.contains(item)) return;
          hidePopover(item);
        });
      }

      function showPopover(item){
        var src = item.getAttribute('data-pop-img');
        if (!src) return;
        var pop = item.querySelector('.ai-popover');
        if (!pop) {
          pop = document.createElement('div');
          pop.className = 'ai-popover';
          var img = document.createElement('img');
          img.src = src;
          pop.appendChild(img);
          item.appendChild(pop);
        }
        
        pop.style.display = 'block';
        requestAnimationFrame(function(){
          try {
            var rect = pop.getBoundingClientRect();
            var overflowLeft = rect.left < 8;
            var overflowRight = rect.right > (window.innerWidth - 8);
            if (overflowLeft) {
              var shift = 8 - rect.left;
              pop.style.left = 'calc(50% + ' + shift + 'px)';
            } else if (overflowRight) {
              var shiftR = rect.right - (window.innerWidth - 8);
              pop.style.left = 'calc(50% - ' + shiftR + 'px)';
            } else {
              pop.style.left = '50%';
            }
          } catch(_) {}
        });
      }
      function hidePopover(item){
        var pop = item.querySelector('.ai-popover');
        if (pop) pop.style.display = 'none';
      }
      }
    }

    function initAIGalleryThumbs(){
      var root = document.getElementById('aiThumbs');
      var main = document.getElementById('aiMainImg');
      if (!root || !main) return;
      root.addEventListener('click', function(e){
        var b = e.target.closest('.thumb');
        if (!b) return;
        var src = b.getAttribute('data-img');
        if (src) main.src = src;
        root.querySelectorAll('.thumb').forEach(function(t){ t.classList.remove('is-active'); });
        b.classList.add('is-active');
      });
      if (window.Splide) {
        try {
          var spl = root._splide || new Splide('#aiThumbs', { type: 'slide', perPage: 6, gap: '6px', pagination: false, arrows: false,
            breakpoints: { 1200:{ perPage:6 }, 992:{ perPage:5 }, 768:{ perPage:4 }, 576:{ perPage:3 } }
          });
          root._splide = spl;
          var barWrap = document.querySelector('.thumbs-progress');
          var bar = document.querySelector('.thumbs-progress-bar');
          if (bar && barWrap) {
            var update = function(){
              try { var end = spl.Components.Controller.getEnd()||0; var ratio = end>0 ? Math.min(1,(spl.index||0)/end) : 0; var IND=20; bar.style.width = IND+'%'; var maxLeft=100-IND; bar.style.left = Math.min(maxLeft, Math.max(0, ratio*maxLeft))+'%'; } catch(_){}
            };
            spl.on('mounted move', update);
            var seek=function(x){ var rect = barWrap.getBoundingClientRect(); var dx = Math.min(Math.max(x-rect.left,0), rect.width); var r = rect.width? dx/rect.width : 0; var end = spl.Components.Controller.getEnd()||0; spl.go(Math.round(r*end)); };
            var down=false; barWrap.addEventListener('mousedown', function(e){down=true; barWrap.classList.add('is-dragging'); seek(e.clientX);});
            document.addEventListener('mousemove', function(e){ if(down) seek(e.clientX); });
            document.addEventListener('mouseup', function(){ if(down){down=false; barWrap.classList.remove('is-dragging'); } });
          }
          spl.on('mounted', function(){ try { var slides = root.querySelectorAll('.splide__slide'); var per = spl.options.perPage||6; var show = slides.length>per; if (barWrap) barWrap.style.display = show?'block':'none'; var ul = root.querySelector('.splide__list'); if (ul){ ul.style.display = show?'':'flex'; ul.style.justifyContent = show?'':'center'; } } catch(_){} });
          spl.mount();
        } catch(_) {}
      }
    }
  });
})();


