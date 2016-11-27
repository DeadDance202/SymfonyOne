/**
 * Created by Misha Savitckiy on 26.11.16.
 */
$.fn.ajaxgrid = function(options) {
    var root = this;
    var table, tbody;
    var headerRow;
    var filters, pagination;
    var request = {
        rows: options.rowsPerPage
    };

    completeOptions(options);
    createBaseElements();
    sendInitRequest();

    return root;

    function completeOptions(options) {
        if (!options.dataUrl) {
            throw 'Wrong "dataUrl" exception';
        }
        options.sortableColumns = options.sortableColumns || [];
        options.filterableColumns = options.filterableColumns || [];
        options.rowsPerPage = options.rowsPerPage || 5;
    }
    function createBaseElements() {
        filters = createElement('div', root).addClass('filters');
        table = createElement('table', root);
        headerRow = createElement('tr', table);
        tbody = createElement('tbody', table);
        pagination = createElement('div', root).addClass('pagination');
    }
    function sendInitRequest() {
        $.getJSON(options.dataUrl, request, function(json) {
            var columns = Object.getOwnPropertyNames(json.data[0]);
            setHeader(headerRow, columns, options.sortableColumns);
            setData(tbody, json.data);
            if (Math.ceil(json.rows / options.rowsPerPage) > 1) {
                setPagination(pagination, Math.ceil(json.rows / options.rowsPerPage));
            }
            if (options.filterableColumns === []) {
                setFilters(filters, options.filterableColumns);
            }
        });
    }

    function setHeader(headerRow, columns, sortable) {
        headerRow.empty();
        setHeaderText(headerRow, columns);
        setHeaderSort(headerRow, sortable);
    }
    function setHeaderText(headerRow, columns) {
        for (var i = 0; i < columns.length; i++) {
            createElement('th', headerRow).text(columns[i]);
        }
    }
    function setHeaderSort(headerRow, sortable) {
        sortable.forEach(function (column) {
            headerRow.find(':contains(' + column +')')
                .addClass('sortable')
                .click(function(){
                    onSortClick(column);
                });
        });
    }

    function setData(tbody, data) {
        tbody.empty();
        for (var i = 0; i <data.length; i++) {
            var row = createElement('tr', tbody);
            setRow(row, data[i]);
        }
    }
    function setRow(row, data) {
        for (var dataCell in data) {
            if (data.hasOwnProperty(dataCell)) {
                createElement('td', row).text(data[dataCell]);
            }
        }
    }

    function setPagination(pagination, pages) {
        pagination.empty();
        var page = request.page ? request.page : 0;
        for (var i = 0; i < pages; i++) {
            var button = createElement('button', pagination)
                .text(i + 1)
                .click(onPageClick.bind(i + 1));
            if (i + 1 === +page) {
                button.addClass('selected');
            }
        }
    }
    function setFilters(filters, filterable) {
        filters.empty();
        var list = createElement('select', filters);
        createElement('input', filters).attr('type', 'text');
        createElement('button', filters).text('Filter').click(onFilterClick);
        filterable.forEach(function (column) {
            createElement('option', list).text(column).attr('value', column);
        });
    }

    function createElement(element, root) {
        return $('<' + element + '>').appendTo(root);
    }

    function onFilterClick() {
        var pattern = $('.filters input').val();
        if (pattern !== '') {
            request.pattern = pattern;
            request.filterbyfield = $('.filters select').val();
            request.page = 1;
        } else {
            delete request.pattern;
            delete request.filterbyfield;
        }
        $.getJSON(options.dataUrl, request, function (json) {
            setData(tbody, json.data);
            if (Math.ceil(json.rows / options.rowsPerPage) > 1) {
                setPagination(pagination,
                    Math.ceil(json.rows / options.rowsPerPage));
            }
        });
    }
    function onPageClick() {
        if (request.page !== this) {
            request.page = this;
            $.getJSON(options.dataUrl, request, function (json) {
                setData(tbody, json.data);
                setPagination(pagination,
                    Math.ceil(json.rows / options.rowsPerPage));
            });
        }
    }
    function onSortClick(column) {
        if (column === request.sortbyfield) {
            if (request.order === 'asc') {
                root.find('.sortable:contains(' + column + ')')
                    .addClass('desc')
                    .removeClass('asc');
                request.order = 'desc';
            } else {
                root.find('.sortable:contains(' + column + ')')
                    .removeClass('desc');
                delete request.order;
                delete request.sortbyfield;
            }
        } else {
            root.find('.sortable:contains(' + column + ')')
                .addClass('asc');
            root.find('.sortable:contains(' + request.sortbyfield + ')')
                .removeClass('asc')
                .removeClass('desc');
            request.sortbyfield = column;
            request.order = 'asc';
        }
        $.getJSON(options.dataUrl, request, function (json) {
            setData(tbody, json.data);
            if (Math.ceil(json.rows / options.rowsPerPage) > 1) {
                setPagination(pagination,
                    Math.ceil(json.rows / options.rowsPerPage));
            }
        });
    }
};