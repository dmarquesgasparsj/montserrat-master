<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class LanguageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a list of the languages available in the application, with an introductory text.
     * @return View
     */

    // public function authorize($ability, $arguments = []): bool

    public function index(): View
    {
        $languages = config('app.languages', []); // Default to empty array
        $currentLang = session('applocale');
        $currentLang = Arr::get($languages, $currentLang);
    
        return view('admin.languages.index', compact('languages', 'currentLang'));
    }

    public function changeLanguage(Request $request)

    {
        $language = $request->input('language');
        if (in_array($language, ['en', 'es', 'pt'])) {
            Session::put('applocale', $language);
            App::setLocale($language);
        }

        return redirect()->back();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function switchLang($lang): RedirectResponse
    {
        session()->put('applocale', $lang);
        return Redirect::back();
    }
}