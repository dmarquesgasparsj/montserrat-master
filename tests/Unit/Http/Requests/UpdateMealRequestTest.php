<?php

namespace Tests\Unit\Http\Requests;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Requests\UpdateMealRequest
 */
final class UpdateMealRequestTest extends TestCase
{
    /** @var \App\Http\Requests\UpdateMealRequest */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new \App\Http\Requests\UpdateMealRequest();
    }

    #[Test]
    public function authorize(): void
    {
        $this->assertTrue($this->subject->authorize());
    }

    #[Test]
    public function rules(): void
    {
        $this->assertEquals([
            'name' => 'required|string|max:255',
            'description' => 'string|nullable',
            'price' => 'required|numeric|min:0',
            'calories' => 'integer|nullable|min:0',
        ], $this->subject->rules());
    }

    #[Test]
    public function messages(): void
    {
        $this->assertEquals([], $this->subject->messages());
    }
}
