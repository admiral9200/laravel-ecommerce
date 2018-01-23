<div class="table-responsive">
<table class="mage2-table-grid table table-striped">
    <thead class="thead-dark">
    <tr >
        @foreach($dataGrid->columns as $column)
            <th>
                @if($column->sortable() && $dataGrid->desc($column->identifier()))
                    <a href="{{ $column->ascUrl() }}">
                        {{ $column->label() }}
                        <i class="text-primary oi oi-arrow-bottom"></i>
                    </a>
                @elseif($column->sortable() && $dataGrid->asc($column->identifier()))
                        <a href="{{ $column->descUrl() }}">
                            {{ $column->label() }}
                            <i class="text-primary oi oi-arrow-top"></i>
                        </a>
                @elseif($column->sortable() )
                    <a href="{{ $column->descUrl() }}">
                        {{ $column->label() }}
                        <i class="text-secondary oi oi-arrow-top"></i>
                    </a>
                @else
                    {{ $column->label() }}

                @endif

            </th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($dataGrid->data as $row)
        <tr>
            @foreach($dataGrid->columns as $column)
                <td>
                    @if($column->type() == "link")
                        {!! $column->executeCallback($row) !!}
                    @else
                        <?php $identifier = $column->identifier() ?>
                        {{ $row->$identifier }}
                    @endif
                </td>
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>
</div>
{!! $dataGrid->data->appends(Request::except('page'))->render('pagination::bootstrap-4') !!}
