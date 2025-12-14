@extends('layouts.librenmsv1')

@section('content')
    <x-device.page :device="$device" subtitle="{{ __('Syslog') }}">
        <x-device.log-tabs :device="$device" tab="syslog"/>

        <x-panel title="{{ __('Syslog') }}">
            <div class="table-responsive">
                <table id="syslog" class="table table-hover table-condensed table-striped"
                    data-url="{{ route('table.syslog') }}" data-export="false">
                    <thead>
                    <tr>
                        <th data-column-id="label" data-width="20" data-sortable="false"></th>
                        <th data-column-id="timestamp" data-width="160" data-order="desc">@lang('Timestamp')</th>
                        <th data-column-id="level">@lang('Level')</th>
                        <th data-column-id="program">@lang('Program')</th>
                        <th data-column-id="msg">@lang('Message')</th>
                        <th data-column-id="priority">@lang('Priority')</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </x-panel>
    </x-device.page>
@endsection

@push('scripts')
    <script>
        $(function () {
            const syslog_grid = $("#syslog").bootgrid({
                ajax: true,
                rowCount: [20, 50, 100, 250, -1],
                templates: {
                    header: '<div id="@{{ctx.id}}" class="@{{css.header}} tw:flex tw:flex-wrap tw:items-center">' +
                        '<form class="tw:flex tw:flex-wrap tw:items-center" role="form" id="syslog_filter">' +
                            '<div class="tw:flex tw:items-baseline tw:ml-2">' +
                                '<select name="program" id="program" class="form-control"></select>' +
                            '</div>' +
                            '<div class="tw:flex tw:items-baseline tw:ml-2">' +
                                '<select name="priority" id="priority" class="form-control"></select>' +
                            '</div>' +
                            '<div class="tw:flex tw:relative tw:items-baseline tw:ml-2">' +
                                '<input name="from" type="text" class="form-control" id="dtpickerfrom" maxlength="16" value="' + @json($from) + '" placeholder="From" data-date-format="YYYY-MM-DD HH:mm">' +
                            '</div>' +
                            '<div class="tw:flex tw:relative tw:items-baseline tw:ml-2">' +
                                '<input name="to" type="text" class="form-control" id="dtpickerto" maxlength="16" value="' + @json($to) + '" placeholder="To" data-date-format="YYYY-MM-DD HH:mm">' +
                            '</div>' +
                            '<button type="submit" class="btn btn-default tw:ml-2">@lang("Filter")</button>' +
                            '<button type="button" class="btn btn-default tw:ml-2" id="syslog_clear">@lang("Clear")</button>' +
                        '</form>' +
                        '<div class="actionBar tw:ml-auto tw:relative">' +
                            '<div class="@{{css.search}}"></div>' +
                            '<div class="@{{css.actions}}"></div>' +
                        '</div>' +
                    '</div>'
                },
                post: function ()
                {
                    return {
                        device: {{ $device->device_id }},
                        program: $('#program').val() || '',
                        priority: $('#priority').val() || '',
                        from: $('#dtpickerfrom').val() || '',
                        to: $('#dtpickerto').val() || '',
                    };
                },
            });

            const dtIcons = {
                time: 'fa fa-clock-o',
                date: 'fa fa-calendar',
                up: 'fa fa-chevron-up',
                down: 'fa fa-chevron-down',
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-calendar-check-o',
                clear: 'fa fa-trash-o',
                close: 'fa fa-close'
            };
            $("#syslog").on("loaded.rs.jquery.bootgrid", function () {
                $("#dtpickerfrom").datetimepicker({
                    icons: dtIcons,
                    defaultDate: '{{ $default_date }}'
                });
                $("#dtpickerfrom").on("dp.change", function (e) {
                    $("#dtpickerto").data("DateTimePicker").minDate(e.date);
                });
                $("#dtpickerto").datetimepicker({
                    icons: dtIcons,
                });
                $("#dtpickerto").on("dp.change", function (e) {
                    $("#dtpickerfrom").data("DateTimePicker").maxDate(e.date);
                });
                if ($("#dtpickerfrom").val() != "") {
                    $("#dtpickerto").data("DateTimePicker").minDate($("#dtpickerfrom").val());
                }
                if ($("#dtpickerto").val() != "") {
                    $("#dtpickerfrom").data("DateTimePicker").maxDate($("#dtpickerto").val());
                } else {
                    $("#dtpickerto").data("DateTimePicker").maxDate('{{ $now }}');
                }
                $("#syslog_filter").on("submit", function (e) {
                    e.preventDefault();
                    syslog_grid.bootgrid("reload", true);
                });

                init_select2("#program", "syslog", @json($syslog_program_filter), @json($program), "@lang('All Programs')");
                init_select2("#priority", "syslog", @json($syslog_priority_filter), @json($priority), "@lang('All Priorities')");
                $("#syslog_clear").on("click", function () {
                    $("#program").val(null).trigger("change");
                    $("#priority").val(null).trigger("change");

                    $("#syslog").find(".search-field").val("");
                    syslog_grid.bootgrid("search", "");

                    const fromPicker = $("#dtpickerfrom").data("DateTimePicker");
                    const toPicker   = $("#dtpickerto").data("DateTimePicker");

                    const now   = moment();
                    const from  = moment(now).subtract(7, 'day');

                    fromPicker.minDate(false);
                    fromPicker.maxDate(now);
                    toPicker.minDate(from);
                    toPicker.maxDate(now);

                    fromPicker.date(from);
                    toPicker.date(now);

                    syslog_grid.bootgrid("reload", true);
                });
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        #syslog-header .actionBar {
            display: flex;
            align-items: center;
        }
        #syslog-header .actionBar .search {
            margin-right: .5rem;
            float: none;
        }
        #syslog-header .actionBar > .actions {
            display: flex;
        }
        #syslog-header .actionBar > .actions > * {
            float: none;
        }
    </style>
@endpush