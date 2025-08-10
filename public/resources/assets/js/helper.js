function checkRadio(name, value) {
    if (!value && value !== 0) return; // skip if null, undefined, empty, or 0

    const safeValue = String(value).trim().toLowerCase();

    const radio = document.querySelector(`input[name="${name}"][value="${value}"]`) ||
        document.querySelector(`input[name="${name}"][id="${safeValue}"]`);

    if (radio) radio.checked = true;
}

function checkMultipleCheckboxes(name, valuesArray) {
    if (!Array.isArray(valuesArray)) valuesArray = valuesArray.split(',');
    document.querySelectorAll(`input[name="${name}"]`).forEach(cb => {
        const checkboxValue = cb.value.trim();
        const match = valuesArray.some(v => v.trim() === checkboxValue);
        cb.checked = match;
    });

}

function checkCheckbox(name, value) {
    if (!value) return;
    const checkbox = document.querySelector(`input[name="${name}"][value="${value}"]`) ||
        document.querySelector(`input[name="${name}"][id="${value.toLowerCase()}"]`);
    if (checkbox) checkbox.checked = true;
}

function fillFormFields(data) {
    for (const [name, value] of Object.entries(data)) {
        const el = document.querySelector(`[name="${name}"]`);

        if (!el) continue;

        // Skip radio and checkbox - those need custom logic
        if (el.type === 'radio' || el.type === 'checkbox') continue;

        el.value = value ?? ''; // fallback to empty string if null/undefined
    }
}

function fillViewPageFields(data) {
    for (const [name, value] of Object.entries(data)) {
        const el = document.querySelector(`[id="${name}"]`);

        if (!el) continue;

        // Skip radio and checkbox - those need custom logic
        if (el.type === 'radio' || el.type === 'checkbox') continue;

        if (el.type === 'text' || el.type === 'email' || el.type === 'date' || el.type === 'number') {
            el.value = value ?? '';
        }

        if (el.type === undefined) {

            el.innerHTML = value ?? ''; // fallback to empty string if null/undefined
        }
    }
}

function getBadgeClass(type) {
    const lower = type.toLowerCase();

    if (lower.includes('maintenance')) return 'bg-primary';
    if (lower.includes('fire') || lower.includes('hazard')) return 'bg-warning text-dark';
    if (lower.includes('harassment') || lower.includes('assault') || lower.includes('damage to tpa')) return 'bg-danger';
    if (lower.includes('loitering') || lower.includes('sleeping') || lower.includes('impaired')) return 'bg-info text-dark';

    return 'bg-secondary'; // fallback
}
