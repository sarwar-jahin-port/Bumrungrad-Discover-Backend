<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $formLabel }}</title>
  </head>
  <body style="font-family: 'Poppins', Arial, sans-serif; background-color: #f4f6f8; margin: 0; padding: 24px;">
    <table align="center" width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden;">
      <tr>
        <td style="background-color: #29286e; padding: 20px 30px;">
          <p style="margin: 0; color: #ffffff; font-size: 18px; font-weight: 600;">
            New Submission: {{ $formLabel }}
          </p>
        </td>
      </tr>
      <tr>
        <td style="padding: 24px 30px;">
          <table width="100%" cellpadding="0" cellspacing="0">
            @foreach ($data as $key => $value)
              @if (!is_null($value) && $value !== '')
                <tr>
                  <td style="padding: 8px 12px; border-bottom: 1px solid #eeeeee; font-weight: 600; color: #29286e; width: 40%; vertical-align: top;">
                    {{ ucwords(str_replace('_', ' ', preg_replace('/(?<!^)[A-Z]/', ' $0', $key))) }}
                  </td>
                  <td style="padding: 8px 12px; border-bottom: 1px solid #eeeeee; color: #333333; word-break: break-word;">
                    {{ is_array($value) ? implode(', ', $value) : $value }}
                  </td>
                </tr>
              @endif
            @endforeach
          </table>
        </td>
      </tr>
      <tr>
        <td style="padding: 16px 30px; background-color: #f4f6f8;">
          <p style="margin: 0; font-size: 12px; color: #888888;">
            Any uploaded files are attached to this email. This is an automated notification from the Discover Bangladesh website.
          </p>
        </td>
      </tr>
    </table>
  </body>
</html>
