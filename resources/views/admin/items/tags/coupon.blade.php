<h3>Coupon</h3>

<h4>Discount</h4>
<p>Input discount percent. You can select what coupons can be used in each shop on the shop edit page.</p>

    <div class="form-group">
        {!! Form::label('discount', 'Discount') !!}
        {!! Form::number('discount', null, ['class' => 'form-control', 'placeholder' => 'Input Discount Percent', 'min' => 1, 'max' => 100 ]) !!} 
    </div>