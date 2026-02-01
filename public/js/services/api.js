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

export async function apiGetMany(routes) {
    const entries = Object.entries(routes);
    const results = await Promise.all(
        entries.map(([_, path]) => apiGet(path))
    );

    return entries.reduce((acc, [key], i) => {
        acc[key] = results[i];
        return acc;
    }, {});
}


// FormData POST (mit CSRF Header)
export async function apiPostFormData(path, formData) {
    const res = await fetch(path, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': window.APP?.csrf || '',
        },
        body: formData,
    });

    if (!res.ok) {
        const text = await res.text().catch(() => '');
        throw new Error(`POST ${path} -> ${res.status} ${text}`);
    }

    return res.headers.get('content-type')?.includes('application/json')
        ? res.json()
        : true;
}

