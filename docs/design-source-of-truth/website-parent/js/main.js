
const qs = (sel, el = document) => el.querySelector(sel);
const qsa = (sel, el = document) => Array.from(el.querySelectorAll(sel));

function initNavbarScrollShrink() {
  const header = qs('.nav-glass');
  if (!header) return;
  const onScroll = () => {
    if (window.scrollY > 8) header.classList.add('scrolled');
    else header.classList.remove('scrolled');
  };
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
}

function initDesktopMegaMenus() {
  const mediaQuery = window.matchMedia('(hover: hover) and (pointer: fine)');
  if (!mediaQuery.matches) return;
  qsa('.has-mega').forEach((item) => {
    const button = qs('.nav-link-btn', item);
    const panel = qs('.mega-panel', item);
    const panelVideo = panel ? panel.querySelector('video[data-mega-video]') : null;
    if (!button || !panel) return;
    let hideTimeout;
    const show = () => {
      clearTimeout(hideTimeout);
      const wasOpen = item.getAttribute('aria-expanded') === 'true';
      item.setAttribute('aria-expanded', 'true');

      if (panelVideo && !wasOpen) {
        try {
          panelVideo.currentTime = 0;
          panelVideo.loop = false;
          panelVideo.muted = true;
          panelVideo.play().catch(() => {});
        } catch(_) {}
      }
    };
    const hide = () => {
      hideTimeout = setTimeout(() => {
        item.setAttribute('aria-expanded', 'false');

        if (panelVideo) {
          try {
            panelVideo.pause();
            panelVideo.currentTime = 0;
          } catch(_) {}
        }
      }, 120);
    };
    item.addEventListener('mouseenter', show);
    item.addEventListener('mouseleave', hide);
    button.addEventListener('focus', show);
    panel.addEventListener('focusin', show);
    panel.addEventListener('focusout', hide);
  });
}

function initActionDropdowns() {
  qsa('.dropdown').forEach((wrapper) => {
    const toggle = qs('button[aria-haspopup="true"]', wrapper);
    const panel = qs('.dropdown-panel', wrapper);
    if (!toggle || !panel) return;
    const close = () => wrapper.setAttribute('aria-expanded', 'false');
    const open = () => {

      qsa('.dropdown[aria-expanded="true"]').forEach((d) => { if (d !== wrapper) d.setAttribute('aria-expanded', 'false'); });
      wrapper.setAttribute('aria-expanded', 'true');
    };
    toggle.addEventListener('click', (e) => {
      e.stopPropagation();
      const isOpen = wrapper.getAttribute('aria-expanded') === 'true';
      if (isOpen) close(); else open();
    });
    document.addEventListener('click', (e) => {
      if (!wrapper.contains(e.target)) close();
    });

    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') close(); });
  });
}

function initOffcanvas() {
  const openBtn = qs('#navOpen');
  const closeBtn = qs('#navClose');
  const drawer = qs('#drawerNav');
  const scrim = qs('#drawerScrim');
  if (!openBtn || !closeBtn || !drawer || !scrim) return;

  const open = () => {
    drawer.classList.add('is-open');
    scrim.classList.add('is-open');
    document.body.style.overflow = 'hidden';
    openBtn.setAttribute('aria-expanded', 'true');
  };
  const close = () => {
    drawer.classList.remove('is-open');
    scrim.classList.remove('is-open');
    document.body.style.overflow = '';
    openBtn.setAttribute('aria-expanded', 'false');
  };

  openBtn.addEventListener('click', open);
  closeBtn.addEventListener('click', close);
  scrim.addEventListener('click', close);
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') close(); });

  qsa('.m-collapse').forEach((section) => {
    const toggle = qs('.m-collapse-toggle', section);
    if (!toggle) return;
    toggle.addEventListener('click', () => {
      const expanded = section.getAttribute('aria-expanded') === 'true';
      section.setAttribute('aria-expanded', String(!expanded));
    });
  });
}

