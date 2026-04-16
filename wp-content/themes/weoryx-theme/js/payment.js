/**
 * WeOryx - Stripe Payment JS
 * Handles both USD and SAR payment pages
 */

(function () {
    'use strict';

    // Data passed from PHP via wp_localize_script
    const config = window.weoryxPayment || {};
    const {
        publishableKey,
        ajaxUrl,
        nonce,
        thankYouUrl,
        currency,
        currencySym,
        isRTL,
        locale
    } = config;

    if (!publishableKey) {
        console.warn('WeOryx: Stripe publishable key is not set.');
        return;
    }

    const stripe   = Stripe(publishableKey);
    // Elements no longer needed as we use Checkout redirect

    // ===== Order Summary =====
    const amountInput = document.getElementById('pay_amount');
    const descInput   = document.getElementById('pay_desc');
    const summary     = document.getElementById('orderSummary');

    function formatAmount(val) {
        if (isRTL) {
            return parseFloat(val).toFixed(2) + ' ' + currencySym;
        }
        return currencySym + parseFloat(val).toFixed(2);
    }

    function updateSummary() {
        if (!amountInput || !summary) return;
        const amt  = parseFloat(amountInput.value);
        const desc = (descInput && descInput.value.trim()) || (isRTL ? 'الخدمة' : 'Service');

        if (amt > 0) {
            summary.style.display = 'block';
            const summaryDesc  = document.getElementById('summaryDesc');
            const summaryAmt   = document.getElementById('summaryAmt');
            const summaryTotal = document.getElementById('summaryTotal');
            if (summaryDesc)  summaryDesc.textContent  = desc;
            if (summaryAmt)   summaryAmt.textContent   = formatAmount(amt);
            if (summaryTotal) summaryTotal.textContent = formatAmount(amt);
        } else {
            summary.style.display = 'none';
        }
    }

    if (amountInput) amountInput.addEventListener('input', updateSummary);
    if (descInput)   descInput.addEventListener('input', updateSummary);

    // ===== Form Submission =====
    const form   = document.getElementById('paymentForm');
    const payBtn = document.getElementById('payBtn');
    const msgEl  = document.getElementById('pay-message');

    if (!form || !payBtn) return;

    function showMessage(text, type) {
        if (!msgEl) return;
        msgEl.className = 'pay-message pay-message--' + type;
        msgEl.textContent = text;
        msgEl.style.display = 'block';
        msgEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function clearMessage() {
        if (!msgEl) return;
        msgEl.style.display = 'none';
        msgEl.className = 'pay-message';
    }

    function setLoading(loading) {
        payBtn.disabled = loading;
        payBtn.classList.toggle('btn-pay--loading', loading);
    }

    const ERRORS = {
        name:    isRTL ? 'يرجى إدخال الاسم الكامل.' : 'Please enter your full name.',
        email:   isRTL ? 'يرجى إدخال بريد إلكتروني صحيح.' : 'Please enter a valid email address.',
        amount:  isRTL ? 'يرجى إدخال مبلغ صحيح (الحد الأدنى 1).' : 'Please enter a valid amount (minimum 1).',
        desc:    isRTL ? 'يرجى إدخال وصف للطلب.' : 'Please enter a description.',
        phone:   isRTL ? 'يرجى إدخال رقم الهاتف.' : 'Please enter your phone number.',
        network: isRTL ? 'خطأ في الاتصال. يرجى المحاولة مرة أخرى.' : 'Network error. Please try again.',
        generic: isRTL ? 'حدث خطأ ما. يرجى المحاولة مرة أخرى.' : 'Something went wrong. Please try again.',
        redirect: isRTL ? 'جاري التحويل إلى صفحة الدفع الآمن...' : 'Redirecting to secure payment page...'
    };

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        clearMessage();

        const name   = document.getElementById('pay_name')?.value.trim()   || '';
        const email  = document.getElementById('pay_email')?.value.trim()  || '';
        const phone  = document.getElementById('pay_phone')?.value.trim()  || '';
        const amount = parseFloat(amountInput?.value || 0);
        const desc   = document.getElementById('pay_desc')?.value.trim()   || '';

        // Client-side validation
        if (!name)                          return showMessage(ERRORS.name, 'error');
        if (!email || !email.includes('@')) return showMessage(ERRORS.email, 'error');
        if (!phone)                         return showMessage(ERRORS.phone, 'error');
        if (!amount || amount < 1)          return showMessage(ERRORS.amount, 'error');
        if (!desc)                          return showMessage(ERRORS.desc, 'error');

        setLoading(true);

        try {
            // Step 1: Create Checkout Session via WP AJAX
            const body = new URLSearchParams({
                action:      'weoryx_create_payment_intent',
                nonce:       nonce,
                amount:      Math.round(amount * 100), 
                currency:    currency,
                name:        name,
                email:       email,
                phone:       phone,
                description: desc
            });

            const resp = await fetch(ajaxUrl, {
                method:  'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body:    body.toString()
            });

            const data = await resp.json();

            if (!data.success) {
                showMessage(data.data || ERRORS.generic, 'error');
                setLoading(false);
                return;
            }

            // Step 2: Redirect to Stripe Checkout URL
            if (data.data.url) {
                showMessage(ERRORS.redirect, 'success');
                window.location.href = data.data.url;
            } else {
                showMessage(ERRORS.generic, 'error');
                setLoading(false);
            }

        } catch (err) {
            console.error('WeOryx Payment Error:', err);
            showMessage(ERRORS.network, 'error');
            setLoading(false);
        }
    });

})();
