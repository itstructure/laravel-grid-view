@php
    /** @var \Itstructure\GridView\Columns\BaseColumn[] $columnObjects */
    /** @var \Illuminate\Pagination\LengthAwarePaginator $paginator */
    /** @var boolean $useFilters */
    $checkboxesExist = false;
@endphp
<style>
    .table-bordered tfoot tr td {
        border-width: 0;
    }
</style>
<div class="card">
    <div class="card-header">
        @if($title)
            <h2 class="card-title">{!! $title !!}</h2>
        @endif
        <div class="float-right">
            @if ($paginator->onFirstPage())
                {!! trans('grid_view::grid.page-info', [
                    'start' => '<b>1</b>',
                    'end' => '<b>' . $paginator->perPage() . '</b>',
                    'total' => '<b>' . $paginator->total() . '</b>',
                ]) !!}
            @elseif ($paginator->currentPage() == $paginator->lastPage())
                {!! trans('grid_view::grid.page-info', [
                    'start' => '<b>' . (($paginator->currentPage() - 1) * $paginator->perPage() + 1) . '</b>',
                    'end' => '<b>' . $paginator->total() . '</b>',
                    'total' => '<b>' . $paginator->total() . '</b>',
                ]) !!}
            @else
                {!! trans('grid_view::grid.page-info', [
                    'start' => '<b>' . (($paginator->currentPage() - 1) * $paginator->perPage() + 1) . '</b>',
                    'end' => '<b>' . (($paginator->currentPage()) * $paginator->perPage()) . '</b>',
                    'total' => '<b>' . $paginator->total() . '</b>',
                ]) !!}
            @endif
        </div>
    </div>
    <div class="card-body">
        <table class="table @if($tableBordered) table-bordered @endif @if($tableStriped) table-striped @endif @if($tableHover) table-hover @endif @if($tableSmall) table-sm @endif">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    @foreach($columnObjects as $column_obj)
                        <th {!! $column_obj->buildHtmlAttributes() !!}>

                            @if($column_obj->getSort() === false || $column_obj instanceof \Itstructure\GridView\Columns\ActionColumn)
                                {{ $column_obj->getLabel() }}

                            @elseif($column_obj instanceof \Itstructure\GridView\Columns\CheckboxColumn)
                                @php($checkboxesExist = true)
                                @if($useFilters)
                                    {{ $column_obj->getLabel() }}
                                @else
                                    <input type="checkbox" id="grid_view_checkbox_main" class="form-control form-control-sm" @if($paginator->count() == 0) disabled="disabled" @endif />
                                @endif

                            @else
                                <a href="{{ \Itstructure\GridView\Helpers\SortHelper::getSortableLink(request(), $column_obj) }}">{{ $column_obj->getLabel() }}</a>
                            @endif

                        </th>
                    @endforeach
                </tr>
                @if ($useFilters)
                    <tr>
                        <form action="{{ $filtersFormAction }}" method="get" id="grid_view_filters_form">
                            <td></td>
                            @foreach($columnObjects as $column_obj)
                                <td>
                                    @if($column_obj instanceof \Itstructure\GridView\Columns\CheckboxColumn)
                                        <input type="checkbox" id="grid_view_checkbox_main" class="form-control form-control-sm" @if($paginator->count() == 0) disabled="disabled" @endif />
                                    @else
                                        {!! $column_obj->getFilter()->render() !!}
                                    @endif
                                </td>
                            @endforeach
                            <input type="submit" class="d-none">
                        </form>
                    </tr>
                @endif
            </thead>

            <form action="{{ $rowsFormAction }}" method="post" id="grid_view_rows_form">
                <tbody>
                    @foreach($paginator->items() as $key => $row)
                        <tr>
                            <td>{{ ($paginator->currentPage() - 1) * $paginator->perPage() + $key + 1 }}</td>
                            @foreach($columnObjects as $column_obj)
                                <td>{!! $column_obj->render($row) !!}</td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="{{ count($columnObjects) + 1 }}">
                            <div class="mx-1">
                                <div class="row">
                                    <div class="col-12 col-xl-8 text-center text-xl-left">
                                        {{ $paginator->render('grid_view::pagination') }}
                                    </div>
                                    <div class="col-12 col-xl-4 text-center text-xl-right">
                                        @if ($useFilters)
                                            <button id="grid_view_search_button" type="button" class="btn btn-primary">{{ $searchButtonLabel }}</button>
                                            <button id="grid_view_reset_button" type="button" class="btn btn-warning">{{ $resetButtonLabel }}</button>
                                        @endif
                                        @if (($checkboxesExist || $useSendButtonAnyway) && $paginator->count() > 0)
                                            <button type="submit" class="btn btn-danger">{{ $sendButtonLabel }}</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tfoot>
                <input type="hidden" value="{!! csrf_token() !!}" name="_token">
            </form>
        </table>
    </div>
</div>
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        $('#grid_view_checkbox_main').click(function (event) {
            $('input[role="grid-view-checkbox-item"]').prop('checked', event.target.checked);
        });

        $('#grid_view_search_button').click(function () {
            $('#grid_view_filters_form').submit();
        });

        $('#grid_view_reset_button').click(function () {
            $('input[role="grid-view-filter-item"]').val('');
            $('select[role="grid-view-filter-item"]').prop('selectedIndex', 0);
        });
    });
</script>