<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstructorApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_dashboard()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
    }

    public function test_admin_can_access_pending_instructors_list()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $instructor = User::factory()->create(['role' => 'instructor', 'is_approved' => false]);

        $response = $this->actingAs($admin)->get(route('admin.instructors.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.instructors.index');
        $response->assertSee($instructor->name);
    }

    public function test_admin_can_approve_instructor()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $instructor = User::factory()->create(['role' => 'instructor', 'is_approved' => false]);

        $response = $this->actingAs($admin)->post(route('admin.instructors.approve', $instructor));

        $response->assertRedirect(route('admin.instructors.index'));
        $this->assertTrue($instructor->fresh()->is_approved);
    }
}
