<!doctype html>
<html lang="{{ session('applocale', config('app.locale')) }}">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Montserrat Retreat House Database</title>
	<!-- generated from https://realfavicongenerator.net/ -->
	<link rel="apple-touch-icon" sizes="180x180" href="/images/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/images/favicon-16x16.png">
	<link rel="manifest" href="/site.webmanifest">
	<link rel="mask-icon" href="/images/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="theme-color" content="#ffffff">

	<link rel="stylesheet" type="text/css" href="{{ url(mix('dist/bundle.css')) }}">
	<script src="{{ url(mix('dist/bundle.js')) }}"></script>
	<script>
                function ConfirmDelete() {
                        var x = confirm("{{ __('messages.delete_confirm') }}");
                        if (x)
                                return true;
                        else
                                return false;
		}
	</script>

</head>

<body>
	<div class="container flash-messages">
		@include('flash::message')
	</div>
	<div class="container pt-0">
		<nav class="navbar navbar-expand-lg navbar-light">
			<a class="navbar-brand" href={{ ( Auth::check() ) ? route('welcome') : route('home') }}>
                                <img src="{{URL('/images/mrhlogoblack.png')}}" alt="{{ __('messages.home') }}" class="logo">
			</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarContent">
				<ul class="navbar-nav mr-auto">
					@can('show-contact')
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        {{ __('messages.contacts') }}
						</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                                        <a class="dropdown-item" href={{ route('person.index') }}>{{ __('messages.persons') }}</a>
                                                        <a class="dropdown-item" href={{ route('parish.index') }}>{{ __('messages.parishes') }}</a>
                                                        <a class="dropdown-item" href={{ route('diocese.index') }}>{{ __('messages.dioceses') }}</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href={{ route('organization.index') }}>Organizations</a>
							<a class="dropdown-item" href={{ route('vendor.index') }}>Vendors</a>
							@can('show-squarespace-order')
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href={{ route('squarespace.order.index') }}>Squarespace Orders</a>
							@endcan
							@can('show-gift-certificate')
							<a class="dropdown-item" href={{ route('gift_certificate.index') }}>Gift Certificates</a>
							@endcan
							@can('show-touchpoint')
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href={{ route('touchpoint.index') }}>Touchpoints</a>
							@endcan
							@can('update-contact')
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href={{ route('person.duplicates') }}>Duplicates</a>
							@endcan
						</div>
					</li>
					@endCan
					@can('show-retreat')
					<li class="nav-item">
                                                <a class="nav-link" href={{ route('retreat.index') }}>{{ __('messages.events') }}</a>
					</li>
					@endCan
					@can('show-room')
					<li class="nav-item">
                                                <a class="nav-link" href={{ route('rooms') }}>{{ __('messages.rooms') }}</a>
					</li>
					@endCan
					@can('show-donation')
					<li class="nav-item">
                                                <a class="nav-link" href={{ route('finance') }}>{{ __('messages.finance') }}</a>
					</li>
					@endCan

					@can('show-asset')
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        {{ __('messages.maintenance') }}
						</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                                        <a class="dropdown-item" href={{ route('maintenance') }}>{{ __('messages.maintenance') }}</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href={{ route('asset.index') }}>Assets</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href={{ route('asset_type.index') }}>Asset types</a>
							<a class="dropdown-item" href={{ route('department.index') }}>Departments</a>
							<a class="dropdown-item" href={{ route('location.index') }}>Locations</a>
							<a class="dropdown-item" href={{ route('uom.index') }}>Units of measure</a>
						</div>
					</li>
					@endCan

					@can('show-gate')
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        {{ __('messages.gate_controls') }}
						</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
							<a class="dropdown-item" href={{ route('gate.index') }}>Gate log</a>
							<a class="dropdown-item" href={{ route('gate.open') }}>Open</a>
							<a class="dropdown-item" href={{ route('gate.close') }}>Close</a>
							<div class="dropdown-divider"></div>
							<div class="dropright dropdown-submenu">
                                                                <a class="dropdown-item dropdown-toggle" href="#" role="button" data-toggle="dropdown">{{ __('messages.open_for') }}</a>
								<div class="dropdown-menu">
									<a class="dropdown-item" href={{ route('gate.open', 1) }}>1 hour</a>
									<a class="dropdown-item" href={{ route('gate.open', 2) }}>2 hours</a>
									<a class="dropdown-item" href={{ route('gate.open', 3) }}>3 hours</a>
									<a class="dropdown-item" href={{ route('gate.open', 4) }}>4 hours</a>
									<a class="dropdown-item" href={{ route('gate.open', 5) }}>5 hours</a>
								</div>
							</div>
						</div>
					</li>
					@endcan
					@can('show-dashboard')
					<li class="nav-item">
                                                <a class="nav-link" href={{ route('dashboard.index') }}>{{ __('messages.dashboards') }}</a>
					</li>
					@endcan
					@can('show-admin-menu')
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        {{ __('messages.admin') }}
						</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
							<a class="dropdown-item" href={{ route('permission.index') }}>Permissions</a>
							<a class="dropdown-item" href={{ route('role.index') }}>Roles</a>
							<a class="dropdown-item" href={{ route('user.index') }}>Users</a>
                                                        <a class="dropdown-item" href={{ route('language.index') }}>Language</a>

                                                        <a class="dropdown-item" href={{ route('room.index') }}>{{ __('messages.rooms') }}</a>

							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href={{ route('donation_type.index') }}>Donation types</a>
							@can('admin-mailgun')
							<a class="dropdown-item" href={{ route('mailgun.index') }}>Mailgun messages</a>
							@endcan
							<a class="dropdown-item" href={{ route('snippet.index') }}>Snippets</a>
							<a class="dropdown-item" href={{ route('custom_form.index') }}>Squarespace Custom Forms</a>
							<a class="dropdown-item" href={{ route('inventory.index') }}>Squarespace Inventory</a>
							<div class="dropdown-divider"></div>
							@can('superuser')
							<a class="dropdown-item" href={{ route('activity') }}>Activity log</a>
							<a class="dropdown-item" href={{ route('audit.index') }}>Audit log</a>
							<a class="dropdown-item" href={{ route('admin.config.index') }}>Configuration</a>
							@endcan
						</div>
					</li>
					@endCan
				</ul>
				@can('show-contact')
				{{ html()->form('GET', action([\App\Http\Controllers\SearchController::class, 'getuser'], ))->class('form-inline my-2 my-lg-0')->open() }}
                                {{ html()->text('contactSearch', '')->id('contactSearch')->placeholder(__('messages.find_contact_placeholder'))->class('form-control mr-sm-2') }}
                                {{ html()->hidden('response', '')->id('response') }}
                                {{ html()->submit(__('messages.find_person_button'))->class('btn btn-outline-success my-2 my-sm-0')->id('btnSearch')->style('display:none') }}
                                <a href="{{action([\App\Http\Controllers\SearchController::class, 'search'])}}">{{ html()->img(asset('images/search.png'), __('messages.advanced_search'))->attribute('title', __('messages.advanced_search'))->class('btn btn-link') }}</a>
				{{ html()->form()->close() }}
				@endcan
				@auth
				<div class="dropdown">
					<div class="dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" title="{{ Auth::user()->name }}" class="rounded-circle avatar">
					</div>
					<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href={{ route('logout') }}>{{ __('messages.logout') }}</a>
					</div>
				</div>


				@else
                                <a href={{ route('login') }}>
                                        {{ __('messages.login') }}
                                </a>
				@endauth
			</div>
		</nav>
	</div>

	@if (isset($errors) && count($errors) > 0)
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	@endif
	<div class="container">
		@yield('content')
		<hr />
		<div class="footer row">
			<div class="col-lg-12 text-center">
				<p>
					<a href='https://shorturl.at/yzVGj' target='_blank'>
						Avenida Viscondes da Torre 80<br />
						4730-579 Soutelo<br />
					</a>
					(940) 321-6020<br />
					<a href='https://pontosj.pt/casadatorre/' target='_blank'>Casadatorre.org</a>
				</p>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		$("#contactSearch").autocomplete({
			source: "{{ url('search/autocomplete') }}",
			minLength: 3,
			autoFocus: true,
			select: function(event, ui) {
				$('#contactSearch').val(ui.item.value);
				$('#response').val(ui.item.id);
				$('#btnSearch').click();
			}
		});
		console.log($("#q"));
	</script>
</body>

</html>