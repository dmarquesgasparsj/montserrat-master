<?php

namespace Tests\Unit;

use App\Models\Roomstate;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class RoomstateFactoryTest extends TestCase
{
    #[Test]
    public function factory_creates_valid_roomstate(): void
    {
        $roomstate = Roomstate::factory()->create();

        $this->assertNotNull($roomstate->statechange_at);
        $this->assertNotNull($roomstate->statusfrom);
        $this->assertNotNull($roomstate->statusto);
    }
}
