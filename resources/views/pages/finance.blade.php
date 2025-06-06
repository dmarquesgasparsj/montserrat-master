@extends('template')
@section('content')

<h1>{{ __('messages.finance_title') }}</h1>

@can('show-donation')

    {{ html()->a(action([\App\Http\Controllers\DonationController::class, 'index']), __('messages.finance_donations_label'))
        ->class('m-2 btn btn-outline-dark')
    }}

    {{ html()->a(action([\App\Http\Controllers\PaymentController::class, 'index']), __('messages.finance_payments_label'))
        ->class('m-2 btn btn-outline-dark')
    }}

    @can('show-squarespace-contribution')
        {{ html()->a(action([\App\Http\Controllers\SquarespaceContributionController::class, 'index']), __('messages.finance_squarespace_contributions_label'))
            ->class('m-2 btn btn-outline-dark')
        }}
    @endcan

    @can('show-stripe-payout')
        {{ html()->a(action([\App\Http\Controllers\StripePayoutController::class, 'index']), __('messages.finance_stripe_payouts_label'))
            ->class('m-2 btn btn-outline-dark')
        }}
    @endCan

    <hr />
    
    <h2>{{ __('messages.finance_reports_title') }}</h2>
    
    <div class="row bg-cover">
        <div class="col-lg-2 col-md-4">
            <h5>{{ __('messages.finance_agc_reports') }}</h5>
            {{ html()->a(action([\App\Http\Controllers\DonationController::class, 'agc'],$current_fiscal_year), __('messages.finance_agc_fy').$current_fiscal_year)
                ->class('m-2 btn btn-outline-dark')
            }}
            
            {{ html()->a(action([\App\Http\Controllers\DonationController::class, 'agc'],$current_fiscal_year-1), __('messages.finance_agc_fy').($current_fiscal_year-1))
                ->class('m-2 btn btn-outline-dark')
            }}

        </div>
    
        <div class="col-lg-2 col-md-4">
            <h5>{{ __('messages.finance_bank_reports') }}</h5>
            {{ html()->a(action([\App\Http\Controllers\PageController::class, 'finance_cash_deposit'],now()->format('Ymd')), __('messages.finance_cash_deposit_report'))
                ->class('m-2 btn btn-outline-dark')
            }}
            
            {{ html()->a(action([\App\Http\Controllers\PageController::class, 'finance_cc_deposit'],now()->format('Ymd')), __('messages.finance_cc_deposit_report'))
                ->class('m-2 btn btn-outline-dark')
            }}
        </div>
    
        <div class="col-lg-2 col-md-4">       
            <h5>{{ __('messages.finance_deposit_reports') }}</h5>
            
                {{ html()->a(action([\App\Http\Controllers\PageController::class, 'finance_deposits']), __('messages.finance_retreat_deposits_report'))
                    ->class('m-2 btn btn-outline-dark')
                }}
                {{ html()->a(action([\App\Http\Controllers\PageController::class, 'finance_reconcile_deposit_show']), __('messages.finance_unreconciled_deposits'))
                    ->class('m-2 btn btn-outline-dark')
                }}
        </div>
            
        <div class="col-lg-2 col-md-4">         
            <h5>{{ __('messages.finance_donation_reports') }}</h5>
            
                {{ html()->a(action([\App\Http\Controllers\PageController::class, 'finance_retreatdonations'],'201601'), __('messages.finance_retreat_donation_report'))
                    ->class('m-2 btn btn-outline-dark')
                }}
                {{ html()->a(action([\App\Http\Controllers\DonationController::class, 'overpaid']), __('messages.finance_overpaid_donations'))
                    ->class('m-2 btn btn-outline-dark')
                }}
        </div>
            
        <div class="col-lg-2 col-md-4">         
            <h5>{{ __('messages.finance_other_reports') }}</h5>
     
                {{ html()->a(action([\App\Http\Controllers\DonationController::class, 'mergeable']), __('messages.finance_mergeable_donations'),)
                    ->class('m-2 btn btn-outline-dark')
                }}

        </div>
    </div>
@endCan

@stop