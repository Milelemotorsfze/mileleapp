/**
 * Lightweight client-side draft autosave for unstable connections.
 * - Saves form fields to localStorage periodically + on input/change
 * - Restores on reload
 * - Clears on submit (best-effort) and when user clicks "Clear draft"
 *
 * This intentionally does NOT change backend logic or request shape.
 */
(function () {
  function now() {
    return Date.now();
  }

  function safeJsonParse(str) {
    try {
      return JSON.parse(str);
    } catch {
      return null;
    }
  }

  function isSkippableField(el) {
    if (!el || !el.name) return true;
    if (el.type === "password") return true;
    if (el.type === "file") return true; // cannot restore file inputs
    if (el.name === "_token" || el.name === "_method") return true;
    return false;
  }

  function setNativeValue(el, value) {
    // Ensure frameworks/listeners see the change
    const valueSetter = Object.getOwnPropertyDescriptor(el.__proto__, "value")?.set;
    const prototype = Object.getPrototypeOf(el);
    const protoSetter = Object.getOwnPropertyDescriptor(prototype, "value")?.set;
    (valueSetter || protoSetter || function (v) { el.value = v; }).call(el, value);
  }

  function collectFormValues(form) {
    const values = {};
    const els = form.querySelectorAll("input, select, textarea");
    els.forEach((el) => {
      if (isSkippableField(el)) return;
      const name = el.name;

      if (el.type === "checkbox") {
        if (!values[name]) values[name] = [];
        if (el.checked) values[name].push(el.value ?? "on");
      } else if (el.type === "radio") {
        if (el.checked) values[name] = el.value;
      } else if (el.multiple && el.tagName === "SELECT") {
        values[name] = Array.from(el.options)
          .filter((o) => o.selected)
          .map((o) => o.value);
      } else {
        values[name] = el.value;
      }
    });
    return values;
  }

  function restoreFormValues(form, values) {
    if (!values || typeof values !== "object") return;
    const els = form.querySelectorAll("input, select, textarea");
    els.forEach((el) => {
      if (isSkippableField(el)) return;
      const name = el.name;
      if (!(name in values)) return;
      const v = values[name];

      if (el.type === "checkbox") {
        const arr = Array.isArray(v) ? v : [v];
        el.checked = arr.includes(el.value ?? "on");
      } else if (el.type === "radio") {
        el.checked = String(v) === String(el.value);
      } else if (el.multiple && el.tagName === "SELECT") {
        const arr = Array.isArray(v) ? v : [v];
        Array.from(el.options).forEach((o) => (o.selected = arr.includes(o.value)));
      } else {
        setNativeValue(el, v ?? "");
      }
      el.dispatchEvent(new Event("change", { bubbles: true }));
      el.dispatchEvent(new Event("input", { bubbles: true }));
    });
  }

  function initDraftAutosave(opts) {
    const form =
      typeof opts.form === "string" ? document.querySelector(opts.form) : opts.form;
    if (!form) return;

    const key = opts.key;
    if (!key) return;

    const metaKey = `${key}:meta`;
    const ttlMs = typeof opts.ttlMs === "number" ? opts.ttlMs : 1000 * 60 * 60 * 24; // 24h
    const intervalMs = typeof opts.intervalMs === "number" ? opts.intervalMs : 2500;

    const existing = safeJsonParse(localStorage.getItem(key));
    const meta = safeJsonParse(localStorage.getItem(metaKey));
    const isExpired = meta?.savedAt && now() - meta.savedAt > ttlMs;
    if (existing && !isExpired) {
      restoreFormValues(form, existing);
      if (typeof opts.onRestore === "function") opts.onRestore();
    } else if (isExpired) {
      localStorage.removeItem(key);
      localStorage.removeItem(metaKey);
    }

    let lastSavedAt = 0;
    function save() {
      const t = now();
      // simple throttle
      if (t - lastSavedAt < 400) return;
      lastSavedAt = t;
      const data = collectFormValues(form);
      localStorage.setItem(key, JSON.stringify(data));
      localStorage.setItem(metaKey, JSON.stringify({ savedAt: t }));
    }

    const debouncedSave = (() => {
      let to = null;
      return function () {
        clearTimeout(to);
        to = setTimeout(save, 250);
      };
    })();

    form.addEventListener("input", debouncedSave, true);
    form.addEventListener("change", debouncedSave, true);
    window.addEventListener("beforeunload", save);

    const timer = setInterval(save, intervalMs);

    form.addEventListener("submit", function () {
      // best-effort clear; if submit fails due to connectivity, beforeunload won't fire, so
      // keeping the draft is still helpful. Clear only after a tiny delay.
      setTimeout(() => {
        localStorage.removeItem(key);
        localStorage.removeItem(metaKey);
      }, 1500);
      clearInterval(timer);
    });

    if (opts.clearButtonSelector) {
      const btn = document.querySelector(opts.clearButtonSelector);
      if (btn) {
        btn.addEventListener("click", function () {
          localStorage.removeItem(key);
          localStorage.removeItem(metaKey);
        });
      }
    }
  }

  // Expose as global helper for blade views
  window.initDraftAutosave = initDraftAutosave;
})();

