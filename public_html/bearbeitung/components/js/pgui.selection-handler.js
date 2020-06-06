define([
    'class',
    'pgui.localizer',
    'pgui.utils',
    'libs/sprintf',
    'pgui.form_collection',
    'pgui.modal_operation_link',
    'underscore',
    'jquery.query',
    'bootbox',
    'jquery.popover'
], function (Class, localizer, utils, sprintf, FormCollection, modalLink) {

    function buildForm(url, data) {
        var $form = $('<form>').hide()
            .attr('method', 'POST')
            .attr('action', url);

        _.each(data, function (value, name) {
            $form.append($('<input name="' + name + '" value="' + value + '">'));
        });

        return $form.appendTo($('body'));
    }

    return Class.extend({
        init: function (selection, $container, $selectionHeader, $checkboxes, hideContainer, grid) {
            this.hideContainer = hideContainer;
            this.$container = $container || $('.js-selection-actions-container').first();
            this.$actions = $container.find('.js-action');
            this.$count = $container.find('.js-count');
            this.selection = selection;
            this.grid = grid;
            if ($selectionHeader !== undefined) {
                var $allCheckbox = $selectionHeader.find('input[type=checkbox]');
            }
            this.setCheckboxes($allCheckbox || $(), $checkboxes || $());

            this.$actions.on('click', this._handleAction.bind(this));
            this.selection.bind('change', this._handleChange.bind(this));
            this._handleChange(this.selection.getData());

            this._initSelectionFilters($selectionHeader);
        },

        setCheckboxes: function ($allCheckbox, $checkboxes) {
            this.$allCheckbox = $allCheckbox || $();
            this.$checkboxes = $checkboxes || $();

            this.checkboxesData = _.map(this.$checkboxes, function (v, k) {
                return $(v).data('value');
            });

            this.$checkboxes.off('change').on('change', this._checkboxChangeHandler.bind(this));
            this.$allCheckbox.off('change').on('change', this._allCheckboxChangeHandler.bind(this));
        },

        _checkboxChangeHandler: function (e) {
            var $el = $(e.currentTarget);
            this.selection.toggle($el.data('value'), $el.prop('checked'));
        },

        _allCheckboxChangeHandler: function (e) {
            var checked = $(e.currentTarget).prop('checked')
            this.$checkboxes.prop('checked', checked);

            _.each(this.checkboxesData, function (value) {
                if (checked) {
                    this.selection.add(value);
                } else {
                    this.selection.remove(value);
                }
            }.bind(this));
        },

        _handleChange: function (selectionData) {
            var count = selectionData.length;

            if (this.hideContainer) {
                this.$container.toggle(count > 0);
                _.defer(this.$container.toggleClass.bind(this.$container), 'in', count > 0);
            }

            this.$count.text(count);
            this.$checkboxes.prop('checked', false);

            _.each(selectionData, function (keys) {
                this.$checkboxes
                    .filter("[data-value='" + JSON.stringify(keys) + "']")
                    .prop('checked', true);
            }.bind(this));

            var checkedCount = this.$checkboxes.filter(':checked').length;
            this.$allCheckbox
                .prop('checked', checkedCount === this.$checkboxes.length && this.$checkboxes.length > 0)
                .prop('indeterminate', checkedCount > 0 && checkedCount < this.$checkboxes.length);
        },

        _handleAction: function (e) {
            var $el = $(e.currentTarget);
            var type = $el.data('type');
            var openInNewTab = $el.attr('target') == '_blank';
            e.preventDefault();

            switch (type) {
                case 'compare':
                    return this._compare($el.data('url'));
                case 'compare-remove':
                    return this._compareRemove($el.attr('href'), $el.data('value'));
                case 'print':
                    return this._print($el.data('url'), openInNewTab);
                case 'export':
                    return this._export($el.data('url'), $el.data('export-type'), openInNewTab);
                case 'update':
                    return this._update($el);
                case 'delete':
                    return this._delete($el.data('url'));
                case 'clear':
                    return this.selection.clear();
                case 'select':
                    return this._processSelection($el);
            }
        },

        _delete: function (url) {
            var self = this;

            bootbox.confirm(localizer.getString('DeleteSelectedRecordsQuestion'), function(confirmed) {
                if (confirmed) {

                    utils.createLoadingModalDialog(localizer.getString('Deleting')).modal();

                    var selectionData = self.selection.getData();
                    var formData = {
                        operation: 'delsel',
                        recordCount: selectionData.length,
                    };

                    _.each(selectionData, function (keys, i) {
                        formData['rec' + i] = null;

                        _.each(keys, function (value, pk) {
                            formData['rec' + i + '_pk' + pk] = value;
                        });

                        self.selection.remove(keys);
                    });

                    buildForm(url, formData).submit();
                }
            });
        },

        _compare: function (url) {
            this._processSelectedRecords(url, 'compare');
        },

        _compareRemove: function (url, value) {
            this.selection.remove(value);
            location.href = url;
        },

        _print: function (url, openInNewTab) {
            this._processSelectedRecords(url, 'print_selected', openInNewTab);
        },

        _export: function (url, exportType, openInNewTab) {
            this._processSelectedRecords(url, sprintf.sprintf('e%s_selected', exportType), openInNewTab);
        },

        _update: function ($el) {
            var self = this;
            if ($el.data('modal-operation') === 'multiple-edit') {
                var handlerName = $el.data('multiple-edit-handler-name');
                var url = jQuery.query.load($el.data('url'))
                    .set('hname', handlerName)
                    .set('keys', this.selection.getData())
                    .toString();
                $el.data('content-link', url);
                modalLink.processQuery(FormCollection, $el, function ($modal, hasErrors, responses, params) {
                    var $grid = $el.closest('.js-grid');
                    var $row = $();
                    if (!hasErrors) {
                        $.each(responses[0].row, function (i, row) {
                            $row = $(row);
                            $grid.find(".row-selection input").each(function() {
                                if ($(this).attr('data-value') === responses[0].primaryKeys[i]) {
                                    $(this).closest('.pg-row').replaceWith($row);
                                    self.grid.integrateRows($row);
                                    return false;
                                }
                            });
                        });
                        $modal.modal('hide');
                        self.selection.clear();
                        if (self.grid.getReloadPageAfterAjaxOperation()) {
                            location.reload();
                        }
                    }
                    return hasErrors;
                });
            } else {
                this._processSelectedRecords($el.data('url'), 'multi_edit');
            }
        },

        _processSelection: function($el) {
            var condition = $el.data('condition');
            if (condition == 'all') {
                location.href = jQuery.query.load($el.data('url'))
                    .remove('keys')
                    .set('selection_filter', '')
                    .toString();
            } else {
                location.href = jQuery.query.load($el.data('url'))
                    .set('selection_filter', condition)
                    .set('keys', this.selection.getData())
                    .toString();
            }
        },

        _initSelectionFilters: function($selectionHeader) {
            if ($selectionHeader === undefined) {
                return;
            }
            var self = this;
            var contentHtml = $('#selection-filters-content').html();
            var $selectionFiltersLink = $selectionHeader.find('a.selection-filters');
            if ($selectionFiltersLink.length == 0) {
                return;
            }
            var popover = $selectionFiltersLink.webuiPopover({
                trigger: 'manual',
                backdrop: true,
                placement: 'auto-bottom',
                padding: false,
                content: $(contentHtml)
            }).data('plugin_webuiPopover');

            $selectionFiltersLink.on('click', function (e) {
                e.preventDefault();
                popover.show();
                $('.webui-popover-backdrop').one('click', function () {
                    popover.hide();
                });
            });

            $selectionFiltersLink.on('show.webui.popover', function () {
                popover.$contentElement.find('ul.dropdown-menu').show();
                popover.$contentElement.find('.js-action').one('click', function() {
                    self._selectRecords($(this));
                    popover.hide();
                });
            });
        },

        _selectRecords: function($el) {
            var self = this;
            var url = jQuery.query.load($el.data('url'))
                .set('hname', $el.data('handler-name'))
                .set('filterName', $el.data('filter-name'))
                .toString();
            $.getJSON(url, function (data) {
                self.selection.clear();
                for (var i = 0; i < data.length; i++) {
                    self.selection.add(data[i]);
                }
            });
        },

        _processSelectedRecords: function (url, operation, openInNewTab) {
            var resultUrl = jQuery.query.load(url)
                .set('operation', operation)
                .set('keys', this.selection.getData())
                .toString();
            if (openInNewTab) {
                window.open(resultUrl, '_blank');
            } else {
                location.href = resultUrl;
            }
        }

    });
});
