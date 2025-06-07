<div class="form-group">
    <ul role="tablist" class="nav nav-tabs">
        <li class="nav-item" role="tab">
            <a class="nav-link active" data-toggle="tab" role="tab" href="#url_main">
                <i class="fa fa-home"></i>
                <label>{{ __('messages.personal_tab') }}</label>
            </a>
        </li>
        <li class="nav-item" role="tab">
            <a class="nav-link" data-toggle="tab" role="tab" href="#url_work">
                <i class="fa fa-archive"></i>
                <label>{{ __('messages.work_tab') }}</label>
            </a>
        </li>
        <li class="nav-item" role="tab">
            <a class="nav-link" data-toggle="tab" role="tab" href="#url_facebook">
                <i class="fab fa-facebook"></i>
                <label>{{ __('messages.facebook_tab') }}</label>
            </a>
        </li>
        <li class="nav-item" role="tab">
            <a class="nav-link" data-toggle="tab" role="tab" href="#url_google">
                <i class="fab fa-google-plus-square"></i>
                <label>{{ __('messages.google_tab') }}</label>
            </a>
        </li>
        <li class="nav-item" role="tab">
            <a class="nav-link" data-toggle="tab" role="tab" href="#url_instagram">
                <i class="fab fa-instagram"></i>
                <label>{{ __('messages.instagram_tab') }}</label>
            </a>
        </li>
        <li class="nav-item" role="tab">
            <a class="nav-link" data-toggle="tab" role="tab" href="#url_linkedin">
                <i class="fab fa-linkedin"></i>
                <label>{{ __('messages.linkedin_tab') }}</label>
            </a>
        </li>
        <li class="nav-item" role="tab">
            <a class="nav-link" data-toggle="tab" role="tab" href="#url_twitter">
                <i class="fab fa-twitter-square"></i>
                <label>{{ __('messages.twitter_tab') }}</label>
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div id="url_main" class="tab-pane fade show active" role="tabpanel">
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    {{ html()->label(__('messages.personal_tab').':', 'url_main') }}
                    {{ html()->text('url_main')->class('form-control') }}
                </div>
            </div>
        </div>
        <div id="url_work" class="tab-pane fade" role="tabpanel">
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    {{ html()->label(__('messages.work_tab').':', 'url_work') }}
                    {{ html()->text('url_work')->class('form-control') }}
                </div>
            </div>
        </div>
        <div id="url_facebook" class="tab-pane fade" role="tabpanel">
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    {{ html()->label(__('messages.facebook_tab').':', 'url_facebook') }}
                    {{ html()->text('url_facebook')->class('form-control') }}
                </div>
            </div>
        </div>
        <div id="url_google" class="tab-pane fade" role="tabpanel">
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    {{ html()->label(__('messages.google_tab').':', 'url_google') }}
                    {{ html()->text('url_google')->class('form-control') }}
                </div>
            </div>
        </div>
        <div id="url_instagram" class="tab-pane fade" role="tabpanel">
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    {{ html()->label(__('messages.instagram_tab').':', 'url_instagram') }}
                    {{ html()->text('url_instagram')->class('form-control') }}
                </div>
            </div>
        </div>
        <div id="url_linkedin" class="tab-pane fade" role="tabpanel">
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    {{ html()->label(__('messages.linkedin_tab').':', 'url_linkedin') }}
                    {{ html()->text('url_linkedin')->class('form-control') }}
                </div>
            </div>
        </div>
        <div id="url_twitter" class="tab-pane fade" role="tabpanel">
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    {{ html()->label(__('messages.twitter_tab').':', 'url_twitter') }}
                    {{ html()->text('url_twitter')->class('form-control') }}
                </div>
            </div>
        </div>
    </div>
</div>