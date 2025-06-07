<div class="form-group">
    <div class="row">
        <div class="col-lg-3 col-md-4">
            {{ html()->label('Primary email:', 'primary_email_location_id') }}
            {{ html()->select('primary_email_location_id', $primary_email_locations, config('polanco.location_type.home'))->class('form-control') }}
        </div>
    </div>
</div>
<div class="form-group">
    <ul role="tablist" class="nav nav-tabs">
        <li class="nav-item" role="tab">
                <a class="nav-link active" data-toggle="tab" role="tab" href="#email_home">
                <i class="fa fa-home"></i>
                <label>{{ __('messages.home_tab') }}</label>
            </a>
        </li>
        <li class="nav-item" role="tab">
                <a class="nav-link" data-toggle="tab" role="tab" href="#email_work">
                <i class="fa fa-archive"></i>
                <label>{{ __('messages.work_tab') }}</label>
            </a>
        </li>
        <li class="nav-item" role="tab">
                <a class="nav-link" data-toggle="tab" role="tab" href="#email_other">
                <i class="fa fa-cog"></i>
                <label>{{ __('messages.other_tab') }}</label>
            </a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div aria-labelledby="tab1-tab" id="email_home" class="tab-pane fade show active" role="tabpanel">
            <h4>{{ __('messages.home_email_title') }}</h4>
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    {{ html()->label('Email:', 'email_home') }}
                    {{ html()->text('email_home')->class('form-control') }}
                </div>
            </div>
        </div>
        <div aria-labelledby="tab2-tab" id="email_work" class="tab-pane fade" role="tabpanel">
            <h4>{{ __('messages.work_email_title') }}</h4>
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    {{ html()->label('Email:', 'email_work') }}
                    {{ html()->text('email_work')->class('form-control') }}
                </div>
            </div>
        </div>
        <div aria-labelledby="tab3-tab" id="email_other" class="tab-pane fade" role="tabpanel">
            <h4>{{ __('messages.other_email_title') }}</h4>
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    {{ html()->label('Email:', 'email_other') }}
                    {{ html()->text('email_other')->class('form-control') }}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-group form-check">
    {{ html()->checkbox('do_not_email', 0, 1)->class('form-check-input')->id('do_not_email') }}
    {{ html()->label(__('messages.do_not_email'), 'do_not_email')->class('form-check-label')->id('do_not_email') }}
</div>
