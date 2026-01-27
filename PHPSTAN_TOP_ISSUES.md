PHPStan Top Issues (initial triage)

This file lists the highest-priority static analysis findings reported by PHPStan (Larastan) and suggested remediation steps so we can fix them incrementally.

1) Undefined / wrong method calls
- Example: `Laravel\Socialite\Contracts\Provider::setHttpClient()` reported as undefined in `AuthController`.
- Fix: Use the documented Socialite API (set the Guzzle client via `Socialite::getFacadeRoot()->withHttpClient()` or configure Guzzle before calling). Add type hints where necessary.

2) `getTimestamp()` called on string (SimpleSsoController)
- Example: `$user->updated_at` or other date fields may be strings; ensure casts in model (`$casts`) include `datetime` so properties are Carbon instances.
- Fix: Update model casts (e.g., `updated_at`, `last_login_at`) to `datetime` or guard with `Carbon::parse()`.

3) `foreach` on a string / non-iterable
- Example: iterating over `$user->attributes` when it can be `null` or string.
- Fix: Ensure `$casts` for `attributes` is `array` in `User` model; add null-checks and defaults `array_filter((array) $user->attributes)`.

4) Missing return / parameter type hints across controllers and commands
- Many controller methods have no return types; add `: \Illuminate\Http\Response|\Illuminate\Contracts\Support\Renderable|\Illuminate\Http\JsonResponse` or `: \Illuminate\Http\Response` as appropriate.
- Fix incrementally: add type hints for critical public methods (controllers, commands, console handlers).

5) Property type mismatches in `User` model
- Properties like `is_active`, `must_change_password`, `attributes` are used as booleans/arrays but defined or inferred as `int|string`.
- Fix: Update `$casts` and `$fillable` and docblocks in `User` to reflect boolean/array types.

6) Model relation return generics
- E.g., `roles()`, `auditLogs()` missing generic types.
- Fix: Add proper return types like `: \Illuminate\Database\Eloquent\Relations\BelongsToMany` and optionally phpdoc generics to satisfy Larastan.

7) Passport HasApiTokens contract issue
- `HasApiTokens` expects a specific contract; add `implements` or adjust usage per Larastan suggestion.

8) Mail classes missing typed properties and constructor params
- Add typed properties and parameter types in `NewDeviceLoginNotification` and `VerifyEmail` mail classes.

9) Misc: seeders and migrations missing return types
- These are lower priority; add `: void` to `run()` methods.

Suggested plan to fix (staged):
- Stage A (safety & correctness): ensure model casts for dates/arrays/booleans (`User`, SSO models). Fix obvious `foreach` and `getTimestamp()` issues.
- Stage B (API correctness): fix undefined method usages (Socialite), add missing parameter/return types on controllers and console commands used by core flows.
- Stage C (polish): add typed properties on Mail classes, add phpdoc generics for relations, clean up seeders and other smaller issues.

I'll prepare a PR branch with Stage A fixes (model casts and a few defensive guards) so tests remain green and static analysis noise is reduced. After that we can iterate on Stage B.

Files to change in Stage A (initial):
- `app/Models/User.php` - ensure casts: `updated_at`, `last_login_at`, `attributes` => `array`, `is_active`/`must_change_password` => boolean
- `app/Models/SsoAuthCode.php`, `SsoAccessToken.php`, `SsoRefreshToken.php` - ensure `revoked` cast boolean (already done), `expires_at` cast datetime
- `app/Http/Controllers/SimpleSsoController.php` - guard `foreach` and timestamp usages


Next: create PR branch `phpstan/fix-stage-a`, apply the Stage A changes, run PHPStan and tests, and push.

If you approve, I'll create the branch, implement Stage A, run tests and PHPStan (fast checks), and open the PR.
