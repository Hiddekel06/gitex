@extends('layouts.app')

@section('content')
<style>
    .thankyou-wrap {
        max-width: 860px;
    }

    .thankyou-card {
        position: relative;
        overflow: hidden;
        border: 1px solid #2d2d2d;
        border-radius: 28px;
        background:
            radial-gradient(circle at 18% 22%, rgba(116, 184, 110, 0.2), transparent 38%),
            radial-gradient(circle at 82% 18%, rgba(96, 138, 209, 0.16), transparent 34%),
            linear-gradient(145deg, rgba(24, 24, 24, 0.95), rgba(33, 33, 33, 0.92));
        box-shadow: 0 28px 65px -35px rgba(0, 0, 0, 0.85);
    }

    .glow-dot {
        position: absolute;
        width: 220px;
        height: 220px;
        border-radius: 999px;
        filter: blur(28px);
        opacity: 0.22;
        pointer-events: none;
    }

    .glow-dot.one {
        top: -90px;
        right: -60px;
        background: #7abf71;
    }

    .glow-dot.two {
        bottom: -90px;
        left: -70px;
        background: #4f83c9;
    }

    .thankyou-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        border: 1px solid #4b6947;
        border-radius: 999px;
        padding: 0.45rem 0.85rem;
        color: #c7e8c1;
        background: rgba(58, 96, 53, 0.24);
        font-size: 0.82rem;
        letter-spacing: 0.2px;
    }

    .hero-image-wrap {
        position: relative;
        margin-bottom: 1.4rem;
        border-radius: 24px;
        padding: 10px;
        background: linear-gradient(130deg, rgba(122, 191, 113, 0.55), rgba(79, 131, 201, 0.45));
        box-shadow: 0 26px 48px -26px rgba(0, 0, 0, 0.9);
    }

    .hero-image-wrap::before {
        content: "";
        position: absolute;
        inset: -70px 12%;
        border-radius: 999px;
        background: radial-gradient(circle, rgba(122, 191, 113, 0.22) 0%, rgba(79, 131, 201, 0.07) 52%, transparent 72%);
        filter: blur(22px);
        z-index: -1;
    }

    .hero-image {
        display: block;
        width: 100%;
        max-width: 760px;
        margin: 0 auto;
        aspect-ratio: 16 / 7;
        object-fit: cover;
        border-radius: 18px;
        border: 1px solid rgba(255, 255, 255, 0.28);
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.14), 0 16px 38px -26px rgba(0, 0, 0, 0.95);
    }

    @media (max-width: 768px) {
        .hero-image {
            aspect-ratio: 16 / 9;
        }
    }

    .title {
        color: #effaef;
        font-weight: 700;
        letter-spacing: -0.4px;
        line-height: 1.15;
        font-size: clamp(1.6rem, 3.2vw, 2.35rem);
    }

    .subtitle {
        color: #b8cfb3;
        font-size: 1.02rem;
        line-height: 1.7;
        max-width: 690px;
    }

    .quote-box {
        border: 1px solid #3a3a3a;
        border-radius: 16px;
        padding: 1rem 1.1rem;
        background: rgba(38, 38, 38, 0.8);
        color: #d7e8d4;
        font-style: italic;
    }

    .signature {
        color: #9db59a;
        font-size: 0.9rem;
    }
</style>

<div class="container thankyou-wrap py-4 py-md-5">
    <div class="thankyou-card p-4 p-md-5">
        <span class="glow-dot one"></span>
        <span class="glow-dot two"></span>

        <div class="position-relative" style="z-index:1;">
            <div class="thankyou-badge mb-3">
                <span>Feedback reçu</span>
                <span style="opacity:.8;">-</span>
                <span>GITEX 2026</span>
            </div>

            <h2 class="title mb-3">Merci pour vos idées et ces beaux moments partagés.</h2>

            <p class="subtitle mb-4">
                Vos réponses ont bien été enregistrées. Ce retour d'expérience nous aide à mieux préparer la suite,
                valoriser les efforts des équipes et construire des accompagnements encore plus utiles.
            </p>

            <!-- <div class="quote-box mb-4">
                "Ce questionnaire clôture une étape, mais il ouvre surtout les prochaines opportunités."
            </div> -->

            <div class="signature">
            
            </div>
        </div>
    </div>
</div>
@endsection
