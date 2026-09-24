<?php

declare(strict_types=1);

namespace App\Extensions\Session;

use Illuminate\Session\DatabaseSessionHandler as BaseDatabaseSessionHandler;

final class DatabaseSessionHandler extends BaseDatabaseSessionHandler
{
    /**
     * Add the user information to the session payload.
     *
     * @param  array<string, mixed>  $payload
     */
    protected function addUserInformation(
        &$payload // @pest-ignore-type
    ): static {
        // Do not add user_id to payload
        return $this;
    }

    /**
     * Add the request information to the session payload.
     *
     * @param  array<string, mixed>  $payload
     */
    protected function addRequestInformation(
        &$payload // @pest-ignore-type
    ): static {
        // Do not add ip_address and user_agent to payload
        return $this;
    }
}
