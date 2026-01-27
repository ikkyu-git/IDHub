@component('emails.layout', ['title' => 'Verify your email'])
<p>Hi {{ $user->name ?? 'there' }},</p>

<p>Thanks for creating an account. Please verify your email address by clicking the button below:</p>

<p style="text-align:center;"><a class="cta" href="{{ $verificationUrl }}">Verify Email</a></p>

<p>If you did not create an account, you can safely ignore this email.</p>

@endcomponent
