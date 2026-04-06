<?php

declare(strict_types=1);

namespace App\Extensions\Session;

use Illuminate\Session\DatabaseSessionHandler as IlluminateDatabaseSessionHandler;

class DatabaseSessionHandler extends IlluminateDatabaseSessionHandler
{
    /**
     * Add the user information to the session payload.
     *
     * @param  array<string, mixed>  $payload
     * @return $this
     */
    protected function addUserInformation(&$payload)
    {
        // Do not add user_id to payload
        return $this;
    }

    /**
     * Add the request information to the session payload.
     *
     * @param  array<string, mixed>  $payload
     * @return $this
     */
    protected function addRequestInformation(&$payload)
    {
        // Do not add ip_address and user_agent to payload
        return $this;
    }
}
