@extends('admin.layout')

@section('admin-title')
    {{ $shop->name }} :: Random Stock
@endsection

@section('admin-content')
    {!! breadcrumbs([
        'Admin Panel' => 'admin',
        'Shops' => 'admin/data/shops',
        $shop->name => 'admin/data/shops/edit/' . $shop->id,
        'Random Stock' => 'admin/data/shops/random-stock',
    ]) !!}

    <h1>
        Randomize {{ $shop->name }}'s Stock</h1>
    <div>You can have regular stock as well as random stock. The randomization won't touch any normal stock.
        <ul>
            <li>Set the max total stock. This will allow the amount of random stock to change. Disable by checking off "Randomize in Range".</li>
            <li>Select the stock pool to randomize. This is what will be chosen between when the stock randomizes!</li>
            <li>Set a currency and cost for each item, this will be the cost when the stock is added.</li>
            <li>Edit the extra settings as well to set the stats for when the stock is added. These stats will apply to all items.</li>
        </ul>
    </div>

    {!! Form::open(['url' => 'admin/data/shops/random-stock/edit/' . $shop->id]) !!}

    <div class="row">
        <div class="col-md">
            <div class="form-group">
                {!! Form::label('randomize_interval', 'Randomize Interval') !!}
                {!! Form::select('randomize_interval', [1 => 'Day', 2 => 'Week', 3 => 'Month'], $shop->shopRandomData['randomize_interval'] ?? 2, ['class' => 'form-control stock-field']) !!}
            </div>
        </div>
        <div class="col-md">
            <div class="form-group">
                {!! Form::label('Max Stock') !!} {!! add_help('Max stock count to randomize.') !!}
                {!! Form::number('max_items', isset($shop->shopRandomData['max_items']) ? $shop->shopRandomData['max_items'] : '1', ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="col-md">
            <div class="form-group">
                {!! Form::checkbox('stock_range', 1, isset($shop->shopRandomData['stock_range']) ? $shop->shopRandomData['stock_range'] : 0, ['class' => 'form-check-label', 'data-toggle' => 'toggle']) !!}
                {!! Form::label('stock_range', 'Randomize in Range?', ['class' => 'form-check-label ml-3']) !!} {!! add_help('If ticked to yes it will restock a random quantity of randomized stock between 1 and the quantity set above. Otherwise, it will always restock the amount that you set above.') !!}
            </div>
        </div>
    </div>

    <h3>Further Settings</h3>
    Will apply to each randomized stock upon creation.
    <br>
    <hr>
    <h5 class="card-header inventory-header">
        <a class="inventory-collapse-toggle collapse-toggle collapsed" href="#shop-collapse" data-toggle="collapse">Settings</a></h3>
    </h5>
    <div class="card-body inventory-body collapse collapsed" id="shop-collapse">

        <div class="pl-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::checkbox('use_user_bank', 1, isset($shop->shopRandomData['use_user_bank']) ? $shop->shopRandomData['use_user_bank'] : 1, ['class' => 'form-check-input stock-toggle stock-field', 'data-name' => 'use_user_bank']) !!}
                        {!! Form::label('use_user_bank', 'Use User Bank', ['class' => 'form-check-label ml-3']) !!} {!! add_help('This will allow users to purchase the item using the currency in their accounts, provided that users can own that currency.') !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-0">
                        {!! Form::checkbox('use_character_bank', 1, isset($shop->shopRandomData['use_character_bank']) ? $shop->shopRandomData['use_character_bank'] : 1, ['class' => 'form-check-input stock-toggle stock-field', 'data-name' => 'use_character_bank']) !!}
                        {!! Form::label('use_character_bank', 'Use Character Bank', ['class' => 'form-check-label ml-3']) !!} {!! add_help('This will allow users to purchase the item using the currency belonging to characters they own, provided that characters can own that currency.') !!}
                    </div>
                </div>
            </div>

            <div class="form-group">
                {!! Form::checkbox('is_fto', 1, isset($shop->shopRandomData['is_fto']) ? $shop->shopRandomData['is_fto'] : 0, ['class' => 'form-check-input stock-toggle stock-field', 'data-name' => 'is_fto']) !!}
                {!! Form::label('is_fto', 'FTO Only?', ['class' => 'form-check-label ml-3']) !!} {!! add_help('If turned on, only FTO will be able to purchase the item.') !!}
            </div>

            <div class="form-group">
                {!! Form::checkbox('is_limited_stock', 1, isset($shop->shopRandomData['is_limited_stock']) ? $shop->shopRandomData['is_limited_stock'] : 0, ['class' => 'form-check-input stock-limited stock-toggle stock-field', 'id' => 'is_limited_stock']) !!}
                {!! Form::label('is_limited_stock', 'Set Limited Stock', ['class' => 'form-check-label ml-3']) !!} {!! add_help('If turned on, will limit the amount purchaseable to the quantity set below.') !!}
            </div>

            <div class="form-group">
                {!! Form::checkbox('disallow_transfer', 1, isset($shop->shopRandomData['disallow_transfer']) ? $shop->shopRandomData['disallow_transfer'] : 0, ['class' => 'form-check-input stock-toggle stock-field', 'data-name' => 'disallow_transfer']) !!}
                {!! Form::label('disallow_transfer', 'Disallow Transfer', ['class' => 'form-check-label ml-3']) !!} {!! add_help('If turned on, users will be unable to transfer this item after purchase.') !!}
            </div>

            <div class="form-group">
                {!! Form::checkbox('is_visible', 1, isset($shop->shopRandomData['is_visible']) ? $shop->shopRandomData['is_visible'] : 1, ['class' => 'form-check-input stock-limited stock-toggle stock-field']) !!}
                {!! Form::label('is_visible', 'Set Visibility', ['class' => 'form-check-label ml-3']) !!} {!! add_help('If turned off it will not appear in the store.') !!}
            </div>
        </div>

        <div class="card mb-3 stock-limited-quantity {{ $shop->shopRandomData['is_timed_stock'] ? '' : 'hide' }}">
            <div class="card-body">
                <div>
                    {!! Form::label('quantity', 'Quantity') !!} {!! add_help('If left blank, will be set to 0 (sold out).') !!}
                    {!! Form::text('quantity', isset($shop->shopRandomData['quantity']) ? $shop->shopRandomData['quantity'] : 0, ['class' => 'form-control stock-field']) !!}
                </div>
                <div class="my-2">
                    {!! Form::checkbox('restock', 1, isset($shop->shopRandomData['restock']) ? $shop->shopRandomData['restock'] : 0, ['class' => 'form-check-input']) !!}
                    {!! Form::label('restock', 'Auto Restock?', ['class' => 'form-check-label ml-3']) !!} {!! add_help('If ticked to yes it will auto restock at the interval defined below.') !!}
                </div>
                <div>
                    {!! Form::label('restock_interval', 'Restock Interval') !!}
                    {!! Form::select('restock_interval', [1 => 'Day', 2 => 'Week', 3 => 'Month'], isset($shop->shopRandomData['restock_interval']) ? $shop->shopRandomData['restock_interval'] : 2, ['class' => 'form-control stock-field']) !!}
                </div>
                <div class="my-2">
                    {!! Form::checkbox('range', 1, isset($shop->shopRandomData['range']) ? $shop->shopRandomData['range'] : 0, ['class' => 'form-check-input']) !!}
                    {!! Form::label('range', 'Restock in Range?', ['class' => 'form-check-label ml-3']) !!} {!! add_help('If ticked to yes it will restock a random quantity between 1 and the quantity set above.') !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                {!! Form::label('purchase_limit', 'User Purchase Limit') !!} {!! add_help('This is the maximum amount of this item a user can purchase from this shop. Set to 0 to allow infinite purchases.') !!}
                {!! Form::text('purchase_limit', isset($shop->shopRandomData['purchase_limit']) ? $shop->shopRandomData['purchase_limit'] : 0, ['class' => 'form-control stock-field', 'data-name' => 'purchase_limit']) !!}
            </div>
            <div class="col-md-6">
                {!! Form::label('purchase_limit_timeframe', 'Purchase Limit Timeout') !!} {!! add_help('This is the timeframe that the purchase limit will apply to. I.E. yearly will only look at purchases made after the beginning of the current year. Weekly starts on Sunday. Rollover will happen on UTC time.') !!}
                {!! Form::select(
                    'purchase_limit_timeframe',
                    ['lifetime' => 'Lifetime', 'yearly' => 'Yearly', 'monthly' => 'Monthly', 'weekly' => 'Weekly', 'daily' => 'Daily'],
                    isset($shop->shopRandomData['purchase_limit_timeframe']) ? $shop->shopRandomData['purchase_limit_timeframe'] : null,
                    [
                        'class' => 'form-control stock-field',
                        'data-name' => 'purchase_limit_timeframe',
                    ],
                ) !!}
            </div>
        </div>
        <br>
        <div class="pl-4">
            <div class="form-group">
                {!! Form::checkbox('is_timed_stock', 1, isset($shop->shopRandomData['is_timed_stock']) ? $shop->shopRandomData['is_timed_stock'] : 0, ['class' => 'form-check-input stock-timed stock-toggle stock-field', 'id' => 'is_timed_stock']) !!}
                {!! Form::label('is_timed_stock', 'Set Timed Stock', ['class' => 'form-check-label ml-3']) !!} {!! add_help('Sets the stock as timed between the chosen dates.') !!}
            </div>
            <div class="stock-timed-quantity {{ $shop->shopRandomData['is_timed_stock'] ? '' : 'hide' }}">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('start_at', 'Start Time') !!} {!! add_help('Stock will cycle in at this date.') !!}
                            {!! Form::text('start_at', isset($shop->shopRandomData['start_at']) ? $shop->shopRandomData['start_at'] : null, ['class' => 'form-control datepicker']) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('end_at', 'End Time') !!} {!! add_help('Stock will cycle out at this date.') !!}
                            {!! Form::text('end_at', isset($shop->shopRandomData['end_at']) ? $shop->shopRandomData['end_at'] : null, ['class' => 'form-control datepicker']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>


    <h3>Current Rolling Pool</h3>
    <p>Remember that this is not the current stock. This is what will potentially be rolled into.</p>

    <div class="text-right mb-3">
        <a href="#" class="btn btn-outline-info" id="addStock">Add Option</a>
    </div>
    <table class="table table-sm" id="stockTable">
        <thead>
            <tr>
                <th width="35%">Stock Type</th>
                <th width="35%">Stock</th>
                <th width="20%">Currency</th>
                <th width="20%">Cost</th>
                <th width="10%"></th>
            </tr>
        </thead>
        <tbody id="stockTableBody">
            @if (isset($shop->decodedStock) && is_array($shop->decodedStock))
                @foreach ($shop->decodedStock as $stock)
                    <tr class="stock-row">
                        <td>{!! Form::select(
                            'stock_type[]',
                            [
                                'Item' => 'Item',
                            ],
                            $stock->stock_type,
                            ['class' => 'form-control stock-type', 'placeholder' => 'Select Stock Type'],
                        ) !!}</td>
                        <td class="stock-row-select">
                            @if ($stock->stock_type == 'Item')
                                {!! Form::select('item_id[]', $items, $stock->item_id, [
                                    'class' => 'form-control item-select selectize',
                                    'placeholder' => 'Select Item',
                                ]) !!}
                            @endif
                        </td>
                        <td>{!! Form::select('currency_id[]', $currencies, $stock->currency_id, ['class' => 'form-control']) !!}</td>
                        <td>{!! Form::text('cost[]', $stock->cost, ['class' => 'form-control']) !!}</td>
                        <td class="text-right"><a href="#" class="btn btn-danger remove-stock-button">Remove</a></td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>


    <div class="text-right">
        {!! Form::submit('Edit', ['class' => 'btn btn-primary']) !!}
    </div>

    {!! Form::close() !!}

    <div id="stockRowData" class="hide">
        <table class="table table-sm">
            <tbody id="stockRow">
                <tr class="stock-row">
                    <td>{!! Form::select(
                        'stock_type[]',
                        [
                            'Item' => 'Item',
                        ],
                        null,
                        ['class' => 'form-control stock-type', 'placeholder' => 'Select Stock Type'],
                    ) !!}</td>
                    <td class="stock-row-select"></td>
                    <td>{!! Form::select('currency_id[]', $currencies, 0, [
                        'class' => 'form-control',
                    ]) !!}</td>
                    <td>{!! Form::text('cost[]', 1, ['class' => 'form-control']) !!}</td>
                    <td class="text-right"><a href="#" class="btn btn-danger remove-stock-button">Remove</a></td>
                </tr>
            </tbody>
        </table>
        {!! Form::select('item_id[]', $items, null, [
            'class' => 'form-control item-select',
            'placeholder' => 'Select Item',
        ]) !!}
    </div>

    <h4>Current Random Stock:</h4>
    @if (!count($shopstock))
        <p>No stock found.</p>
    @else
        <table class="table table-sm stock-table">
            <tbody>
                @foreach ($shopstock as $stock)
                    <tr class="sort-item" data-id="{{ $stock->id }}">
                        <td>
                            @if ($stock->item->has_image)
                                <img src="{{ $stock->item->imageUrl }}" class="img-fluid mr-2" style="height: 2em;" />
                            @endif
                            <a href="{{ $stock->item->idUrl }}"><strong>{{ $stock->item->name }} -
                                    {{ $stock->stock_type }}</strong></a>
                            @if (!$stock->is_visible)
                                <i class="fas fa-eye-slash"></i>
                            @endif
                            @if ($stock->is_timed_stock)
                                <i class="fas fa-clock"></i>
                            @endif

                        </td>
                        <td>
                            <strong>Cost: </strong> {!! $stock->currency->display($stock->cost) !!}
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm" onclick="editStock({{ $stock->id }})">
                                {{-- pencil icon --}}
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <div class="btn btn-danger btn-sm" onclick="deleteStock({{ $stock->id }})">
                                {{-- trash icon --}}
                                <i class="fas fa-trash"></i>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
    @endif


@endsection

@section('scripts')
    @parent
    <script>
        // edit stock function
        function editStock(id) {
            loadModal("{{ url('admin/data/shops/stock/edit') }}/" + id, 'Edit Stock');
        }

        function deleteStock(id) {
            loadModal("{{ url('admin/data/shops/stock/delete') }}/" + id, 'Delete Stock');
        }

        $(document).ready(function() {
            var $stockTable = $('#stockTableBody');
            var $stockRow = $('#stockRow').find('.stock-row');
            var $itemSelect = $('#stockRowData').find('.item-select');


            $('#stockTableBody .selectize').selectize();
            attachRemoveListener($('#stockTableBody .remove-stock-button'));

            $('#addStock').on('click', function(e) {
                e.preventDefault();
                var $clone = $stockRow.clone();
                $stockTable.append($clone);
                attachStockTypeListener($clone.find('.stock-type'));
                attachRemoveListener($clone.find('.remove-stock-button'));
            });


            $('.stock-type').on('change', function(e) {
                var val = $(this).val();
                var $cell = $(this).parent().find('.stock-row-select');

                var $clone = null;
                if (val == 'Item') $clone = $itemSelect.clone();

                $cell.html('');
                $cell.append($clone);
            });

            function attachStockTypeListener(node) {
                node.on('change', function(e) {
                    var val = $(this).val();
                    var $cell = $(this).parent().parent().find('.stock-row-select');

                    var $clone = null;
                    if (val == 'Item') $clone = $itemSelect.clone();

                    $cell.html('');
                    $cell.append($clone);
                    $clone.selectize();
                });
            }

            function attachRemoveListener(node) {
                node.on('click', function(e) {
                    e.preventDefault();
                    $(this).parent().parent().remove();
                });
            }

            // is_limited_stock change
            $('#is_limited_stock').change(function() {
                if ($(this).is(':checked')) {
                    $('.stock-limited-quantity').removeClass('hide');
                } else {
                    $('.stock-limited-quantity').addClass('hide');
                }
            });
            // is_timed_stock change
            $('#is_timed_stock').change(function() {
                if ($(this).is(':checked')) {
                    $('.stock-timed-quantity').removeClass('hide');
                } else {
                    $('.stock-timed-quantity').addClass('hide');
                }
            });

            $(".datepicker").datetimepicker({
                dateFormat: "yy-mm-dd",
                timeFormat: 'HH:mm:ss',
            });

        });
    </script>
@endsection
