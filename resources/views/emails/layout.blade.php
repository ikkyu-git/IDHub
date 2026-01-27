<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
      /* Simple responsive email styles */
      body { margin:0; padding:0; background:#f5f7fb; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; }
      .email-wrapper { max-width:640px; margin:24px auto; background:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 6px 18px rgba(12,18,26,0.08); }
      .header { background: #0b5cff; color: #fff; padding:20px; text-align:left; }
      .header h1 { margin:0; font-size:20px; }
      .content { padding:22px; color:#0f1724; line-height:1.5; }
      .cta { display:inline-block; background:#0b5cff; color:#fff; padding:10px 16px; border-radius:6px; text-decoration:none; }
      .footer { padding:16px; font-size:12px; color:#6b7280; text-align:center; }
      @media (max-width:480px) { .content { padding:16px } .header { padding:16px } }
    </style>
  </head>
  <body>
    <div class="email-wrapper">
      <div class="header">
        <h1>{{ $title ?? config('app.name') }}</h1>
      </div>
      <div class="content">
        {!! $slot ?? $content !!}
      </div>
      <div class="footer">
        © {{ date('Y') }} {{ config('app.name') }} — <a href="{{ url('/') }}">{{ url('/') }}</a>
      </div>
    </div>
  </body>
</html>
