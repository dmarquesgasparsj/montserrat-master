<?php

namespace Tests\Feature;

use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

final class LanguageSwitchTest extends TestCase
{
    #[Test]
    public function lang_switch_updates_locale_for_subsequent_requests(): void
    {
        // switch to Spanish and follow redirect back to welcome
        $this->withHeader('referer', route('welcome'))
            ->get(route('lang.switch', ['language' => 'es']));

        $response = $this->get(route('welcome'));
        $response->assertSee('¡Bienvenido a Polanco');

        $response = $this->get(route('dashboard.index'));
        $response->assertSee('Índice del Tablero');
    }
}
