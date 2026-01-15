// public/js/api.js

function getBaseUrl() {
    // base_url() ist bei dir z.B. http://localhost/Bootsverleih/public/
    return (window.APP?.baseUrl || '').replace(/\/$/, '');
}

function buildUrl(path) {
    if (!path.startsWith('/')) path = `/${path}`;
    return `${getBaseUrl()}${path}`;
}

// Objekt -> FormData
// Default: packt alles unter "data" als JSON string
export function toFormData(obj, key = 'data') {
    const fd = new FormData();
    fd.append(key, JSON.stringify(obj ?? {}));
    return fd;
}

export async function apiGet(path) {
    const res = await fetch(buildUrl(path));
    if (!res.ok) throw new Error(`GET ${path} -> ${res.status}`);
    return res.json();
}

// FormData POST (mit CSRF Header)
export async function apiPostFormData(path, formData) {
    const res = await fetch(buildUrl(path), {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': window.APP?.csrf || '',
        },
        body: formData,
    });

    if (!res.ok) {
        const text = await res.text().catch(() => '');
        console.error(`POST ${path} failed`, res.status, text);
        throw new Error(`POST ${path} -> ${res.status}`);
    }

    // wenn Response JSON ist -> json() sonst true
    const ct = res.headers.get('content-type') || '';
    if (ct.includes('application/json')) return res.json();
    return true;
}
