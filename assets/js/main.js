/**
 * BMMC Website Main JavaScript
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Mariner / General citizen toggle
    const userTypeSelect = document.querySelectorAll('input[name="user_type"]');
    const marinerFields = document.getElementById('mariner-fields');
    const cdcInput = document.getElementById('cdc_sid_no');

    function toggleMarinerFields(val) {
        if (!marinerFields) return;
        if (val === 'mariner') {
            marinerFields.style.display = 'block';
            if (cdcInput) cdcInput.setAttribute('required', 'required');
        } else {
            marinerFields.style.display = 'none';
            if (cdcInput) cdcInput.removeAttribute('required');
        }
    }

    if (userTypeSelect.length > 0) {
        userTypeSelect.forEach(radio => {
            radio.addEventListener('change', (e) => {
                toggleMarinerFields(e.target.value);
            });
            if (radio.checked) {
                toggleMarinerFields(radio.value);
            }
        });
    }

    // 2. Animated Counters
    const counters = document.querySelectorAll('.stat-counter[data-target]');
    if (counters.length > 0) {
        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    const target = +counter.getAttribute('data-target');
                    let count = 0;
                    const speed = Math.max(1, Math.floor(target / 40));

                    const updateCount = () => {
                        count += speed;
                        if (count < target) {
                            counter.innerText = toBanglaNumber(count);
                            setTimeout(updateCount, 30);
                        } else {
                            counter.innerText = toBanglaNumber(target);
                        }
                    };
                    updateCount();
                    obs.unobserve(counter);
                }
            });
        }, { threshold: 0.5 });

        counters.forEach(c => observer.observe(c));
    }

    // Bangla Number Converter in JS
    function toBanglaNumber(n) {
        const bn = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
        return n.toString().split('').map(d => bn[d] !== undefined ? bn[d] : d).join('');
    }

    // 3. Auto dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            if (bsAlert) bsAlert.close();
        }, 6000);
    });
});
