# Leads Reminder System - Commands

## Daily Reminder Commands (New Leads)

### Send to All Sales Persons
```bash
php artisan leads:send-reminder
```

### Send to Specific Sales Person
```bash
php artisan leads:send-reminder --sales-person=123
```

## Friday Reminder Commands (Contacted/Working Leads)

### Send to All Sales Persons
```bash
php artisan leads:send-friday-reminder
```

### Send to Specific Sales Person
```bash
php artisan leads:send-friday-reminder --sales-person=123
```

## Daily Management Report Commands

### Send Daily Report to Management
```bash
php artisan leads:send-daily-report
```

## Web Routes (for testing)

### Daily Reminders
```
GET /leads-reminder/send
GET /leads-reminder/send/{salesPersonId}
```

### Friday Reminders
```
GET /leads-friday-reminder/send
GET /leads-friday-reminder/send/{salesPersonId}
```

### Daily Management Report
```
GET /leads-daily-report/send
```

### Statistics
```
GET /leads-reminder/stats
```

## Automatic Schedule

- **9:00 AM Daily**: Morning reminders (New leads)
- **6:00 PM Daily**: Evening reminders (New leads)
- **Friday 9:00 AM**: Weekly follow-up (Contacted/Working leads)
- **7:00 PM Daily**: Management report to abdul@milele.com
