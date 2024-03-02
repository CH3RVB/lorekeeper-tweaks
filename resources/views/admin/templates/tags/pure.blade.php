<h5>Pure HTML Template</h5>

<div class="form-group">
    {!! Form::label('data[pure_html]', 'Pure HTML') !!}
    <div class="card" style="height: 300px">
        <div id="codeeditor"></div>
        {!! Form::textarea('data[pure_html]', $template->data['pure_html'] ?? '', ['class' => 'form-control', 'id' => 'codearea', 'class' => 'hide']) !!}
    </div>
       
    
</div>

@section('scripts')
@parent
<script>
$( document ).ready(function() {
    const aceEditor = ace.edit("codeeditor")
    aceEditor.setTheme("ace/theme/chrome");
    aceEditor.session.setMode("ace/mode/php_laravel_blade");
    aceEditor.setOption("showPrintMargin", false);
    
    var textarea = $('#codearea');
    aceEditor.getSession().setValue(textarea.val());
    aceEditor.getSession().on('change', function(){
        textarea.val(aceEditor.getSession().getValue());
    });
});
</script>
@endsection