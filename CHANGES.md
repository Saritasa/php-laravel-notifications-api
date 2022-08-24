# Changes History

2.0.0
-----
! Use uppercase in table names
- Fix pagination - page size (per_page query parameter) was not working

1.2.0
-----
- Add `DELETE notifications` api endpoint
- Rename `POST notifications/viewed` to `POST notifications/read`

1.2.2
-----
Add notification type to GET /notifications

1.2.1
-----
Add timestamps to NotificationSetting

1.2.0
-----
- Add DELETE notifications api endpoint
- Rename POST notifications/viewed to POST notifications/read

1.1.4
-----
Update notification transformer output and corresponding Swagger docs

1.1.3
-----
Allow get single notification type preference

1.1.2
-----
Implement settings update

1.1.1
-----
Fix namespace in controllers

1.1.0
-----
Declare compatibility with Laravel 6

1.0.4
-----
Enable Laravel's package discovery https://laravel.com/docs/5.5/packages#package-discovery

1.0.3
-----
Update dependencies versions

1.0.2
-----
Require authentication for notifications API endpoints

1.0.1
-----
Add route PUT /settings/device

1.0.0
-----
Initial version
