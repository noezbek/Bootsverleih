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

async function apiFetch(path, options = {}) {
    const res = await fetch(buildUrl(path), {
        credentials: 'same-origin',
        ...options
    });

    if (res.status === 401) {
        window.location.replace(buildUrl('/auth'));
        throw new Error('unauthorized');
    }

    return res;
}


export async function apiGet(path) {
    const res = await apiFetch(path);

    if (!res.ok) {
        throw new Error(`GET ${path} -> ${res.status}`);
    }

    return res.json();
}


// FormData POST (mit CSRF Header)
export async function apiPostFormData(path, formData) {
    const res = await apiFetch(path, {
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
