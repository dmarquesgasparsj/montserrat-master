<?php

namespace App\Http\Controllers;

use App\Http\Requests\SnippetTestRequest;
use App\Http\Requests\StoreSnippetRequest;
use App\Http\Requests\UpdateSnippetRequest;
use App\Mail\RetreatantBirthday;
use App\Mail\RetreatConfirmation;
use App\Mail\SquarespaceOrderFulfillment;
use Auth;
use Faker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SnippetController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $this->authorize('show-snippet');

        $titles = \App\Models\Snippet::groupBy('title')->with('language')->orderBy('title')->pluck('title', 'title');
        $snippets = \App\Models\Snippet::orderBy('title')->with('language')->orderBy('locale')->orderBy('label')->get();

        return view('admin.snippets.index', compact('snippets', 'titles'));
    }

    public function index_type($title = null): View
    {
        $this->authorize('show-snippet');

        $titles = \App\Models\Snippet::groupBy('title')->with('language')->orderBy('title')->pluck('title', 'title');
        $snippets = \App\Models\Snippet::whereTitle($title)->with('language')->orderBy('title')->orderBy('locale')->orderBy('label')->get();

        return view('admin.snippets.index', compact('snippets', 'titles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create-snippet');
        $locales = \App\Models\Language::whereIsActive(1)->orderBy('label')->pluck('label', 'name');

        return view('admin.snippets.create', compact('locales'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSnippetRequest $request): RedirectResponse
    {
        $this->authorize('create-snippet');

        $snippet = new \App\Models\Snippet;
        $snippet->title = $request->input('title');
        $snippet->label = $request->input('label');
        $snippet->locale = $request->input('locale');
        $snippet->snippet = $request->input('snippet');

        $snippet->save();

        flash('Snippet: <a href="'.url('/admin/snippet/'.$snippet->id).'">'.$snippet->title.' - '.$snippet->label.'</a> added')->success();

        return Redirect::action([self::class, 'index']);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): View
    {
        $this->authorize('show-snippet');

        $snippet = \App\Models\Snippet::findOrFail($id);

        return view('admin.snippets.show', compact('snippet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $this->authorize('update-snippet');

        $snippet = \App\Models\Snippet::findOrFail($id);
        $locales = \App\Models\Language::whereIsActive(1)->orderBy('label')->pluck('label', 'name');

        return view('admin.snippets.edit', compact('snippet', 'locales')); //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSnippetRequest $request, int $id): RedirectResponse
    {
        $this->authorize('update-snippet');

        $snippet = \App\Models\Snippet::findOrFail($id);

        $snippet->title = $request->input('title');
        $snippet->label = $request->input('label');
        $snippet->locale = $request->input('locale');
        $snippet->snippet = $request->input('snippet');

        $snippet->save();

        flash('Snippet: <a href="'.url('/admin/snippet/'.$snippet->id).'">'.$snippet->title.' - '.$snippet->label.'</a> updated')->success();

        return Redirect::action([self::class, 'show'], $snippet->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->authorize('delete-snippet');
        $snippet = \App\Models\Snippet::findOrFail($id);

        \App\Models\Snippet::destroy($id);

        flash('Snippet: '.$snippet->title.' - '.$snippet->label.' deleted')->warning()->important();

        return Redirect::action([self::class, 'index']);
    }

    public function snippet_test(SnippetTestRequest $request): RedirectResponse
    {
        $this->authorize('show-snippet');

        $title = $request->input('title');
        $email = $request->input('email');
        $language = $request->input('language');

        $faker = Faker\Factory::create();
        if (empty($email)) {
            $email = config('polanco.admin_email');
        }

        switch ($title) {
            case 'birthday':
                // dd($title,$email,$language);
                // generate and store snippets
                $snippets = \App\Models\Snippet::whereTitle('birthday')->get();
                foreach ($snippets as $snippet) {
                    $decoded = html_entity_decode($snippet->snippet, ENT_QUOTES | ENT_XML1);
                    Storage::put('views/snippets/'.$snippet->title.'/'.$snippet->locale.'/'.$snippet->label.'.blade.php', $decoded);
                }
                // create fake person
                $receiver = collect();
                $receiver->id = $faker->numberBetween(100, 900);
                $receiver->first_name = $faker->firstName();
                $receiver->nick_name = $faker->firstName();
                $receiver->display_name = $receiver->first_name.' '.$faker->lastName();
                $receiver->birth_date = $faker->date();
                $receiver->email = $faker->safeEmail();
                $receiver->preferred_language = $language;
                if (! empty($email)) {
                    try {
                        Mail::to($email)->queue(new RetreatantBirthday($receiver));
                        flash('Birthday test email successfully sent to: '.$email)->success();
                    } catch (\Exception $e) {
                        // dd($e);
                        flash('Sending of birthday test email to: '.$email.' failed')->error();
                    }
                }
                break;

            case 'agc_acknowledge':
                // not needed - can test via UI by viewing an AGC letter
                $msg = 'AGC acknowledgment snippets can be tested by viewing an actual letter from the <a href="'.url('/agc').'">AGC list</a>';
                flash($msg)->warning();
                break;
            case 'event-confirmation':
                $snippets = \App\Models\Snippet::whereTitle('event-confirmation')->get();
                foreach ($snippets as $snippet) {
                    $decoded = html_entity_decode($snippet->snippet, ENT_QUOTES | ENT_XML1);
                    Storage::put('views/snippets/'.$snippet->title.'/'.$snippet->locale.'/'.$snippet->label.'.blade.php', $decoded);
                }
                switch ($language) {
                    case 'es_ES':
                        $registration = \App\Models\Registration::with('event.event_type', 'contact')
                            ->whereHas('event', function ($q) {
                                $q->whereEventTypeId(config('polanco.event_type.ignatian'));
                            })
                            ->whereHas('contact', function ($q) use ($language) {
                                $q->wherePreferredLanguage($language);
                            })
                            ->first();
                        break;
                    default: // en_US
                        $registration = \App\Models\Registration::with('event.event_type', 'contact')
                            ->whereHas('event', function ($q) {
                                $q->whereEventTypeId(config('polanco.event_type.ignatian'));
                            })
                            ->whereHas('contact', function ($q) {
                                $q->wherePreferredLanguage('en_US');
                            })
                            ->first();
                }
                if (! empty($email)) {
                    try {
                        Mail::to($email)->queue(new RetreatConfirmation($registration));
                        flash('Retreat confirmation test email successfully sent to: '.$email)->success();
                    } catch (\Exception $e) {
                        // dd($e);
                        flash('Sending of retreat confirmation test email to: '.$email.' failed')->error();
                    }
                }
                break;
            case 'squarespace_order_fulfillment':
                $snippets = \App\Models\Snippet::whereTitle($title)->get();
                foreach ($snippets as $snippet) {
                    $decoded = html_entity_decode($snippet->snippet, ENT_QUOTES | ENT_XML1);
                    Storage::put('views/snippets/'.$snippet->title.'/'.$snippet->locale.'/'.$snippet->label.'.blade.php', $decoded);
                }

                // TODO:: actually select an order for a Spanish speaker
                switch ($language) {
                    case 'es_ES':
                        $order = \App\Models\SquarespaceOrder::whereIsProcessed(1)
                            ->whereNotNull('event_id')
                            ->whereNotNull('contact_id')
                            ->with('retreatant', 'event')->first();
                        $order = \App\Models\SquarespaceOrder::whereIsProcessed(1)
                            ->whereNotNull('event_id')
                            ->whereNotNull('contact_id')
                            ->whereHas('retreatant', function ($q) {
                                $q->wherePreferredLanguage('es_ES');
                            })
                            ->with('retreatant', 'event')
                            ->first();
                        break;
                    default: // en_US
                        $order = \App\Models\SquarespaceOrder::whereIsProcessed(1)
                            ->whereNotNull('event_id')
                            ->whereNotNull('contact_id')
                            ->with('retreatant', 'event')->first();
                }
                // dd($order->event_start_date->locale('es_ES')->translatedFormat("l d \de F \de\l Y"));
                if (! empty($email)) {
                    try {
                        Mail::to($email)->queue(new SquarespaceOrderFulfillment($order));
                        flash('Retreat confirmation test email successfully sent to: '.$email)->success();
                    } catch (\Exception $e) {
                        // dd($e, $order);
                        flash('Sending of retreat confirmation test email to: '.$email.' failed')->error();
                    }
                }
                break;
            default:
                flash(__('messages.unknown_snippet_test', ['title' => $title]))->error();
                break;
        }

        return Redirect::action([self::class, 'index']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function test($title = null, $email = null, $language = 'en_US'): View
    {
        $this->authorize('show-snippet');
        $titles = \App\Models\Snippet::groupBy('title')->orderBy('title')->pluck('title', 'title');
        $languages = \App\Models\Language::whereIsActive(1)->orderBy('label')->pluck('label', 'name');
        if (empty($email)) {
            $email = Auth::user()->email;
        }

        return view('admin.snippets.test', compact('language', 'email', 'title', 'titles', 'languages'));
    }
}
