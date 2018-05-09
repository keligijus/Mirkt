<?php

namespace Tests\Feature\Controllers\Home;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomeControllerTest extends TestCase
{
    private $route;
    private $method;

    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->route = '/';
        $this->method = 'get';
    }

    /**
     * Test base view
     */
    public function testIndex(): void
    {
        // Make request
        $request = $this->json($this->method, $this->route);

        // Check with view is returned
        $request->assertViewIs('app');

        // Is request was success
        $request->assertSuccessful();
    }
}