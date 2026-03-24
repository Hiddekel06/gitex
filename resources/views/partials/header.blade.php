<header style="position: relative; background: transparent; border-bottom: none; padding: 0.75rem 0; transition: all 0.3s ease; z-index: 10;">
    <div class="d-flex align-items-center gap-3 header-left-align">
        <img src="/images/logoGov.png" alt="Logo" style="height: 74px; width: auto; display: block; filter: drop-shadow(0 0 4px rgba(90,143,76,0.4)); transition: filter 0.3s, transform 0.2s;">
    </div>

    <div class="header-neon-bar"></div>
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
    @keyframes neonBar {
        0% { background-position: 0% 50%; filter: brightness(1.1) drop-shadow(0 0 6px #ffe066); }
        50% { background-position: 100% 50%; filter: brightness(1.4) drop-shadow(0 0 16px #ffd600); }
        100% { background-position: 0% 50%; filter: brightness(1.1) drop-shadow(0 0 6px #ffe066); }
    }

</style>