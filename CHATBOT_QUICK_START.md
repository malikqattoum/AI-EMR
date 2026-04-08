# WhatsApp & Messenger Chatbot - Quick Start Guide

## 🚀 Quick Setup (5 Minutes)

### Step 1: Run Migrations & Seeder

```bash
cd C:\xampp\htdocs\AI-EMR
php artisan migrate
php artisan db:seed --class=ChatbotSeeder
```

### Step 2: Configure Environment Variables

Add these to your `.env` file:

```env
# For Testing (No external service required)
CHATBOT_AI_ENABLED=true

# WhatsApp (Optional - for WhatsApp support)
WHATSAPP_BUSINESS_ACCESS_TOKEN=
WHATSAPP_BUSINESS_PHONE_NUMBER_ID=
WHATSAPP_WEBHOOK_VERIFY_TOKEN=medcura-webhook-verify

# Facebook Messenger (Optional - for Messenger support)
MESSENGER_ACCESS_TOKEN=
MESSENGER_APP_SECRET=
MESSENGER_VERIFY_TOKEN=medcura-messenger-verify
MESSENGER_PAGE_ID=
```

### Step 3: Access Admin Panel

Navigate to: `http://yourdomain.com/admin/chatbot/settings`

You'll see:
- Conversation statistics
- Configurable intents
- Testing tools
- Setup guides

### Step 4: Test the Chatbot (Without External Services)

The chatbot is ready to use! Even without WhatsApp or Messenger credentials, you can:

1. View the admin panel
2. See conversation logs
3. Manage intents
4. Test once you add credentials

## 🔧 Setting Up WhatsApp Business API

### 1. Create Meta Business Account

