<?php

namespace Tests\Unit\Http\Requests;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class StoreMealRequestTest extends TestCase
{
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new \App\Http\Requests\StoreMealRequest;
    }

    #[Test]
    public function authorize(): void
    {
        $actual = $this->subject->authorize();
        $this->assertTrue($actual);
    }

    #[Test]
    public function rules(): void
    {
        $actual = $this->subject->rules();

        $this->assertEquals([
            'retreat_id' => 'integer|exists:event,id|required',
            'meal_date' => 'date|required',
            'meal_type' => 'string|required',
            'vegetarian_count' => 'integer|nullable',
            'gluten_free_count' => 'integer|nullable',
            'dairy_free_count' => 'integer|nullable',
        ], $actual);
    }

    #[Test]
    public function messages(): void
    {
        $actual = $this->subject->messages();

        $this->assertEquals([], $actual);
    }
}
