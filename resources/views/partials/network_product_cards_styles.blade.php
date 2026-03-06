<style>
    .public-products-grid {
        margin-top: 1.5rem;
    }

    .public-product-card {
        border: 1px solid rgba(0, 35, 77, 0.08);
        border-radius: 16px;
        background: #ffffff;
        box-shadow: 0 10px 24px rgba(10, 24, 40, 0.06);
        transition: transform .2s ease, box-shadow .2s ease;
        overflow: hidden;
        height: 100%;
    }

    .public-product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 34px rgba(10, 24, 40, 0.12);
    }

    .public-network-chip {
        margin: 12px 12px 0;
        border-radius: 999px;
        height: 26px;
        line-height: 26px;
        padding: 0 14px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .5px;
        text-transform: uppercase;
    }

    .public-network-chip.is-mtn {
        color: #2b2b2b;
        background: linear-gradient(90deg, #f5c400 0%, #ffc928 100%);
    }

    .public-network-chip.is-telecel {
        color: #b10000;
        background: #fff3f3;
    }

    .public-network-chip.is-at {
        color: #ffffff;
        background: linear-gradient(90deg, #2875f0 0%, #e9334c 100%);
    }

    .public-network-chip.is-default {
        color: #ffffff;
        background: #203047;
    }

    .public-product-body {
        padding: 14px 14px 16px;
    }

    .public-product-title {
        font-size: 1rem;
        font-weight: 700;
        color: #12263f;
        margin-bottom: 2px;
    }

    .public-product-subtitle {
        color: #6c7887;
        font-size: 13px;
        margin-bottom: 14px;
    }

    .public-product-footer {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 8px;
    }

    .public-price-label {
        display: block;
        color: #7a8797;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .public-price-value {
        margin: 0;
        color: #12263f;
        font-weight: 700;
        font-size: 1.15rem;
    }

    .public-buy-btn {
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    .public-stock-badge {
        border-radius: 10px;
        background: #fff2f2;
        color: #bf2f2f;
        padding: 8px 10px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }
</style>