1. Go to [https://business.facebook.com](https://business.facebook.com)
2. Create or select your business
3. Navigate to WhatsApp section

### 2. Get Credentials

1. Go to WhatsApp > API Setup
2. Create a phone number
3. Copy:
   - Access Token
   - Phone Number ID

### 3. Configure Webhook

1. In WhatsApp API Setup
2. Add webhook URL: `https://yourdomain.com/webhooks/whatsapp`
3. Verify token: `medcura-webhook-verify`
4. Subscribe to: `messages` event

### 4. Update .env

```env
WHATSAPP_BUSINESS_ACCESS_TOKEN=your_token_here
WHATSAPP_BUSINESS_PHONE_NUMBER_ID=your_phone_number_id_here
```

## 🔧 Setting Up Facebook Messenger

### 1. Create Facebook Page

If you don't have one:
1. Go to Facebook
2. Create a Page for your business

### 2. Create Facebook App

1. Go to [https://developers.facebook.com](https://developers.facebook.com)
2. Create App
3. Add Messenger product

### 3. Get Credentials

1. Go to Messenger > Settings
2. Generate Page Access Token
3. Copy App Secret from App Settings
4. Copy Page ID from your Page

### 4. Configure Webhook

1. In Messenger > Settings
2. Add webhook URL: `https://yourdomain.com/webhooks/messenger`
3. Verify token: `medcura-messenger-verify`
4. Subscribe to:
   - `messages`
   - `messaging_postbacks`

### 5. Update .env

```env
MESSENGER_ACCESS_TOKEN=your_page_access_token_here
MESSENGER_APP_SECRET=your_app_secret_here
MESSENGER_PAGE_ID=your_page_id_here
```

## 🧪 Testing the Chatbot

### Method 1: Using Admin Panel

1. Go to `/admin/chatbot/settings`
2. Use the "Test WhatsApp" form
3. Enter a phone number and message
4. Click "Send Test WhatsApp"

### Method 2: Using WhatsApp/Messenger

1. Send a message to your WhatsApp/Messenger number
2. Try these messages:
   - "Hi" or "Hello"
   - "Book appointment"
   - "My appointments"
   - "Help"

### Method 3: Direct API Call

```bash
curl -X POST http://yourdomain.com/webhooks/whatsapp \
  -H "Content-Type: application/json" \
  -d '{
    "entry": [{
      "changes": [{
        "value": {
          "messages": [{
            "from": "+1234567890",
            "text": {"body": "Hi"}
          }]
        }
      }]
    }]
  }'
```

## 📋 What Patients Can Do

### 1. Check Doctor Availability
```
Patient: "Check availability"
Bot: Shows available doctors
Patient: Selects doctor
Bot: Asks for date
Patient: Enters date
Bot: Shows available times
```

### 2. Book Appointment
```
Patient: "Book appointment"
Bot: Shows doctors
Patient: Selects doctor
Bot: Asks for date
Patient: Enters date
Bot: Shows times
Patient: Selects time
Bot: Confirms booking
```

### 3. View Appointments
```
Patient: "My appointments"
Bot: Shows upcoming appointments
```

### 4. Cancel Appointment
```
Patient: "Cancel appointment"
Bot: Shows cancellable appointments
Patient: Selects appointment
Bot: Asks for confirmation
Patient: Confirms
Bot: Cancels appointment
```

### 5. Reschedule Appointment
```
Patient: "Reschedule appointment"
Bot: Shows reschedulable appointments
Patient: Selects appointment
Bot: Asks for new date
Patient: Enters date
Bot: Shows available times
Patient: Selects time
Bot: Confirms reschedule
```

## 🎯 Admin Features

### Settings Page (`/admin/chatbot/settings`)

- **Statistics Dashboard**: View conversation metrics
- **Intent Management**: Enable/disable chatbot intents
- **Quick Actions**: Test and manage conversations
- **Setup Guide**: Step-by-step configuration help

### Conversations Page (`/admin/chatbot/conversations`)

- **View All Conversations**: Filter by platform, state, patient
- **View Details**: See full message history
- **Delete**: Remove conversations
- **Search**: Find specific conversations

## 🔍 Troubleshooting

### Issue: Webhook not receiving messages

**Solution:**
1. Verify webhook URL uses HTTPS
2. Check verify token matches in .env
3. Ensure webhook is subscribed to correct events
4. Check logs: `storage/logs/laravel.log`

### Issue: Messages not being sent

**Solution:**
1. Verify credentials in .env are correct
2. Check phone number format (include country code)
3. Review error messages in logs
4. Test credentials using Postman/cURL

### Issue: Patient not identified

**Solution:**
1. **WhatsApp**: Phone number must match database exactly
2. **Messenger**: Patient must provide phone/email manually
3. Check phone format: `+` followed by country code and number

### Issue: AI not working

**Solution:**
1. Verify `OPENAI_API_KEY` is set in .env
2. Check `CHATBOT_AI_ENABLED=true`
3. Fallback to keyword matching still works

## 📊 Monitoring & Analytics

### View in Admin Panel

1. Total conversations
2. Active conversations
3. Platform distribution (WhatsApp vs Messenger)
4. Intent usage
5. Message success rates

### Logs Location

```
storage/logs/laravel.log
```

Search for:
- `WhatsApp webhook received`
- `Messenger message received`
- `Chatbot message processing`
- `Chatbot action handler`

## 🚀 Production Checklist

Before going live:

- [ ] SSL certificate installed
- [ ] Webhooks configured and tested
- [ ] All environment variables set
- [ ] Migrations run
- [ ] Intents seeded
- [ ] Test messages sent successfully
- [ ] Admin panel accessible
- [ ] Logs being monitored
- [ ] Queue workers running (if using queues)
- [ ] Error notifications set up
- [ ] Backup strategy in place

## 📚 Additional Resources

- Full Documentation: `CHATBOT_FEATURE.md`
- Laravel Documentation: https://laravel.com/docs
- WhatsApp Business API: https://developers.facebook.com/docs/whatsapp
- Messenger Platform: https://developers.facebook.com/docs/messenger-platform

## 🆘 Need Help?

1. Check logs first: `storage/logs/laravel.log`
2. Review admin panel for error messages
3. Verify all environment variables
4. Test with the built-in test tools
5. Check platform credentials and webhook URLs

---

**That's it!** Your chatbot is now ready to interact with patients via WhatsApp and Messenger! 🎉
