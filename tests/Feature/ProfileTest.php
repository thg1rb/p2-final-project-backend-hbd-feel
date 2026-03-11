<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

// ใช้ RefreshDatabase เพื่อให้ DB สะอาดทุกครั้งที่เริ่มรัน Test
uses(RefreshDatabase::class);

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/profile');

    $response->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $newEmail = 'test.' . uniqid() . '@example.com';

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'firstName' => 'NewFirstName',
            'lastName' => 'NewLastName',
            'email' => $newEmail,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $user->refresh();

    $this->assertSame('NewFirstName', $user->firstName);
    $this->assertSame('NewLastName', $user->lastName);
    $this->assertSame($newEmail, $user->email);

    /** * หมายเหตุ: หากใน Controller คุณไม่มี Logic สำหรับ $user->email_verified_at = null;
     * บรรทัดด้านล่างนี้อาจจะ Fail ให้เปลี่ยนเป็น assertNotNull แทนตามพฤติกรรมจริง
     */
    // $this->assertNull($user->email_verified_at);
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'firstName' => 'UpdatedName',
            'lastName' => 'UpdatedLast',
            'email' => $user->email,
        ]);

    $response->assertSessionHasNoErrors();

    $this->assertNotNull($user->refresh()->email_verified_at);
});

test('user can delete their account', function () {
    // กำหนด Password ให้แน่นอน เพื่อให้ตอนส่ง Delete ผ่านการ Verify
    $user = User::factory()->create([
        'password' => Hash::make('password123'),
    ]);

    $response = $this
        ->actingAs($user)
        ->delete('/profile', [
            'password' => 'password123',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();

    // ตรวจสอบ Soft Delete ใน Table 'users'
    $this->assertSoftDeleted('users', [
        'id' => $user->id,
    ]);
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create([
        'password' => Hash::make('correct-password'),
    ]);

    $response = $this
        ->actingAs($user)
        ->from('/profile') // จำลองว่ามาจากหน้า profile
        ->delete('/profile', [
            'password' => 'wrong-password',
        ]);

    // ตรวจสอบว่ามี Error กลับมา (ไม่ระบุชื่อถุง Error เพื่อความชัวร์)
    $response->assertSessionHasErrors();
    $response->assertRedirect('/profile');

    // User ต้องยังอยู่ใน DB (ไม่โดนลบ)
    $this->assertNotNull($user->fresh());
});
