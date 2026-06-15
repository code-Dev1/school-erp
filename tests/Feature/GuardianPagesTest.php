<?php

use App\Livewire\Pages\Guardian\GuardianCreate;
use App\Models\Guardian;
use App\Models\User;
use Livewire\Livewire;

test('guardian pages can be rendered', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('guardian-index'))->assertOk();
    $this->actingAs($user)->get(route('guardian-create'))->assertOk();
});

test('a guardian can be created from the guardian livewire form', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(GuardianCreate::class)
        ->set('form.name', 'Karim Rahimi')
        ->set('form.father_name', 'Rahman')
        ->set('form.phone', '0799000000')
        ->set('form.whatsapp_number', '0799000001')
        ->set('form.email', 'karim@example.com')
        ->set('form.job', 'معلم')
        ->set('form.tazkira_number', 'G-1001')
        ->set('form.address', 'کابل')
        ->set('form.status', 'active')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('guardian-index'));

    expect(Guardian::query()->where('phone', '0799000000')->exists())->toBeTrue();
});