function initRecommendedSlider() {
  if (!window.Splide) return;
  var root = document.getElementById('rec-splide');
  if (!root) return;

  try {
    var splide = new Splide('#rec-splide', {
      type: 'loop',
      perPage: 6,
      perMove: 1,
      gap: '22px',
      pagination: false,
      arrows: true,
      autoplay: true,
      interval: 3500,
      pauseOnHover: true,
      pauseOnFocus: true,
      drag: 'free',
      snap: true,
      keyboard: 'global',
      breakpoints: {
        1600: { perPage: 6 },
        1400: { perPage: 6 },
        1200: { perPage: 5 },
        992:  { perPage: 4 },
        768:  { perPage: 2 },
        480:  { perPage: 1 }
      }
    });
    splide.mount();

    var prev = document.querySelector('.rec-prev');
    var next = document.querySelector('.rec-next');
    if (prev) prev.addEventListener('click', function(){ splide.go('<'); });
    if (next) next.addEventListener('click', function(){ splide.go('>'); });
    document.querySelectorAll('#benefits .benefit-icon video').forEach(function(v){
      try { v.play().catch(function(){}); } catch(_) {}
    });
  } catch (_) {}
}

function initBlogSlider() {
  if (!window.Splide) return;
  var root = document.getElementById('blog-splide');
  if (!root) return;
  try {
    var splide = new Splide('#blog-splide', {
      type: 'loop',
      perPage: 3,
      perMove: 1,
      gap: '22px',
      pagination: false,
      arrows: false,
      autoplay: false,
      pauseOnHover: true,
      pauseOnFocus: true,
      keyboard: 'global',
      breakpoints: {
        1200: { perPage: 3 },
        992:  { perPage: 2 },
        576:  { perPage: 1 }
      }
    });
    splide.mount();
    var prev = document.querySelector('.blog-prev');
    var next = document.querySelector('.blog-next');
    if (prev) prev.addEventListener('click', function(){ splide.go('<'); });
    if (next) next.addEventListener('click', function(){ splide.go('>'); });
  } catch(_) {}
}

document.addEventListener('DOMContentLoaded', () => {
  initNavbarScrollShrink();
  initDesktopMegaMenus();
  initActionDropdowns();
  initOffcanvas();
  initRecommendedSlider();
  initBlogSlider();
  
  var y = document.getElementById('yearCopy');
  if (y) { y.textContent = new Date().getFullYear(); }
  
  document.querySelectorAll('#benefits video').forEach(function(v){
    v.muted = true;
    var play = function(){ try { v.play().catch(function(){}); } catch(_) {} };
    if (v.readyState >= 2) play();
    v.addEventListener('canplay', play, { once: true });
  });

  
  (function(){
    var section = document.getElementById('benefits');
    if (!section) return;
    var lastY = -1;
    var onScroll = function(){
      var rect = section.getBoundingClientRect();
      var viewportH = window.innerHeight || document.documentElement.clientHeight;
      if (rect.top < viewportH && rect.bottom > 0) {
        var center = rect.top + rect.height/2 - viewportH/2;
        var offset = Math.round(center * -0.10); 
        if (offset !== lastY) {
          section.style.setProperty('--benefits-bg-shift', offset + 'px');
          lastY = offset;
        }
      }
    };
    document.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  })();

  
  (function(){
    var isPickPage = document.body.classList.contains('page-alege-buchet');
    if (!isPickPage) return;
    document.querySelectorAll('.option-card .line-art video').forEach(function(v){
      try { v.pause(); v.currentTime = 0; } catch(_) {}
      
      v.style.opacity = '1';
      var card = v.closest('.option-card');
      if (!card) return;
      card.addEventListener('mouseenter', function(){ try { v.play().catch(function(){}); } catch(_) {} });
      card.addEventListener('focusin', function(){ try { v.play().catch(function(){}); } catch(_) {} });
      card.addEventListener('mouseleave', function(){ try { v.pause(); v.currentTime = 0; } catch(_) {} });
      card.addEventListener('focusout', function(){ try { v.pause(); v.currentTime = 0; } catch(_) {} });
    });
  })();
});



