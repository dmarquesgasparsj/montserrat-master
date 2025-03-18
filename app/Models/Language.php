<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Language extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    protected $table = 'language';


    // Criado e Editado por DMG

    /**
     * Set the application locale.
     *
     * @param string $locale
     * @return void
     */
    public static function setLocale($locale)
    {
        if (in_array($locale, ['en', 'es', 'pt'])) {
            session(['applocale' => $locale]);
            app()->setLocale($locale);
        }
    }

    /**
     * Get the current application locale.
     *
     * @return string
     */
    public static function getLocale()
    {
        return session('applocale', config('app.locale'));
    }
}