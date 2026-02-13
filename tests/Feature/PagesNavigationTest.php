<?php

namespace Tests\Feature;

use Tests\TestCase;

class PagesNavigationTest extends TestCase
{
    public function test_about_page_is_accessible(): void
    {
        $this->get(route('about'))
            ->assertOk()
            ->assertSee('About Us');
    }

    public function test_services_page_is_accessible(): void
    {
        $this->get(route('services'))
            ->assertOk()
            ->assertSee('Services');
    }

    public function test_market_header_shows_about_and_services_links(): void
    {
        $this->get(route('about'))
            ->assertOk()
            ->assertSee('About Us')
            ->assertSee('Services');
    }
}
