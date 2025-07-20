@extends('template')
@section('content')
<div class="jumbotron text-left">
    <h2>Laravel Autocomplete</h2>
    {{ html()->form()->open() }}
    {{ html()->label(__('messages.find_retreatant_label'), 'auto') }}
    {{ html()->text('auto')->id('auto') }}
    <br>
    {{ html()->label(__('messages.retreatant_id_label'), 'response') }}
    {{ html()->text('response')->id('response')->attribute('disabled', 'disabled') }}
    {{ html()->form()->close() }}
</div>
<script type="text/javascript">
    $(function() {
        $("#auto").autocomplete({
            source: '../getdata',
            minLength: 1,
            select: function( event, ui ) {
                $('#response').val(ui.item.id);
            }
        });
    });
</script>
@endsection
