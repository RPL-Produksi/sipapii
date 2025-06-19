document.addEventListener("DOMContentLoaded", () => {
    const loadingButtons = document.querySelectorAll(
        'button[data-loading="true"]'
    );

    loadingButtons.forEach((btn) => {
        btn.addEventListener("click", function (e) {
            btn.disabled = true;

            btn.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    Loading...
                `;

            const form = btn.closest("form");
            if (form && btn.type === "submit") {
                form.submit();
            }
        });
    });
});
