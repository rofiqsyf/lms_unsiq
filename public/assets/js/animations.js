/**
 * GSAP Animations for LMS Focus & Flow Layout
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Dynamic Island Entry
    gsap.from(".main-content > div:first-child", {
        y: -40,
        opacity: 0,
        duration: 0.8,
        ease: "elastic.out(1, 0.5)",
        delay: 0.1
    });

    // 2. Animate Bento Box Grid Items
    const bentoItems = document.querySelectorAll('.bento-item');
    if (bentoItems.length > 0) {
        gsap.from(bentoItems, {
            y: 50,
            opacity: 0,
            duration: 0.8,
            stagger: 0.1,
            ease: "back.out(1.2)",
            delay: 0.2
        });
    }

    // 3. Floating Dock Entry (Handled by CSS to avoid conflicts)
    // gsap.from(".dock-container", { ... });

    // 4. Hover Effects via Vanilla JS for extra smoothness
    bentoItems.forEach(card => {
        card.addEventListener('mouseenter', () => {
            gsap.to(card, { y: -6, scale: 1.01, duration: 0.4, ease: "power2.out", boxShadow: "0 25px 50px rgba(0,0,0,0.1)" });
        });
        card.addEventListener('mouseleave', () => {
            gsap.to(card, { y: 0, scale: 1, duration: 0.5, ease: "power2.out", boxShadow: "0 10px 20px rgba(0,0,0,0.02)" });
        });
    });
});
