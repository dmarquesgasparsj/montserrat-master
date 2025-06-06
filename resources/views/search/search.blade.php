@extends('template')
@section('content')

<div>
<div class="jumbotron text-left">
    <div class="panel panel-default">

        <div class='panel-heading'>
            <h1><strong>{{ __('messages.search_contacts_title') }}</strong></h1>
        </div>

        {{ html()->form('GET', 'results')->open() }}

        <div class="panel-body">
            <div class='panel-heading'>
                <h2>
                    <span>{{ __('messages.name') }}</span>
                    <span>{{ html()->input('image', 'search')->class('btn btn-outline-dark pull-right')->attribute('src', asset('images/submit.png')) }}</span>
                </h2>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    {{ html()->label(__('messages.title_label'), 'prefix_id')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->select('prefix_id', $prefixes)->class('form-control') }}
                    </div>

                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.first_label'), 'first_name')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->text('first_name')->class('form-control') }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.middle_label'), 'middle_name')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->text('middle_name')->class('form-control') }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.last_label'), 'last_name')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                    {{ html()->text('last_name')->class('form-control') }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.suffix_label'), 'suffix_id')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                    {{ html()->select('suffix_id', $suffixes)->class('form-control') }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.nickname_label'), 'nick_name')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                    {{ html()->text('nick_name')->class('form-control') }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.display_name_label'), 'display_name')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                    {{ html()->text('display_name')->class('form-control') }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.sort_name_label'), 'sort_name')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                    {{ html()->text('sort_name')->class('form-control') }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.contact_type_label'), 'contact_type')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                    {{ html()->select('contact_type', $contact_types, 0)->class('form-control') }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.subcontact_type_label'), 'subcontact_type')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                    {{ html()->select('subcontact_type', $subcontact_types, 0)->class('form-control') }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.has_avatar_label'), 'has_avatar')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                    {{ html()->checkbox('has_avatar', 0, 1)->class('') }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.has_attachment_label'), 'has_attachment')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                    {{ html()->checkbox('has_attachment', 0, 1)->class('') }}
                    </div>
                </div>
                <div class="form-group">
                    <div class="clearfix"> </div>
                    {{ html()->label(__('messages.attachment_description_label'), 'attachment_description')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                    {{ html()->text('attachment_description')->class('form-control') }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.phone_label'), 'phone')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->text('phone')->class('form-control') }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.do_not_phone_label'), 'do_not_phone')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->select('do_not_phone', [null => __('messages.not_applicable'), '1' => __('messages.yes'), '0' => __('messages.no')]) }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.do_not_sms_label'), 'do_not_sms')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->select('do_not_sms', [null => __('messages.not_applicable'), '1' => __('messages.yes'), '0' => __('messages.no')]) }}
                    </div>
                </div>

                <div class="form-group">
                    {{ html()->label(__('messages.email_label'), 'email')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->text('email')->class('form-control') }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.do_not_email_label'), 'do_not_email')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->select('do_not_email', [null => __('messages.not_applicable'), '1' => __('messages.yes'), '0' => __('messages.no')]) }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.address_label'), 'street_address')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->text('street_address')->class('form-control') }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.city_label'), 'city')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->text('city')->class('form-control') }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.state_label'), 'state_province_id')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->select('state_province_id', $states)->class('form-control') }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.zip_label'), 'postal_code')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->text('postal_code')->class('form-control') }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.country_label'), 'country_id')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->select('country_id', $countries)->class('form-control') }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.do_not_mail_label'), 'do_not_mail')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->select('do_not_mail', [null => __('messages.not_applicable'), '1' => __('messages.yes'), '0' => __('messages.no')]) }}
                    </div>
                </div>

                <div class="form-group">
                    {{ html()->label(__('messages.website_label'), 'url')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->text('url')->class('form-control') }}
                    </div>
                </div>
            </div>
            <div class='panel-heading' style="background-color: lightcoral;"><h2>{{ __('messages.emergency_contact_information_title') }}</h2></div>
            <div class="panel-body" style="background-color: lightcoral;">
                <div class="form-group">
                    {{ html()->label(__('messages.emergency_contact_name_label'), 'emergency_contact_name')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->text('emergency_contact_name')->class('form-control') }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.emergency_contact_relationship_label'), 'emergency_contact_relationship')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->text('emergency_contact_relationship')->class('form-control') }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.emergency_contact_phone_label'), 'emergency_contact_phone')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->text('emergency_contact_phone')->class('form-control') }}
                    </div>
                </div>
            </div>
            <div class='panel-heading'><h2>{{ __('messages.demographics_title') }}</h2></div>
            <div class="panel-body">
               <div class="form-group">
                    {{ html()->label(__('messages.gender_label'), 'gender_id')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->select('gender_id', $genders, 0)->class('form-control') }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.birth_date_label'), 'birth_date')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->text('birth_date')->class('form-control flatpickr-date') }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.religion_label'), 'religion_id')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->select('religion_id', $religions, 0)->class('form-control') }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.occupation_label'), 'occupation_id')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->select('occupation_id', $occupations, 0)->class('form-control') }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.parish_label'), 'parish_id')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->select('parish_id', $parish_list, 0)->class('form-control') }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.ethnicity_label'), 'ethnicity_id')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->select('ethnicity_id', $ethnicities, 0)->class('form-control') }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.languages_label'), 'languages')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->multiselect('languages[]', $languages)->id('languages')->class('form-control')->style('width: auto; font-size: inherit;') }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.preferred_language_label'), 'preferred_language_id')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->select('preferred_language_id', $languages, 0)->class('form-control') }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.referral_sources_label'), 'referrals')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->multiselect('referrals[]', $referrals)->id('referrals')->class('form-control')->style('width: auto; font-size: inherit;') }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.deceased_date_label'), 'deceased_date')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->text('deceased_date')->class('form-control flatpickr-date') }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.is_deceased_label'), 'is_deceased')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->checkbox('is_deceased', NULL, 1)->class('') }}
                    </div>
                </div>
            </div>
            <div class='panel-heading'><h2>{{ __('messages.health_notes_title') }}</h2></div>
            <div class="panel-body">
                <div class="form-group">
                    {{ html()->label(__('messages.health_notes_label'), 'note_health')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->text('note_health')->class('form-control') }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.dietary_notes_label'), 'note_dietary')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->text('note_dietary')->class('form-control') }}
                    </div>
                </div>
            </div>
            <div class='panel-heading'><h2>{{ __('messages.general_notes_title') }}</h2></div>
            <div class="panel-body">
                <div class="form-group">
                    {{ html()->label(__('messages.general_notes_label'), 'note_general')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->text('note_general')->class('form-control') }}
                    </div>
                </div>
                <div class="form-group">
                    {{ html()->label(__('messages.room_preference_label'), 'note_room_preference')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->text('note_room_preference')->class('form-control') }}
                    </div>
                </div>
            </div>
            <div class='panel-heading'><h2>{{ __('messages.touchpoints_title') }}</h2></div>
            <div class="form-group">
                    <div class="clearfix"> </div>
                        {{ html()->label(__('messages.note_label'), 'touchpoint_notes')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->text('touchpoint_notes')->class('form-control') }}
                    </div>
                </div>
            <div class="form-group">
                    <div class="clearfix"> </div>
                        {{ html()->label(__('messages.touched_at_label'), 'touched_at')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->text('touched_at')->class('form-control flatpickr-date bg-white') }}
                    </div>
                </div>
            <div class='panel-heading'><h2>{{ __('messages.groups_and_relationships_title') }}</h2></div>
            <div class="panel-body">
                <div class="form-group">
                    {{ html()->label(__('messages.groups_label'), 'groups')->class('control-label col-sm-3') }}
                    <div class="col-sm-8">
                        {{ html()->multiselect('groups[]', $groups)->id('groups')->class('form-control')->style('width: auto; font-size: inherit;') }}
                    </div>
                </div>
            </div>
        </div>

   </div>
    {{ html()->form()->close() }}
</div>


@stop
