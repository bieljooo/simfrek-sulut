<style>
    .sf-admin-page-shell,
    .sf-admin-tool-grid,
    .sf-admin-tool-actions,
    .sf-admin-style-form,
    .sf-admin-style-grid,
    .sf-admin-style-actions {
        display: grid;
        gap: 1rem;
    }

    .sf-admin-page-shell {
        grid-template-columns: minmax(0, 1fr);
    }

    .sf-admin-tool-grid {
        grid-template-columns: minmax(0, 1.2fr) minmax(280px, 0.8fr);
    }

    .sf-admin-style-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .sf-dashboard-filter-card,
    .sf-dashboard-chart-card {
        border: 1px solid #dce5ef;
        border-radius: 28px;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.98) 0%, #ffffff 100%);
        box-shadow: 0 24px 56px rgba(24, 39, 64, 0.1);
    }

    .sf-dashboard-filter-card,
    .sf-dashboard-chart-card {
        padding: 1.2rem;
    }

    .sf-admin-page-hero {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        align-items: end;
        gap: 1rem;
    }

    .sf-dashboard-kicker,
    .sf-chart-kicker {
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: #7f8ea2;
    }

    .sf-admin-page-copy,
    .sf-admin-card-copy,
    .sf-admin-table-head-copy,
    .sf-admin-style-copy {
        display: grid;
        gap: 0.2rem;
    }

    .sf-admin-page-copy strong,
    .sf-admin-card-copy strong,
    .sf-admin-table-head-copy strong,
    .sf-admin-style-copy strong,
    .sf-admin-style-card-head h3 {
        font-family: 'Space Grotesk', 'Manrope', sans-serif;
        letter-spacing: -0.04em;
        color: #18253c;
    }

    .sf-admin-page-copy strong,
    .sf-admin-table-head-copy strong {
        font-size: 1rem;
    }

    .sf-admin-page-copy p,
    .sf-admin-card-copy p,
    .sf-admin-table-head-copy p,
    .sf-admin-style-copy p,
    .sf-admin-style-card-head p {
        margin: 0;
        color: #6d7c92;
        font-size: 0.86rem;
        font-weight: 700;
    }

    .sf-chart-badge,
    .sf-admin-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.42rem;
        padding: 0.58rem 0.82rem;
        border-radius: 999px;
        background: #eef5ff;
        color: #295fb7;
        font-size: 0.76rem;
        font-weight: 800;
        white-space: nowrap;
    }

    .sf-chart-badge svg,
    .sf-admin-pill svg {
        width: 1rem;
        height: 1rem;
    }

    .sf-admin-card-head,
    .sf-admin-style-card-head,
    .sf-admin-table-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
    }

    .sf-admin-card-head,
    .sf-admin-style-card-head {
        margin-bottom: 1rem;
    }

    .sf-admin-style-card-head h3 {
        margin: 0;
        font-size: 1.02rem;
        font-weight: 800;
    }

    .sf-dashboard-button,
    .sf-dashboard-link,
    .sf-admin-upload-input,
    .sf-admin-check {
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease, background 0.2s ease;
    }

    .sf-dashboard-button,
    .sf-dashboard-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.55rem;
        min-height: 52px;
        padding: 0.88rem 1.1rem;
        border-radius: 18px;
        font-weight: 800;
        text-decoration: none;
    }

    .sf-dashboard-button {
        border: 0;
        color: #fff;
        background: linear-gradient(135deg, #182740, #4e7cf7);
        box-shadow: 0 18px 30px rgba(41, 95, 183, 0.18);
        cursor: pointer;
    }

    .sf-dashboard-link {
        border: 1px solid #dce5ef;
        color: #22334f;
        background: #fff;
    }

    .sf-dashboard-button:hover,
    .sf-dashboard-link:hover {
        transform: translateY(-1px);
    }

    .sf-dashboard-button svg,
    .sf-dashboard-link svg {
        width: 1rem;
        height: 1rem;
    }

    .sf-admin-tool-actions {
        grid-template-columns: minmax(0, 1fr);
    }

    .sf-admin-upload-input {
        width: 100%;
        padding: 0.95rem 1rem;
        border: 1px dashed #c7d6e6;
        border-radius: 18px;
        background: #f8fbff;
        color: #22334f;
        font-size: 0.9rem;
        font-weight: 700;
    }

    .sf-admin-upload-input:hover,
    .sf-admin-upload-input:focus {
        border-color: rgba(78, 124, 247, 0.4);
        box-shadow: 0 0 0 4px rgba(78, 124, 247, 0.12);
        outline: none;
    }

    .sf-admin-check {
        display: inline-flex;
        align-items: center;
        gap: 0.65rem;
        padding: 0.9rem 0.95rem;
        border: 1px solid #e3ebf4;
        border-radius: 18px;
        background: #f7f9fc;
        color: #43556f;
        font-size: 0.86rem;
        font-weight: 700;
    }

    .sf-admin-check input {
        margin: 0;
    }

    .sf-admin-table-wrap {
        overflow-x: auto;
        margin-top: 1rem;
        border: 1px solid #edf2f7;
        border-radius: 22px;
    }

    .sf-admin-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 760px;
    }

    .sf-admin-table thead th {
        padding: 0.9rem 1rem;
        background: #f8fbff;
        border-bottom: 1px solid #edf2f7;
        color: #6d7c92;
        font-size: 0.76rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        text-align: left;
    }

    .sf-admin-table tbody td {
        padding: 0.95rem 1rem;
        border-bottom: 1px solid #edf2f7;
        color: #22334f;
        font-size: 0.88rem;
        font-weight: 700;
        vertical-align: top;
    }

    .sf-admin-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .sf-admin-badge-soft {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 44px;
        padding: 0.4rem 0.7rem;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 800;
    }

    .sf-admin-badge-soft.is-success {
        background: #eef8f0;
        color: #1f7a47;
    }

    .sf-admin-badge-soft.is-danger {
        background: #fff1f2;
        color: #be123c;
    }

    .sf-admin-badge-soft.is-warning {
        background: #fff7ed;
        color: #c2410c;
    }

    .sf-admin-empty {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 180px;
        border: 1px dashed #dce5ef;
        border-radius: 22px;
        background: #f8fbff;
        color: #6d7c92;
        font-size: 0.9rem;
        font-weight: 700;
        text-align: center;
    }

    @media (max-width: 1280px) {
        .sf-admin-tool-grid,
        .sf-admin-style-grid,
        .sf-admin-page-hero {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .sf-admin-card-head,
        .sf-admin-style-card-head,
        .sf-admin-table-head {
            flex-direction: column;
        }
    }
</style>