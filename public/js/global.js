// jquery table javascript
window.initializeDataTable = function (selector, options) {
    const defaultOptions = {
        serverSide: true,
        processing: true,
        responsive: true,
        autoWidth: false,
        lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, "All"],
        ],
        dom:
            "<'row'<'col-sm-4 mb-2'B><'col-sm-2'l><'col-sm-6'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5 mb-3'i><'col-sm-7'p>>",
        buttons: [
            {
                extend: "csv",
                className: "btn-csv-custom",
                text: '<i class="fas fa-file-csv text-white"></i> CSV',
            },
            {
                extend: "pdf",
                className: "btn-pdf-custom",
                text: '<i class="fas fa-file-pdf text-white"></i> PDF',
            },
            {
                extend: "print",
                className: "btn-print-custom",
                text: '<i class="fas fa-print text-white"></i> Print',
            },
        ],
        drawCallback: function () {
            if (typeof $('[data-bs-toggle="tooltip"]').tooltip === 'function') {
                $('[data-bs-toggle="tooltip"]').tooltip("dispose");
                var tooltipTriggerList = [].slice.call(
                    document.querySelectorAll('[data-bs-toggle="tooltip"]')
                );
                tooltipTriggerList.map(function (el) {
                    return new bootstrap.Tooltip(el);
                });
            }

            if (typeof KTMenu !== 'undefined' && KTMenu.createInstances) {
                KTMenu.createInstances();
            }
        },
        createdRow: function (row) {
            $("td", row).addClass("table-td-custom");
            $(row).addClass("table-tr-hover");
        },
    };

    return $(selector).DataTable($.extend(true, defaultOptions, options));
};

// delete table records with confirmation
window.confirmDelete = function (url, tableId, message = "You won't be able to revert this!") {
    Swal.fire({
        title: "Are you sure?",
        text: message,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#f5a623",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                type: "DELETE",
                data: {
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                success: function (response) {
                    if (response.success || response.status) {
                        $(tableId).DataTable().ajax.reload(null, false);
                        if (typeof showToast === "function") {
                            showToast('success', response.message || "Deleted successfully!");
                        } else {
                            Swal.fire("Success!", response.message || "Deleted successfully!", "success");
                        }
                    }
                },
                error: function (xhr) {
                    if (typeof showToast === "function") {
                        showToast('error', "Something went wrong. Please try again.");
                    } else {
                        Swal.fire("Error!", "Something went wrong.", "error");
                    }
                },
            });
        }
    });
};

// Validation Error Handler
window.handleValidationErrors = function (form, errors) {
    form.find(".invalid-feedback").text("");
    form.find(".form-input-custom").removeClass("border-red-500");
    $.each(errors, function (field, messages) {
        let input = form.find(`[name="${field}"]`);
        input.addClass("border-red-500");
        input.siblings(".invalid-feedback").text(messages[0]);
    });
};

// SweetAlert Toast Notification
window.showToast = function (icon, message) {
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });
    Toast.fire({ icon: icon, title: message });
};

// ── Double-Submit Prevention ─────────────────────────────────────────────────
window.preventDoubleSubmit = function (form, event) {
    if (form._submitting) {
        if (event) event.preventDefault();
        return false;
    }
    form._submitting = true;

    const submitBtns = form.querySelectorAll('button[type="submit"], input[type="submit"]');
    submitBtns.forEach(function (btn) {
        btn.disabled = true;
        if (btn.tagName === 'BUTTON') {
            btn._originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Processing...';
        }
    });

    return true;
};

window.resetDoubleSubmitGuard = function (form) {
    form._submitting = false;
    const submitBtns = form.querySelectorAll('button[type="submit"], input[type="submit"]');
    submitBtns.forEach(function (btn) {
        btn.disabled = false;
        if (btn.tagName === 'BUTTON' && btn._originalHtml) {
            btn.innerHTML = btn._originalHtml;
            delete btn._originalHtml;
        }
    });
};

// ── AppHelpers Global Helper ──────────────────────────────────────────────────
window.AppHelpers = {};

// ── Global Searchable Dropdown Selector (Select2) ───────────────────────────
window.initSearchableSelects = function (context) {
    if (typeof $ === 'undefined' || !$.fn.select2) return;

    const $context = context ? $(context) : $(document);

    const $selects = $context.find('select.form-select, select.form-input-custom, select.select2, select.select2-init, select[data-control="select2"]').not('.no-select2, .swal2-select');

    $selects.each(function () {
        const $select = $(this);

        if ($select.hasClass('select2-hidden-accessible')) {
            return;
        }

        const $modal = $select.closest('.modal');
        const parentOption = $modal.length > 0 ? $modal : $(document.body);

        let placeholderText = $select.attr('placeholder') || $select.data('placeholder');
        if (!placeholderText) {
            const $firstOpt = $select.find('option[value=""]').first();
            placeholderText = $firstOpt.length ? $firstOpt.text() : 'Select an option...';
        }

        $select.select2({
            width: '100%',
            dropdownParent: parentOption,
            placeholder: placeholderText,
            allowClear: $select.find('option[value=""]').length > 0 && !$select.prop('required'),
            language: {
                noResults: function () {
                    return "No matching options found";
                }
            }
        });
    });
};

// Global event listener for Bootstrap Modals to initialize Select2
$(document).on('shown.bs.modal', '.modal', function () {
    window.initSearchableSelects(this);
});

// Auto-initialize on DOM ready
$(document).ready(function () {
    window.initSearchableSelects();
});

