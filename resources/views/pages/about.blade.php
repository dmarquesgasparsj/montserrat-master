@extends('template')
@section('content')
<h1>{{ __('messages.about_title') }}</h1>
<ul>
    <li><a href='https://github.com/arborrow/montserrat'>Version: 0.1 (alpha)</a>
    <li>Contributors include: <a href="https://github.com/arborrow/montserrat/graphs/contributors">Fr. Anthony Borrow, S.J., Gulmaro Salinas, and Ata Gowani</a>
<li>Issues: Bugs and feature requests can be reported via the <a href='https://github.com/arborrow/montserrat/issues'>Polanco issue tracker on Github.com</a>
<li>License: <a href='http://opensource.org/licenses/MIT'>MIT License</a>
<li>Designed with <a href='https://laravel.com'>Laravel</a>
<li><div>Icons made by <a href="https://www.freepik.com/" title="Freepik">Freepik</a> from <a href="https://www.flaticon.com/" 			    title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" 			    title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div>        
</ul>
<p>{{ __('messages.about_description') }}<p>
    {{ html()->img(asset('images/codetest.png'), __('messages.about_codetest_alt'))->attribute('title', __('messages.about_codetest_alt'))->attribute('style', "height: 300px;") }}
@stop
