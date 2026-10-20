<style>
    * { box-sizing: border-box; }
    body {
        margin: 0;
        font-family: 'Inter', system-ui, sans-serif;
        background: #0c0a09;
        color: #e7e5e4;
    }
    .admin-main {
        margin-left: 16rem;
        padding: 2rem 2.25rem;
        min-height: 100vh;
        max-width: 56rem;
    }
    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    .page-header h1 { font-size: 1.4rem; font-weight: 700; color: #fafaf9; margin: 0 0 0.25rem; }
    .page-header p { color: #a8a29e; font-size: 0.85rem; margin: 0; }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.6rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.8125rem;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }
    .btn-primary { background: #C28E27; color: #1c1917; }
    .btn-primary:hover { background: #d9a43a; }
    .btn-ghost { background: rgba(255,255,255,0.06); color: #e7e5e4; }
    .btn-ghost:hover { background: rgba(255,255,255,0.1); }
    .btn-danger { background: rgba(220,38,38,0.15); color: #fca5a5; }
    .btn-danger:hover { background: rgba(220,38,38,0.28); }
    .btn-sm { padding: 0.4rem 0.7rem; font-size: 0.72rem; }

    .alert {
        padding: 0.75rem 1rem;
        border-radius: 0.6rem;
        font-size: 0.82rem;
        margin-bottom: 1.25rem;
    }
    .alert-success { background: rgba(34,197,94,0.12); color: #86efac; border: 1px solid rgba(34,197,94,0.3); }
    .alert-error { background: rgba(220,38,38,0.12); color: #fca5a5; border: 1px solid rgba(220,38,38,0.3); }

    .card {
        background: #1c1917;
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 0.85rem;
        overflow: hidden;
    }
    .form-card { padding: 1.5rem; }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 1.1rem;
    }
    .field { display: flex; flex-direction: column; gap: 0.35rem; }
    .field label { font-size: 0.78rem; font-weight: 600; color: #a8a29e; }
    .field input, .field select {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 0.5rem;
        padding: 0.55rem 0.75rem;
        color: #fafaf9;
        font-size: 0.85rem;
        font-family: inherit;
    }
    .field input:focus, .field select:focus {
        outline: none;
        border-color: #C28E27;
    }
    .field-error { color: #fca5a5; font-size: 0.72rem; }
    .field-hint { color: #78716c; font-size: 0.72rem; margin: 0.4rem 0 0; }

    .field-group { margin-bottom: 1.25rem; }
    .field-group h3 {
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #78716c;
        margin: 0 0 0.6rem;
    }
    .checkbox-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.5rem;
    }
    .checkbox-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 0.5rem;
        padding: 0.5rem 0.7rem;
    }
    .checkbox-item input { accent-color: #C28E27; }

    .form-actions { margin-top: 1.5rem; display: flex; justify-content: flex-end; }

    table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
    th, td { text-align: left; padding: 0.85rem 1.1rem; }
    thead th {
        color: #78716c;
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    tbody tr { border-bottom: 1px solid rgba(255,255,255,0.05); }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: rgba(255,255,255,0.025); }

    .staf-name { font-weight: 600; color: #fafaf9; }
    .staf-email { color: #78716c; font-size: 0.76rem; }

    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.2rem 0.55rem;
        border-radius: 9999px;
        font-size: 0.68rem;
        font-weight: 700;
    }
    .badge-super { background: rgba(194,142,39,0.18); color: #C28E27; }
    .badge-admin { background: rgba(255,255,255,0.08); color: #a8a29e; }

    .row-actions { display: flex; gap: 0.4rem; flex-wrap: wrap; }
    .empty-state { padding: 3rem 1rem; text-align: center; color: #78716c; }
</style>
