# 📧 Leads Reminder System - Manual Guide

## Overview
The Leads Reminder System automatically sends email reminders to sales persons about their assigned leads with "New" status. The system runs twice daily at 9:00 AM and 6:00 PM to ensure no leads are left unattended.

## 🚀 Features

### ✅ Automated Scheduling
- **9:00 AM Daily**: Morning reminder emails
- **6:00 PM Daily**: Evening reminder emails
- **Laravel Scheduler**: Integrated with existing cron jobs

### ✅ Professional Email Design
- Clean, responsive email template
- Table structure with all lead details
- Clickable lead URLs for easy access
- Pending days calculation with urgent styling
- Professional branding and layout

### ✅ Comprehensive Lead Information
- Lead ID (clickable link)
- Customer name, phone, email, location
- Pending since (number of days)
- Action buttons to view leads
- Reminder text about updating status

## 📁 Files Created/Modified

### New Files Created:
1. `app/Http/Controllers/CallsReminderController.php` - Main controller
2. `app/Mail/LeadsReminderMail.php` - Email class
3. `app/Console/Commands/SendLeadsReminder.php` - Console command
4. `resources/views/emails/leads-reminder.blade.php` - Email template

### Modified Files:
1. `app/Models/User.php` - Added assignedLeads() relationship
2. `app/Console/Kernel.php` - Added scheduler and command registration
3. `routes/web.php` - Added reminder routes

## 🛠️ Setup Instructions

### 1. Verify Laravel Scheduler is Running
Make sure your Laravel scheduler is running by adding this to your server's crontab:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### 2. Test the System
Run the following commands to test the system:

```bash
# Test sending reminders to all sales persons
php artisan leads:send-reminder

# Test sending reminder to specific sales person
php artisan leads:send-reminder --sales-person=123

# Check scheduler status
php artisan schedule:list
```

### 3. Verify Email Configuration
Ensure your Laravel mail configuration is properly set up in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@milele.com
MAIL_FROM_NAME="Milele Leads System"
```

## 📊 Usage

### Manual Execution

#### Send Reminders to All Sales Persons
```bash
php artisan leads:send-reminder
```

#### Send Reminder to Specific Sales Person
```bash
php artisan leads:send-reminder --sales-person=123
```

### Web Routes (for testing)

#### Send Reminders via Web
```
GET /leads-reminder/send
```

#### Send to Specific Sales Person
```
GET /leads-reminder/send/{salesPersonId}
```

#### Get Reminder Statistics
```
GET /leads-reminder/stats
```

### Automatic Execution
The system automatically runs at:
- **9:00 AM** - Morning reminders
- **6:00 PM** - Evening reminders

## 📧 Email Template Features

### Email Content Includes:
- **Header**: Professional greeting with sales person's name
- **Summary**: Total number of new leads assigned
- **Table**: Detailed lead information
  - Lead ID (clickable link to lead details)
  - Customer name, phone, email, location
  - Pending days (with urgent styling for >3 days)
  - Action button to view lead
- **Footer**: Reminder about updating lead status

### Table Structure:
| Lead ID | Customer Name | Phone | Email | Location | Pending Since | Action |
|---------|---------------|-------|-------|---------|---------------|--------|
| #123 | John Doe | +971501234567 | john@email.com | Dubai | 2 days | View Lead |
| #124 | Jane Smith | +971509876543 | jane@email.com | Abu Dhabi | 5 days | View Lead |

### Styling Features:
- **Responsive Design**: Works on all devices
- **Professional Layout**: Clean, modern design
- **Urgent Styling**: Leads pending >3 days are highlighted
- **Clickable Links**: Easy navigation to lead details
- **Brand Colors**: Consistent with Milele branding

## 🔧 Configuration

### Scheduler Configuration
The reminders are scheduled in `app/Console/Kernel.php`:

```php
// Leads reminder emails at 9AM and 6PM
$schedule->command('leads:send-reminder')->dailyAt('09:00');
$schedule->command('leads:send-reminder')->dailyAt('18:00');
```

### Email Template Customization
Edit `resources/views/emails/leads-reminder.blade.php` to customize:
- Colors and styling
- Email content
- Table structure
- Footer messages

### Controller Customization
Edit `app/Http/Controllers/CallsReminderController.php` to modify:
- Lead filtering criteria
- Email content logic
- Statistics calculation

## 📈 Monitoring and Logs

### Log Files
Check these log files for system activity:
- `storage/logs/laravel.log` - General application logs
- `storage/logs/leads_reassign.log` - Lead reassignment logs

### Log Messages
The system logs important events:
- Reminder process start/completion
- Number of emails sent
- Total leads processed
- Errors and exceptions

### Example Log Entries:
```
[2024-01-15 09:00:01] Starting leads reminder email process
[2024-01-15 09:00:05] Reminder email sent to John Doe (john@milele.com) with 3 leads
[2024-01-15 09:00:10] Leads reminder process completed. Emails sent: 5, Total leads: 12
```

## 🚨 Troubleshooting

### Common Issues:

#### 1. Emails Not Sending
- Check mail configuration in `.env`
- Verify SMTP credentials
- Check Laravel logs for errors

#### 2. Scheduler Not Running
- Verify cron job is set up
- Check if `php artisan schedule:run` works manually
- Ensure server timezone is correct

#### 3. No Leads Found
- Check if sales persons have assigned leads
- Verify lead status is "New"
- Check database for lead assignments

#### 4. Email Template Issues
- Clear view cache: `php artisan view:clear`
- Check template syntax
- Verify file permissions

### Debug Commands:
```bash
# Check scheduler status
php artisan schedule:list

# Test email sending
php artisan tinker
>>> Mail::raw('Test email', function($msg) { $msg->to('test@email.com')->subject('Test'); });

# Check lead assignments
php artisan tinker
>>> App\Models\Calls::where('status', 'New')->count();
```

## 📋 Maintenance

### Regular Tasks:
1. **Monitor Logs**: Check for errors and performance issues
2. **Email Deliverability**: Ensure emails are not going to spam
3. **Lead Status Updates**: Encourage sales persons to update lead status
4. **Template Updates**: Keep email template current with branding

### Performance Optimization:
- Monitor database queries for large lead volumes
- Consider email queuing for high-volume scenarios
- Implement email rate limiting if needed

## 🔒 Security Considerations

### Access Control:
- Reminder routes should be protected with authentication
- Consider IP whitelisting for production environments
- Monitor for unauthorized access attempts

### Data Privacy:
- Ensure email content complies with privacy regulations
- Consider data retention policies for lead information
- Implement proper access controls for lead data

## 📞 Support

For technical support or questions about the Leads Reminder System:

1. **Check Logs**: Review application logs for error messages
2. **Test Commands**: Run manual commands to isolate issues
3. **Verify Configuration**: Ensure all settings are correct
4. **Contact Development Team**: For complex issues or customizations

## 📝 Changelog

### Version 1.0 (Initial Release)
- ✅ Automated reminder emails at 9AM and 6PM
- ✅ Professional email template with table structure
- ✅ Clickable lead URLs and pending days calculation
- ✅ Manual execution options
- ✅ Comprehensive logging and error handling
- ✅ Statistics and monitoring capabilities

---

**Last Updated**: January 2024  
**Version**: 1.0  
**Maintained By**: Milele Development Team
