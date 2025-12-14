@extends('layouts.librenmsv1')

@section('content')
    <x-device.page :device="$device" subtitle="{{ __('Graylog') }}">
        <x-device.log-tabs :device="$device" tab="graylog" />

        <x-panel title="{{ __('Graylog') }}">
            <div class="table-responsive">
                <table id="graylog" class="table table-hover table-condensed graylog"
                    data-url="{{ route('table.graylog') }}" data-export="false">
                    <thead>
                    <tr>
                        <th data-column-id="severity" data-sortable="false"></th>
                        <th data-column-id="origin">Origin</th>
                        <th data-column-id="timestamp" data-formatter="browserTime">Timestamp</th>
                        <th data-column-id="level">Level</th>
                        <th data-column-id="source">Source</th>
                        <th data-column-id="message" data-sortable="false">Message</th>
                        <th data-column-id="facility">Facility</th>
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
            const graylog_grid = $("#graylog").bootgrid({
                ajax: true,
                rowCount: [20, 50, 100, 250, -1],
                templates: {
                    header: '<div id="@{{ctx.id}}" class="@{{css.header}} tw:flex tw:flex-wrap tw:items-center">' +
                        '<form class="tw:flex tw:flex-wrap tw:items-center" role="form" id="graylog_filter">' +
                            '<div class="tw:flex tw:items-baseline tw:ml-2">' +
                                '<select name="stream" id="stream" class="form-control"></select>' +
                            '</div>' +
                            '<div class="tw:flex tw:items-baseline tw:ml-2">' +
                                '<select name="loglevel" id="loglevel" class="form-control">' +
                                    '<option value="" disabled selected>All LogLevels</option>' +
                                    '<option value="0">(0) {{ __("syslog.severity.0") }}</option>' +
                                    '<option value="1">(1) {{ __("syslog.severity.1") }}</option>' +
                                    '<option value="2">(2) {{ __("syslog.severity.2") }}</option>' +
                                    '<option value="3">(3) {{ __("syslog.severity.3") }}</option>' +
                                    '<option value="4">(4) {{ __("syslog.severity.4") }}</option>' +
                                    '<option value="5">(5) {{ __("syslog.severity.5") }}</option>' +
                                    '<option value="6">(6) {{ __("syslog.severity.6") }}</option>' +
                                    '<option value="7">(7) {{ __("syslog.severity.7") }}</option>' +
                                '</select>' +
                            '</div>' +
                            '<div class="tw:flex tw:items-baseline tw:ml-2">' +
                                '<select id="range" name="range" class="form-control">' +
                                    '<option value="0">All Time Ranges</option>' +
                                    '<option value="300">Last 5 minutes</option>' +
                                    '<option value="900">Last 15 minutes</option>' +
                                    '<option value="1800">Last 30 minutes</option>' +
                                    '<option value="3600">Last 1 hour</option>' +
                                    '<option value="7200">Last 2 hours</option>' +
                                    '<option value="28800">Last 8 hours</option>' +
                                    '<option value="86400">Last 1 day</option>' +
                                    '<option value="172800">Last 2 days</option>' +
                                    '<option value="432000">Last 5 days</option>' +
                                    '<option value="604800">Last 7 days</option>' +
                                    '<option value="1209600">Last 14 days</option>' +
                                    '<option value="2592000">Last 30 days</option>' +
                                '</select>' +
                            '</div>' +
                            '<button type="submit" class="btn btn-default tw:ml-2">@lang("Filter")</button>' +
                            '<button type="button" class="btn btn-default tw:ml-2" id="graylog_clear">@lang("Clear")</button>' +
                        '</form>' +
                        '<div class="actionBar tw:ml-auto tw:relative">' +
                            '<div class="@{{css.search}}"></div>' +
                            '<div class="@{{css.actions}}"></div>' +
                        '</div>' +
                    '</div>'
                },
                post: function () {
                    return {
                        device: {{ $device->device_id }},
                        stream: $('#stream').val() || '',
                        range: $('#range').val() || '',
                        loglevel: $('#loglevel').val() || '',
                    };
                },
            });
            $("#graylog").on("loaded.rs.jquery.bootgrid", function () {
                init_select2("#stream", "graylog", @json($graylog_filter), @json($stream),'All Messages');
                $("#graylog_clear").on("click", function () {
                    $("#stream").val(null).trigger("change");
                    $("#loglevel").val(null).trigger("change");
                    $("#range").val('0').trigger("change");

                    $("#graylog").find(".search-field").val("");
                    graylog_grid.bootgrid("search", "");
                    graylog_grid.bootgrid("reload", true);
                });
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        #graylog-header .actionBar {
            display: flex;
            align-items: center;
        }
        #graylog-header .actionBar .search {
            margin-right: .5rem;
            float: none;
        }
        #graylog-header .actionBar > .actions {
            display: flex;
        }
        #graylog-header .actionBar > .actions > * {
            float: none;
        }
    </style>
@endpush
