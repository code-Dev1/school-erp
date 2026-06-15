import './bootstrap';

window.uiCombobox = function ({ options = [], selected = null, multiple = false, allowTags = false } = {}) {
    return {
        open: false,
        query: '',
        options,
        selected: multiple ? (Array.isArray(selected) ? selected : []) : selected,
        activeIndex: 0,
        get filtered() {
            const query = this.query.toLowerCase().trim();

            if (! query) {
                return this.options;
            }

            return this.options.filter((option) => String(option.label).toLowerCase().includes(query));
        },
        isSelected(value) {
            return multiple ? this.selected.includes(value) : this.selected === value;
        },
        labelFor(value) {
            return this.options.find((option) => option.value === value)?.label ?? value;
        },
        choose(option) {
            if (multiple) {
                this.selected = this.isSelected(option.value)
                    ? this.selected.filter((value) => value !== option.value)
                    : [...this.selected, option.value];
                this.query = '';
                this.dispatchValue();

                return;
            }

            this.selected = option.value;
            this.query = option.label;
            this.open = false;
            this.dispatchValue();
        },
        addTag() {
            const tag = this.query.trim();

            if (! allowTags || ! tag) {
                return;
            }

            if (! this.options.some((option) => option.value === tag)) {
                this.options.push({ value: tag, label: tag });
            }

            this.choose({ value: tag, label: tag });
        },
        remove(value) {
            this.selected = this.selected.filter((item) => item !== value);
            this.dispatchValue();
        },
        dispatchValue() {
            this.$nextTick(() => {
                this.$refs.input?.dispatchEvent(new Event('input', { bubbles: true }));
                this.$dispatch('ui-select-change', { value: this.selected });
            });
        },
    };
};

window.uiAsyncSelect = function ({ endpoint = '', minChars = 2, selected = null } = {}) {
    return {
        open: false,
        loading: false,
        query: '',
        options: [],
        selected,
        async search() {
            if (this.query.length < minChars || ! endpoint) {
                this.options = [];
                return;
            }

            this.loading = true;

            try {
                const response = await fetch(`${endpoint}?q=${encodeURIComponent(this.query)}`, {
                    headers: { Accept: 'application/json' },
                });
                this.options = await response.json();
                this.open = true;
            } finally {
                this.loading = false;
            }
        },
        choose(option) {
            this.selected = option.value;
            this.query = option.label;
            this.open = false;
            this.$nextTick(() => this.$refs.input?.dispatchEvent(new Event('input', { bubbles: true })));
        },
    };
};

window.uiDataTable = function ({ rows = [], columns = [], rowKey = 'id' } = {}) {
    return {
        rows,
        columns,
        rowKey,
        search: '',
        sortKey: columns[0]?.key ?? null,
        sortDirection: 'asc',
        selected: [],
        filters: {},
        get filteredRows() {
            const search = this.search.toLowerCase().trim();

            return this.rows.filter((row) => {
                const matchesSearch = ! search || Object.values(row).some((value) => String(value ?? '').toLowerCase().includes(search));
                const matchesFilters = Object.entries(this.filters).every(([key, value]) => ! value || String(row[key] ?? '') === String(value));

                return matchesSearch && matchesFilters;
            });
        },
        get visibleRows() {
            return [...this.filteredRows].sort((a, b) => {
                if (! this.sortKey) {
                    return 0;
                }

                const left = a[this.sortKey] ?? '';
                const right = b[this.sortKey] ?? '';
                const result = String(left).localeCompare(String(right), undefined, { numeric: true, sensitivity: 'base' });

                return this.sortDirection === 'asc' ? result : -result;
            });
        },
        sort(column) {
            if (! column.sortable) {
                return;
            }

            if (this.sortKey === column.key) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                return;
            }

            this.sortKey = column.key;
            this.sortDirection = 'asc';
        },
        toggleAll(checked) {
            this.selected = checked ? this.visibleRows.map((row) => row[this.rowKey]) : [];
        },
        exportCsv(filename = 'table-export.csv') {
            const header = this.columns.map((column) => `"${column.label}"`).join(',');
            const body = this.visibleRows.map((row) => this.columns.map((column) => `"${String(row[column.key] ?? '').replaceAll('"', '""')}"`).join(',')).join('\n');
            const blob = new Blob([[header, body].filter(Boolean).join('\n')], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');

            link.href = URL.createObjectURL(blob);
            link.download = filename;
            link.click();
            URL.revokeObjectURL(link.href);
        },
    };
};

window.uiFileUpload = function ({ multiple = false, accept = '' } = {}) {
    return {
        multiple,
        accept,
        files: [],
        progress: 0,
        dragging: false,
        handle(files) {
            this.files = Array.from(files ?? []);
            this.progress = this.files.length ? 100 : 0;
            this.$nextTick(() => this.$refs.input?.dispatchEvent(new Event('change', { bubbles: true })));
        },
        preview(file) {
            return file.type?.startsWith('image/') ? URL.createObjectURL(file) : null;
        },
        clear() {
            this.files = [];
            this.progress = 0;
            if (this.$refs.input) {
                this.$refs.input.value = null;
            }
        },
    };
};

window.uiRichText = function ({ value = '' } = {}) {
    return {
        value,
        sync() {
            this.value = this.$refs.editor.innerHTML;
            this.$refs.input.value = this.value;
            this.$refs.input.dispatchEvent(new Event('input', { bubbles: true }));
        },
        command(name, value = null) {
            document.execCommand(name, false, value);
            this.sync();
            this.$refs.editor.focus();
        },
    };
};
