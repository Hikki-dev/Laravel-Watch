/* 360 viewer — drag-to-scrub frame sequence (the technique luxury watch sites use).
   Layout and the first frame live in the markup + CSS (.s360 / .s360-layer), so thumbnails need no script at all.
   This script only adds interaction to elements that carry data-live.

   <div class="s360 s360-live" data-spin360 data-live="1" data-cols="8" data-rows="6" data-count="48" data-frame="46"
        data-auto="spin|sway" role="slider" tabindex="0" aria-label="Rotate the watch">
     <div class="s360-layer" style="background-image:url(sheet.webp);background-position:..."></div>
   </div>
   Real photography: set data-frames='["f01.jpg","f02.jpg",...]' instead of a sprite sheet.
   Views: elements inside the same [data-s360-scope] with data-s360-view="front|angle|side|back" (or a frame number). */
(function () {
  if (window.Spin360) return;
  var VIEWS = { front: 0, angle: 0.9583, side: 0.25, back: 0.5 }; // fractions of a full turn
  var reduce = window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;

  function mount(el) {
    if (el._s360 || el.dataset.live == null) return; el._s360 = true;
    var d = el.dataset, frames = null, cols = parseInt(d.cols || '8', 10), rows = parseInt(d.rows || '6', 10), n;
    if (d.frames) { try { frames = JSON.parse(d.frames); } catch (e) { } }
    n = frames ? frames.length : parseInt(d.count || '48', 10);
    var f = ((parseInt(d.frame || '0', 10) % n) + n) % n, base = f, auto = reduce ? null : (d.auto || null);
    var layer = el.querySelector('.s360-layer'); if (!layer) return;
    if (frames) {
      layer.style.backgroundSize = 'contain';
      // Warm the browser cache only once the viewer is on screen, first frame first, the rest when idle.
      var warm = new IntersectionObserver(function (e, o) {
        if (!e[0].isIntersecting) return; o.disconnect();
        var i = 0, next = function () { if (i >= frames.length) return; var im = new Image(); im.decoding = 'async'; im.src = frames[i++]; (window.requestIdleCallback || setTimeout)(next, 30); };
        next(); next(); next();
      }); warm.observe(el);
    }

    function show(i) {
      i = ((Math.round(i) % n) + n) % n;
      if (frames) { layer.style.backgroundImage = 'url("' + frames[i] + '")'; layer.style.backgroundPosition = 'center'; return; }
      var c = i % cols, r = Math.floor(i / cols);
      layer.style.backgroundPosition = (cols > 1 ? c / (cols - 1) * 100 : 0) + '% ' + (rows > 1 ? r / (rows - 1) * 100 : 0) + '%';
    }
    function aria() { el.setAttribute('aria-valuenow', String(((Math.round(f) % n) + n) % n)); }

    var st = { drag: false, x: 0, v: 0, anim: null, auto: auto, visible: true, t: 0 };
    var io = new IntersectionObserver(function (e) { st.visible = e[0].isIntersecting; }); io.observe(el);
    function stop() { st.auto = null; st.anim = null; }
    el.addEventListener('pointerdown', function (e) { st.drag = true; st.x = e.clientX; st.v = 0; stop(); try { el.setPointerCapture(e.pointerId); } catch (x) { } });
    el.addEventListener('pointermove', function (e) {
      if (!st.drag) return; var dx = e.clientX - st.x; st.x = e.clientX;
      var df = -dx * (n / Math.max(260, el.clientWidth * 1.1)); f += df; st.v = df;
    });
    function up() { st.drag = false; }
    el.addEventListener('pointerup', up); el.addEventListener('pointercancel', up);
    el.addEventListener('keydown', function (e) { if (e.key === 'ArrowLeft') { stop(); f -= 1; } else if (e.key === 'ArrowRight') { stop(); f += 1; } else return; e.preventDefault(); });
    el._s360api = { go: function (target) { var cur = ((f % n) + n) % n, diff = ((target - cur + n * 1.5) % n) - n / 2; st.anim = { from: f, to: f + diff, t: 0 }; st.auto = null; st.v = 0; } };

    var last = performance.now();
    (function loop(now) {
      if (!el.isConnected) { io.disconnect(); return; }
      requestAnimationFrame(loop); if (!st.visible) return;
      var dt = Math.min(0.05, (now - last) / 1000); last = now;
      if (st.anim) { st.anim.t = Math.min(1, st.anim.t + dt / 0.9); var t = st.anim.t, e = t < .5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2; f = st.anim.from + (st.anim.to - st.anim.from) * e; if (st.anim.t >= 1) st.anim = null; }
      else if (!st.drag) {
        if (Math.abs(st.v) > 0.01) { f += st.v; st.v *= 0.93; }
        else if (st.auto === 'spin') { f += dt * n * 0.1; }
        else if (st.auto === 'sway') { st.t += dt; f = base + Math.sin(st.t * 0.7) * n * 0.05; }
      }
      show(f); aria();
    })(performance.now());
  }

  function scan(root) { (root || document).querySelectorAll('[data-spin360][data-live]').forEach(mount); }
  document.addEventListener('click', function (e) {
    var b = e.target.closest && e.target.closest('[data-s360-view]'); if (!b) return;
    var scope = b.closest('[data-s360-scope]') || document, v = scope.querySelector('[data-spin360][data-live]'); if (!v || !v._s360api) return;
    var key = b.dataset.s360View, cnt = parseInt(v.dataset.count || '48', 10);
    var t = VIEWS[key] != null ? Math.round(VIEWS[key] * cnt) % cnt : parseInt(key, 10);
    v._s360api.go(t);
    scope.querySelectorAll('[data-s360-view]').forEach(function (x) { x.setAttribute('aria-pressed', x === b ? 'true' : 'false'); });
  });
  new MutationObserver(function () { scan(document); }).observe(document.documentElement, { childList: true, subtree: true });
  if (document.readyState !== 'loading') scan(document); else document.addEventListener('DOMContentLoaded', function () { scan(document); });
  window.Spin360 = { mount: mount, scan: scan };
})();
