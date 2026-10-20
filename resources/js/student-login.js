let gsapPromise;

const prefersReducedMotion = () => window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const loadGsap = () => {
    if (prefersReducedMotion() || window.matchMedia('(max-width: 760px)').matches) {
        return Promise.resolve(null);
    }

    if (!gsapPromise) {
        gsapPromise = import('gsap')
            .then(({ gsap }) => {
                const artToggle = document.querySelector('.student-login-art-toggle');
                const slider = document.querySelector('.student-login-slider');

                if (!artToggle || !slider) {
                    return null;
                }

                const isActivationMode = document.body.classList.contains('student-login-activation');

                gsap.set(artToggle, { xPercent: isActivationMode ? -100 : 0 });
                gsap.set(slider, { xPercent: isActivationMode ? 100 : 0 });
                artToggle.classList.add('student-login-gsap-ready');
                slider.classList.add('student-login-gsap-ready');

                return gsap;
            })
            .catch((error) => {
                gsapPromise = null;
                console.error('Unable to load the login transition animation.', error);

                return null;
            });
    }

    return gsapPromise;
};

document.addEventListener('pointerover', (event) => {
    if (event.target instanceof Element && event.target.closest('.student-login-art-toggle, .student-login-link')) {
        void loadGsap();
    }
});

document.addEventListener('focusin', (event) => {
    if (event.target instanceof Element && event.target.closest('.student-login-art-toggle, .student-login-link')) {
        void loadGsap();
    }
});

document.addEventListener('click', (event) => {
    if (!(event.target instanceof Element)) {
        return;
    }

    const trigger = event.target.closest('.student-login-art-toggle, .student-login-link');

    if (!trigger || !gsapPromise) {
        return;
    }

    const artToggle = document.querySelector('.student-login-art-toggle');
    const slider = document.querySelector('.student-login-slider');

    if (!artToggle || !slider) {
        return;
    }

    const isActivationMode = document.body.classList.contains('student-login-activation');

    void gsapPromise.then((gsap) => {
        if (!gsap) {
            return;
        }

        if (prefersReducedMotion()) {
            gsap.set(artToggle, { xPercent: isActivationMode ? -100 : 0 });
            gsap.set(slider, { xPercent: isActivationMode ? 100 : 0 });

            return;
        }

        const timeline = gsap.timeline({ defaults: { duration: 0.85, ease: 'power3.inOut', overwrite: 'auto' } });

        timeline
            .fromTo(slider, {
                xPercent: isActivationMode ? 0 : 100,
            }, {
                xPercent: isActivationMode ? 100 : 0,
            }, 0)
            .fromTo(artToggle, {
                xPercent: isActivationMode ? 0 : -100,
            }, {
                xPercent: isActivationMode ? -100 : 0,
            }, 0);
    });
});
