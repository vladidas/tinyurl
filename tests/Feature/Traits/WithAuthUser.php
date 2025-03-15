<?php

namespace Tests\Feature\Traits;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

trait WithAuthUser
{
    protected ?Authenticatable $authUser = null;

    protected function signIn(?Authenticatable $user = null): static
    {
        $this->authUser = $user ?? User::factory()->create();
        $this->actingAs($this->authUser);

        return $this;
    }
}
