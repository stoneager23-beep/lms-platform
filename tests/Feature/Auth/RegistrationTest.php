<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new student users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'student',
    ]);

    $this->assertAuthenticated();

    $response->assertRedirect(
        route('dashboard', absolute: false)
    );

    $this->assertDatabaseHas('users', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'role' => 'student',
        'is_approved' => true,
    ]);
});

test('new instructor registration waits for admin approval', function () {
    $response = $this->post('/register', [
        'name' => 'Test Instructor',
        'email' => 'instructor@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'instructor',
    ]);

    $this->assertGuest();

    $response->assertRedirect(
        route('login', absolute: false)
    );

    $response->assertSessionHasErrors([
        'email',
    ]);

    $this->assertDatabaseHas('users', [
        'name' => 'Test Instructor',
        'email' => 'instructor@example.com',
        'role' => 'instructor',
        'is_approved' => false,
    ]);
});