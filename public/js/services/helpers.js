export function setText (id, v)  {
    const el = document.getElementById(id);
    if (!el) return; // verhindert Crash wenn ID fehlt
    el.textContent = v ?? '';
}

export function setValue (id, v)  {
    const el = document.getElementById(id);
    if (!el) return;
    if (v === null || v === undefined) el.value = '';
    else el.value = v;
}
export function val (id)  {
    const v = document.getElementById(id)?.value;
    return v === '' ? null : v;
}
export function escape (v) {
    return String(v ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;');
}

export function isEmpty(value) {
    return (
        value === null ||
        value === undefined ||
        (typeof value === 'string' && value.trim() === '') ||
        (Array.isArray(value) && value.length === 0) ||
        (typeof value === 'object' && !Array.isArray(value) && Object.keys(value).length === 0)
    );
}

export function formatEuro(v) {
    const n = Number(v ?? 0);
    return n.toFixed(2).replace('.', ',') + ' €';
}
