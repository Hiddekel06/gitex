<header style="position: relative; background: rgba(17, 17, 17, 0.7); backdrop-filter: blur(8px); border-bottom: 1px solid rgba(90, 143, 76, 0.2); padding: 0.75rem 0; transition: all 0.3s ease;">
    <div class="d-flex align-items-center gap-3 header-left-align">
        <img src="/images/logogitex.png" alt="Logo" style="height:44px; width:auto; display:block; filter: drop-shadow(0 0 4px rgba(90,143,76,0.4)); transition: filter 0.3s, transform 0.2s;">
        <h1 class="h5 mb-0 fw-semibold" style="color: #fff; letter-spacing: -0.3px; transition: text-shadow 0.3s;">Gov'Athon</h1>
    </div>
</header>

<style>
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-15px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    header {
        animation: fadeInDown 0.5s ease-out;
    }
    header:hover img {
        filter: drop-shadow(0 0 8px rgba(90,143,76,0.8));
        transform: scale(1.02);
    }
    header:hover h1 {
        text-shadow: 0 0 6px rgba(90,143,76,0.6);
    }
    @media (min-width: 768px) {
        .header-left-align {
            margin-left: 0 !important;
            padding-left: 2.5vw !important;
            max-width: none !important;
            width: auto !important;
            justify-content: flex-start !important;
        }
    }
</style>