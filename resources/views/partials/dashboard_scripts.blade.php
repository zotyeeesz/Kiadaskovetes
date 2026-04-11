<script>
    const calendarState = {
        visibleMonth: null,
    };

    const deleteState = {
        id: null,
    };

    function parseDateString(value) {
        if (!value || !/^\d{4}-\d{2}-\d{2}$/.test(value)) {
            return null;
        }

        const [year, month, day] = value.split('-').map(Number);
        return new Date(year, month - 1, day);
    }

    function formatDateForInput(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`;
    }

    function formatDateForDisplay(value) {
        const date = parseDateString(value);
        if (!date) {
            return 'Válassz dátumot';
        }

        return new Intl.DateTimeFormat('hu-HU', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        }).format(date);
    }

    function getDateInput() {
        return document.getElementById('rogzites_input');
    }

    @php
        $koltsegKategoriakJs = $koltsegKategoriak->map(function ($kat) {
            return [
                'id' => $kat->id,
                'nev' => $kat->nev,
                'owned' => (bool) $kat->felhasznaloid,
            ];
        })->values()->all();

        $bevetelKategoriakJs = $bevetelKategoriak->map(function ($kat) {
            return [
                'id' => $kat->id,
                'nev' => $kat->nev,
                'owned' => (bool) $kat->felhasznaloid,
            ];
        })->values()->all();
    @endphp

    const categoryOptions = {
        koltseg: @json($koltsegKategoriakJs),
        bevetel: @json($bevetelKategoriakJs),
    };

    let categorySavePromise = null;

    function getActiveKategoriak() {
        const tipusInput = document.getElementById('tipus_input');
        const tipus = tipusInput && tipusInput.value === 'bevetel' ? 'bevetel' : 'koltseg';
        return categoryOptions[tipus] || [];
    }

    function getActiveTipus() {
        const tipusInput = document.getElementById('tipus_input');
        return tipusInput && tipusInput.value === 'bevetel' ? 'bevetel' : 'koltseg';
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function setCategoryMessage(message = '', type = '') {
        const messageBox = document.getElementById('kategoria_message');
        if (!messageBox) {
            return;
        }

        messageBox.textContent = message;
        messageBox.className = `field-inline-message${type ? ` ${type}` : ''}`;
    }

    function renderKategoriak(filterText = '', forceShow = false) {
        const list = document.getElementById('kategoria_list');
        if (!list) {
            return;
        }

        const normalizedFilter = filterText.toLowerCase();
        const matches = getActiveKategoriak().filter((item) => item.nev.toLowerCase().includes(normalizedFilter));

        list.innerHTML = matches
            .map((item) => {
                const escapedName = String(item.nev).replace(/\\/g, '\\\\').replace(/'/g, "\\'");
                const deleteButton = item.owned
                    ? `<button type="button" class="kategoria-item-delete" onclick="deleteCustomCategory(event, ${item.id}, '${escapedName}')">Törlés</button>`
                    : '';

                return `<div class="kategoria-item" onclick="selectKategoria('${escapedName}')"><span class="kategoria-item-label">${escapeHtml(item.nev)}</span>${deleteButton}</div>`;
            })
            .join('');

        list.classList.toggle('show', matches.length > 0 && (forceShow || normalizedFilter.length > 0));
    }

    function setTipus(value) {
        ensureCategorySaved({ showMessage: false });

        const input = document.getElementById('tipus_input');
        const options = document.querySelectorAll('.type-toggle-option');
        const normalizedValue = value === 'bevetel' ? 'bevetel' : 'koltseg';

        if (input) {
            input.value = normalizedValue;
        }

        options.forEach((option) => {
            const isActive = option.dataset.value === normalizedValue;
            option.classList.toggle('active', isActive);
            option.setAttribute('aria-pressed', String(isActive));
        });

        const categoryInput = document.getElementById('kategoria_input');
        setCategoryMessage('');
        renderKategoriak(categoryInput ? categoryInput.value : '');
    }

    function categoryExists(name, tipus = getActiveTipus()) {
        const normalizedName = String(name || '').trim().toLowerCase();
        return (categoryOptions[tipus] || []).some((item) => item.nev.trim().toLowerCase() === normalizedName);
    }

    async function ensureCategorySaved(options = {}) {
        const categoryInput = document.getElementById('kategoria_input');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const tipus = getActiveTipus();
        const categoryName = categoryInput ? categoryInput.value.trim() : '';
        const showMessage = options.showMessage !== false;

        if (!categoryName || categoryExists(categoryName, tipus)) {
            return true;
        }

        if (!csrfToken) {
            if (showMessage) {
                setCategoryMessage('A mentés most nem elérhető.', 'error');
            }
            return false;
        }

        if (categorySavePromise) {
            return categorySavePromise;
        }

        categorySavePromise = (async () => {
            try {
                const response = await fetch('/kategoria/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        kategoria_nev: categoryName,
                        tipus,
                    }),
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'A kategória mentése nem sikerült.');
                }

                if (!categoryExists(data.kategoria_nev, tipus)) {
                    categoryOptions[tipus] = [
                        ...(categoryOptions[tipus] || []),
                        {
                            id: data.kategoriaid,
                            nev: data.kategoria_nev,
                            owned: Boolean(data.owned),
                        },
                    ].sort((a, b) => a.nev.localeCompare(b.nev, 'hu', { sensitivity: 'base' }));
                }

                if (categoryInput) {
                    categoryInput.value = data.kategoria_nev;
                }

                renderKategoriak(data.kategoria_nev, true);
                if (showMessage) {
                    setCategoryMessage('Saját kategória elmentve.', 'success');
                }

                return true;
            } catch (error) {
                if (showMessage) {
                    setCategoryMessage(error.message || 'A kategória mentése nem sikerült.', 'error');
                }
                return false;
            } finally {
                categorySavePromise = null;
            }
        })();

        return categorySavePromise;
    }

    function setDateValue(value) {
        const input = getDateInput();
        const display = document.getElementById('datePickerValue');

        if (!input || !display) {
            return;
        }

        input.value = value || '';
        display.textContent = formatDateForDisplay(input.value);

        const parsed = parseDateString(input.value);
        calendarState.visibleMonth = parsed
            ? new Date(parsed.getFullYear(), parsed.getMonth(), 1)
            : new Date(new Date().getFullYear(), new Date().getMonth(), 1);

        renderDatePicker();
    }

    function toggleDatePicker() {
        const wrapper = document.getElementById('date_picker');
        const trigger = document.getElementById('datePickerTrigger');
        const popover = document.getElementById('datePickerPopover');

        if (!wrapper || !trigger || !popover) {
            return;
        }

        const shouldOpen = !wrapper.classList.contains('open');
        wrapper.classList.toggle('open', shouldOpen);
        trigger.setAttribute('aria-expanded', String(shouldOpen));
        popover.setAttribute('aria-hidden', String(!shouldOpen));

        if (shouldOpen) {
            renderDatePicker();
            updateDatePickerPosition();
        }
    }

    function closeDatePicker() {
        const wrapper = document.getElementById('date_picker');
        const trigger = document.getElementById('datePickerTrigger');
        const popover = document.getElementById('datePickerPopover');

        if (wrapper) {
            wrapper.classList.remove('open');
            wrapper.classList.remove('open-upward');
        }
        if (trigger) {
            trigger.setAttribute('aria-expanded', 'false');
        }
        if (popover) {
            popover.setAttribute('aria-hidden', 'true');
            popover.style.maxHeight = '';
        }
    }

    function updateDatePickerPosition() {
        const wrapper = document.getElementById('date_picker');
        const trigger = document.getElementById('datePickerTrigger');
        const popover = document.getElementById('datePickerPopover');

        if (!wrapper || !trigger || !popover || !wrapper.classList.contains('open')) {
            return;
        }

        wrapper.classList.remove('open-upward');
        popover.style.maxHeight = '';

        const triggerRect = trigger.getBoundingClientRect();
        const popoverRect = popover.getBoundingClientRect();
        const viewportPadding = 16;
        const gap = 10;
        const spaceBelow = window.innerHeight - triggerRect.bottom - viewportPadding;
        const spaceAbove = triggerRect.top - viewportPadding;
        const shouldOpenUpward = popoverRect.height > spaceBelow && spaceAbove > spaceBelow;
        const availableSpace = shouldOpenUpward ? spaceAbove - gap : spaceBelow - gap;

        wrapper.classList.toggle('open-upward', shouldOpenUpward);
        popover.style.maxHeight = `${Math.max(160, Math.floor(availableSpace))}px`;
    }

    function changeCalendarMonth(offset) {
        if (!calendarState.visibleMonth) {
            const today = new Date();
            calendarState.visibleMonth = new Date(today.getFullYear(), today.getMonth(), 1);
        }

        calendarState.visibleMonth = new Date(
            calendarState.visibleMonth.getFullYear(),
            calendarState.visibleMonth.getMonth() + offset,
            1
        );

        renderDatePicker();
    }

    function selectDate(value) {
        setDateValue(value);
        closeDatePicker();
    }

    function jumpToToday() {
        setDateValue(formatDateForInput(new Date()));
        closeDatePicker();
    }

    function renderDatePicker() {
        const month = document.getElementById('datePickerMonth');
        const grid = document.getElementById('datePickerGrid');
        const input = getDateInput();

        if (!month || !grid) {
            return;
        }

        if (!calendarState.visibleMonth) {
            const parsed = parseDateString(input?.value || '');
            const source = parsed || new Date();
            calendarState.visibleMonth = new Date(source.getFullYear(), source.getMonth(), 1);
        }

        const visible = calendarState.visibleMonth;
        month.textContent = new Intl.DateTimeFormat('hu-HU', {
            year: 'numeric',
            month: 'long',
        }).format(visible);

        const firstDay = new Date(visible.getFullYear(), visible.getMonth(), 1);
        const gridStart = new Date(firstDay);
        const startOffset = (firstDay.getDay() + 6) % 7;
        gridStart.setDate(firstDay.getDate() - startOffset);

        const selectedValue = input?.value || '';
        const todayValue = formatDateForInput(new Date());
        const activeMonth = visible.getMonth();

        let markup = '';

        for (let index = 0; index < 42; index += 1) {
            const current = new Date(gridStart);
            current.setDate(gridStart.getDate() + index);

            const value = formatDateForInput(current);
            const classes = ['date-picker-day'];

            if (current.getMonth() !== activeMonth) {
                classes.push('is-outside');
            }
            if (value === todayValue) {
                classes.push('is-today');
            }
            if (value === selectedValue) {
                classes.push('is-selected');
            }

            markup += `<button type="button" class="${classes.join(' ')}" onclick="selectDate('${value}')" aria-label="${formatDateForDisplay(value)}">${current.getDate()}</button>`;
        }

        grid.innerHTML = markup;

        if (document.getElementById('date_picker')?.classList.contains('open')) {
            updateDatePickerPosition();
        }
    }

    function openModal(tranzakcioId = null) {
        const modal = document.getElementById('koltsegModal');
        const form = document.getElementById('koltsegForm');
        const title = document.getElementById('modalTitle');
        const submitBtn = document.querySelector('#koltsegForm button[type="submit"]');

        modal.classList.add('show');

        if (tranzakcioId) {
            title.textContent = 'Tranzakció szerkesztése';
            form.action = `/koltseg/edit/${tranzakcioId}`;
            submitBtn.textContent = 'Tranzakció mentése';

            let methodField = document.getElementById('methodField');
            if (!methodField) {
                methodField = document.createElement('input');
                methodField.id = 'methodField';
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PUT';
                form.appendChild(methodField);
            } else {
                methodField.value = 'PUT';
            }
        } else {
            title.textContent = 'Új tranzakció hozzáadása';
            form.action = '/koltseg/add';
            submitBtn.textContent = 'Tranzakció hozzáadása';

            const methodField = document.getElementById('methodField');
            if (methodField) {
                methodField.remove();
            }

            document.getElementById('kategoria_input').value = '';
            document.getElementById('penznem_input').value = '';
            setTipus('koltseg');
            document.getElementById('koltsegForm').querySelector('input[name="osszeg"]').value = '';
            setDateValue('{{ old('rogzites', now()->toDateString()) }}');
            document.getElementById('koltsegForm').querySelector('textarea[name="megjegyzes"]').value = '';
        }
    }

    function editTranzakcio(tranzakcioId, rogzites, kategoria, osszeg, penznem, tipus, megjegyzes) {
        openModal(tranzakcioId);

        document.getElementById('kategoria_input').value = kategoria;
        document.getElementById('kategoria_list').classList.remove('show');
        document.getElementById('penznem_input').value = penznem;
        document.getElementById('penznem_list').classList.remove('show');
        setTipus(tipus || 'koltseg');
        document.getElementById('koltsegForm').querySelector('input[name="osszeg"]').value = osszeg.replace(/\s/g, '').replace(',', '.');
        setDateValue(rogzites);
        document.getElementById('koltsegForm').querySelector('textarea[name="megjegyzes"]').value = megjegyzes || '';
    }

    function closeModal() {
        document.getElementById('koltsegModal').classList.remove('show');
        closeDatePicker();
    }

    function openDeleteConfirm(tranzakcioId, categoria, osszeg) {
        const modal = document.getElementById('deleteConfirmModal');
        const category = document.getElementById('deleteConfirmCategory');
        const amount = document.getElementById('deleteConfirmAmount');

        deleteState.id = tranzakcioId;

        if (category) {
            category.textContent = categoria || '-';
        }
        if (amount) {
            amount.textContent = osszeg || '-';
        }
        if (modal) {
            modal.classList.add('show');
        }
    }

    function closeDeleteConfirm() {
        const modal = document.getElementById('deleteConfirmModal');
        deleteState.id = null;

        if (modal) {
            modal.classList.remove('show');
        }
    }

    function confirmDeleteTranzakcio() {
        if (!deleteState.id) {
            closeDeleteConfirm();
            return;
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/koltseg/delete/${deleteState.id}`;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrfToken) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = '_token';
            input.value = csrfToken;
            form.appendChild(input);
        }

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    }

    function filterKategoriak(forceShow = false) {
        const input = document.getElementById('kategoria_input').value;
        renderKategoriak(input, forceShow);
        setCategoryMessage('');
    }

    function selectKategoria(nev) {
        document.getElementById('kategoria_input').value = nev;
        document.getElementById('kategoria_list').classList.remove('show');
        setCategoryMessage('');
    }

    async function deleteCustomCategory(event, id, nev) {
        event.stopPropagation();

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const tipus = getActiveTipus();

        if (!csrfToken) {
            setCategoryMessage('A törlés most nem elérhető.', 'error');
            return;
        }

        try {
            const response = await fetch(`/kategoria/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'A kategória törlése nem sikerült.');
            }

            categoryOptions[tipus] = (categoryOptions[tipus] || []).filter((item) => item.id !== id);

            const categoryInput = document.getElementById('kategoria_input');
            if (categoryInput && categoryInput.value.trim().toLowerCase() === String(nev).trim().toLowerCase()) {
                categoryInput.value = '';
            }

            renderKategoriak(categoryInput ? categoryInput.value : '', true);
            setCategoryMessage('Saját kategória törölve.', 'success');
        } catch (error) {
            setCategoryMessage(error.message || 'A kategória törlése nem sikerült.', 'error');
        }
    }

    function filterPenznemek() {
        const input = document.getElementById('penznem_input').value.toUpperCase();
        const list = document.getElementById('penznem_list');
        const items = list.querySelectorAll('.penznem-item');

        if (input.length > 0) {
            list.classList.add('show');
        } else {
            list.classList.remove('show');
        }

        items.forEach((item) => {
            const text = item.textContent.toUpperCase();
            item.style.display = text.includes(input) ? 'flex' : 'none';
        });
    }

    function selectPenznem(nev) {
        document.getElementById('penznem_input').value = nev;
        document.getElementById('penznem_list').classList.remove('show');
    }

    function deleteTranzakcio(tranzakcioId, categoria, osszeg) {
        openDeleteConfirm(tranzakcioId, categoria, osszeg);
    }

    @if($errors->any())
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('koltsegModal').classList.add('show');
    });
    @endif

    document.addEventListener('DOMContentLoaded', function () {
        setTipus('{{ old('tipus', 'koltseg') }}');
        setDateValue('{{ old('rogzites', now()->toDateString()) }}');
        renderKategoriak(@json(old('kategoria', '')));

        const form = document.getElementById('koltsegForm');
        if (form) {
            form.addEventListener('submit', async function (event) {
                const saved = await ensureCategorySaved();

                if (!saved) {
                    event.preventDefault();
                }
            });
        }
    });

    document.addEventListener('click', function (event) {
        const categoryWrapper = document.getElementById('kategoria_wrapper');
        const categoryList = document.getElementById('kategoria_list');

        if (categoryWrapper && !categoryWrapper.contains(event.target)) {
            categoryList.classList.remove('show');
        }

        const currencyWrapper = document.getElementById('penznem_wrapper');
        const currencyList = document.getElementById('penznem_list');

        if (currencyWrapper && !currencyWrapper.contains(event.target)) {
            currencyList.classList.remove('show');
        }

        const datePicker = document.getElementById('date_picker');
        if (datePicker && !datePicker.contains(event.target)) {
            closeDatePicker();
        }
    });

    window.onclick = function (event) {
        const modal = document.getElementById('koltsegModal');
        const deleteModal = document.getElementById('deleteConfirmModal');

        if (event.target === modal) {
            closeModal();
        }
        if (event.target === deleteModal) {
            closeDeleteConfirm();
        }
    };

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeDatePicker();
            closeDeleteConfirm();
        }
    });

    window.addEventListener('resize', function () {
        updateDatePickerPosition();
    });
</script>
