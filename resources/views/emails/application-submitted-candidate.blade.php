<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Application Submitted') }}</title>
</head>

<body style="margin:0;padding:24px;background:#f5f7fb;font-family:Arial,Helvetica,sans-serif;color:#111827;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
        style="max-width:640px;margin:0 auto;background:#ffffff;border:1px solid #e5e7eb;border-radius:8px;">
        <tr>
            <td style="padding:24px;">
                <h2 style="margin:0 0 16px 0;font-size:22px;line-height:1.3;">
                    {{ __('Application Submitted') }}
                </h2>

                <p style="margin:0 0 14px 0;">
                    {{ __('Hello :name,', ['name' => $application->candidate->name]) }}
                </p>

                <p style="margin:0 0 18px 0;">
                    {{ __('Your application has been submitted successfully.') }}
                </p>

                <div
                    style="padding:14px 16px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;margin-bottom:18px;">
                    <p style="margin:0 0 8px 0;">{{ __('Job: :job', ['job' => $application->job->title]) }}</p>
                    <p style="margin:0 0 8px 0;">{{ __('Company: :company', ['company' => $application->job->employer->name]) }}
                    </p>
                    <p style="margin:0;">
                        {{ __('Status:') }}
                        <span
                            style="display:inline-block;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:700;background:#EFF6FF;color:#1D4ED8;border:1px solid #BFDBFE;">
                            {{ $application->status->label() }}
                        </span>
                    </p>
                </div>

                <p style="margin:0 0 18px 0;">
                    {{ __('Track your application progress from your dashboard.') }}
                </p>

                <p style="margin:0 0 22px 0;">
                    <a href="{{ route('dashboard') }}"
                        style="display:inline-block;padding:11px 16px;background:#2563eb;color:#ffffff;text-decoration:none;border-radius:6px;font-weight:600;">
                        {{ __('Go to Dashboard') }}
                    </a>
                </p>

                <p style="margin:0;">
                    {{ __('Regards,') }}<br>
                    {{ __(':app Team', ['app' => config('app.name')]) }}
                </p>
            </td>
        </tr>
    </table>
</body>

</html>
