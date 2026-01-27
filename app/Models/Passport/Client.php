<?php

namespace App\Models\Passport;

use Laravel\Passport\Client as BaseClient;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Contracts\Auth\Authenticatable;

class Client extends BaseClient
{
    /**
     * Determine if the client should skip the authorization prompt.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $scopes
     * @return bool
     */
    public function skipsAuthorization(Authenticatable $user, array $scopes): bool
    {
        return $this->firstParty();
    }

    /**
     * Get the redirect URIs.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function redirectUris(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $redirect = $attributes['redirect'] ?? null;

                if (is_string($redirect) && !empty($redirect)) {
                    return array_map('trim', explode(',', $redirect));
                }

                if (is_array($redirect)) {
                    return $redirect;
                }

                return [];
            }
        );
    }

    /**
     * Get the grant types.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function grantTypes(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                // Check if key exists to avoid undefined index warnings
                if (!isset($attributes['grant_types'])) {
                     return ['authorization_code', 'refresh_token'];
                }

                $grantTypes = $attributes['grant_types'];

                if (is_string($grantTypes) && !empty(trim($grantTypes))) {
                    return array_map('trim', explode(',', $grantTypes));
                }

                if (is_array($grantTypes) && !empty($grantTypes)) {
                    return $grantTypes;
                }

                // Default fallback
                return ['authorization_code', 'refresh_token'];
            }
        );
    }
}
