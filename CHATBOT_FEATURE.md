# WhatsApp & Messenger Chatbot Feature Documentation

## Overview

This comprehensive chatbot system allows patients to interact with the MedCura AI EMR system via WhatsApp and Facebook Messenger. Patients can perform actions like:

- Check doctor availability
- Book new appointments
- View upcoming appointments
- Cancel existing appointments
- Reschedule appointments
- Get help and navigate the system

## Architecture

### Core Components

1. **Platform Adapters** (`app/Services/Chatbot/Platforms/`)
   - `WhatsAppPlatform.php` - WhatsApp Business API integration
   - `MessengerPlatform.php` - Facebook Messenger integration
   - `ChatbotPlatformInterface.php` - Common interface for all platforms

2. **Chatbot Service** (`app/Services/Chatbot/ChatbotService.php`)
   - Central orchestration service
   - AI-powered intent recognition using OpenAI GPT-4o-mini
   - Conversation state management
   - Message routing and response handling

3. **Action Handlers** (`app/Services/Chatbot/Actions/`)
   - `CheckAvailabilityAction.php` - Check doctor availability
   - `BookAppointmentAction.php` - Book new appointments
   - `ViewAppointmentsAction.php` - View patient appointments
   - `CancelAppointmentAction.php` - Cancel appointments
   - `RescheduleAppointmentAction.php` - Reschedule appointments

4. **Models** (`app/Models/`)
   - `ChatbotConversation.php` - Conversation sessions
   - `ChatbotMessage.php` - Message logs
   - `ChatbotIntent.php` - Intent configurations

5. **Controllers** (`app/Http/Controllers/`)
   - `WhatsAppWebhookController.php` - WhatsApp webhook handler
   - `MessengerWebhookController.php` - Messenger webhook handler
   - `Admin/ChatbotController.php` - Admin management interface

## Setup Instructions

### 1. Database Migrations

Run the migrations to create the chatbot tables:

```bash
php artisan migrate
```

This will create:
- `chatbot_conversations` - Conversation sessions
- `chatbot_messages` - Message logs
- `chatbot_intents` - Intent configurations

### 2. Seed Default Intents

```bash
php artisan db:seed --class=ChatbotSeeder
```

### 3. Configure Environment Variables

Add the following to your `.env` file:

#### WhatsApp Business API

```env
WHATSAPP_BUSINESS_ACCESS_TOKEN=your_access_token_here
WHATSAPP_BUSINESS_PHONE_NUMBER_ID=your_phone_number_id_here
WHATSAPP_WEBHOOK_VERIFY_TOKEN=medcura-webhook-verify
```

#### Facebook Messenger

```env
MESSENGER_ACCESS_TOKEN=your_page_access_token_here
MESSENGER_APP_SECRET=your_app_secret_here
MESSENGER_VERIFY_TOKEN=medcura-messenger-verify
MESSENGER_PAGE_ID=your_page_id_here
```

#### Chatbot Configuration

```env
CHATBOT_AI_ENABLED=true
CHATBOT_DEFAULT_MODEL=gpt-4o-mini
CHATBOT_MAX_CONVERSATION_AGE_HOURS=24
CHATBOT_INTENT_CONFIDENCE_THRESHOLD=0.7
```

### 4. Configure Webhooks

#### WhatsApp Business API Webhook

1. Go to your Meta Business App
2. Navigate to WhatsApp > Configuration
3. Set webhook URL: `https://yourdomain.com/webhooks/whatsapp`
4. Verify token: `medcura-webhook-verify` (or custom from .env)
5. Subscribe to: `messages` event

#### Facebook Messenger Webhook

1. Go to your Facebook App Dashboard
2. Navigate to Messenger > Settings
3. Add webhook subscription with URL: `https://yourdomain.com/webhooks/messenger`
4. Verify token: `medcura-messenger-verify` (or custom from .env)
5. Subscribe to: `messages` and `messaging_postbacks` events

### 5. Configuration File

The chatbot configuration is in `config/chatbot.php`. You can customize:

- AI settings
- Conversation timeout
- Platform credentials
- Allowed actions
- Patient identification methods
- Logging behavior

## Usage

### Patient Interaction Flow

#### Booking an Appointment

1. Patient sends: "I want to book an appointment"
2. Bot identifies patient (via phone number on WhatsApp, or asks for ID on Messenger)
3. Bot shows available doctors
4. Patient selects doctor
5. Bot asks for preferred date
6. Patient enters date
7. Bot shows available time slots
8. Patient selects time
9. Bot confirms booking details
10. Patient confirms
11. Bot creates appointment and sends confirmation

#### Checking Availability

1. Patient sends: "Check availability"
2. Bot asks for doctor
3. Patient selects doctor
4. Bot asks for date
5. Patient enters date
6. Bot shows available slots

#### Viewing Appointments

1. Patient sends: "My appointments"
2. Bot shows upcoming appointments
3. Patient can cancel from the list

#### Canceling Appointment

1. Patient sends: "Cancel appointment"
2. Bot shows cancellable appointments
3. Patient selects appointment
4. Bot asks for confirmation
5. Patient confirms
6. Bot cancels appointment

#### Rescheduling Appointment

1. Patient sends: "Reschedule appointment"
2. Bot shows reschedulable appointments
3. Patient selects appointment
4. Bot asks for new date
5. Patient enters date
6. Bot shows available slots
7. Patient selects time
8. Bot confirms reschedule
9. Patient confirms
10. Bot reschedules appointment

### Admin Management

Access the admin panel at: `/chatbot/settings`

Features:
- View conversation statistics
- Manage intents (enable/disable)
- View all conversations
- View conversation details with message history
- Send test messages
- Delete conversations

## Intent Recognition

The chatbot uses a multi-layered approach to understand user intent:

1. **Quick Reply Payload** - Direct matching from button clicks
2. **State-Based Matching** - Based on conversation state
3. **AI Recognition** - OpenAI GPT-4o-mini classifies intent
4. **Keyword Matching** - Fallback keyword detection

### Default Intents

| Intent | Description | Keywords |
|--------|-------------|----------|
| greeting | User greets the bot | hi, hello, hey, start |
| help | User needs help | help, menu, options |
| goodbye | User says goodbye | bye, thank you, done |
| check_availability | Check doctor availability | availability, slots, schedule |
| book_appointment | Book new appointment | book, make appointment |
| view_appointments | View patient appointments | my appointments, upcoming |
| cancel_appointment | Cancel appointment | cancel appointment |
| reschedule_appointment | Reschedule appointment | reschedule, change appointment |

## Conversation States

The chatbot uses state machines to manage multi-step conversations:

- `idle` - No active flow
- `awaiting_patient_identification` - Waiting for patient ID
- `awaiting_doctor` - Waiting for doctor selection
- `awaiting_date` - Waiting for date input
- `awaiting_time` - Waiting for time selection
- `booking_select_doctor` - Booking flow: select doctor
- `booking_select_date` - Booking flow: select date
- `booking_select_time` - Booking flow: select time
- `booking_confirm` - Booking flow: confirm details
- `booking_final_confirm` - Booking flow: final confirmation
- `cancel_select_appointment` - Cancel flow: select appointment
- `cancel_confirm` - Cancel flow: confirm cancellation
- `reschedule_select_appointment` - Reschedule flow: select appointment
- `reschedule_select_date` - Reschedule flow: select date
- `reschedule_select_time` - Reschedule flow: select time
- `reschedule_confirm` - Reschedule flow: confirm

## Security & Compliance

- All webhook routes are protected with verify tokens
- Patient data is only accessed after proper identification
- All conversations are logged for audit trails
- Phone numbers are used for identification on WhatsApp
- Messenger PSIDs are platform-specific and secure

## Troubleshooting

### Webhook Not Working

1. Verify webhook URL is accessible (HTTPS required)
2. Check verify token matches in .env
3. Check logs: `storage/logs/laravel.log`
4. Test webhook manually using tools like Postman

### Messages Not Being Sent

1. Check platform credentials in .env
2. Verify phone number format (must include country code)
3. Check WhatsApp Business API quota
4. Review logs for specific error messages

### AI Intent Recognition Not Working

1. Verify `OPENAI_API_KEY` is set
2. Check `CHATBOT_AI_ENABLED=true`
3. Review AI response in logs
4. Fallback to keyword matching will still work

### Patient Not Identified

1. On WhatsApp: Ensure patient phone number matches database
2. On Messenger: Patient must manually provide phone/email
3. Check phone number format (include country code)

## Production Deployment Checklist

- [ ] Run migrations
- [ ] Seed default intents
- [ ] Configure all environment variables
- [ ] Set up SSL certificate for webhooks
- [ ] Configure webhooks on WhatsApp Business API
- [ ] Configure webhooks on Facebook Messenger
- [ ] Test both platforms end-to-end
- [ ] Set up log monitoring
- [ ] Configure queue workers for background jobs
- [ ] Set up error notifications
- [ ] Review and test all conversation flows
- [ ] Test patient identification
- [ ] Test appointment booking, cancellation, rescheduling
- [ ] Verify admin panel access and functionality

## API Reference

### Admin Routes

```
GET  /chatbot/settings                    - View settings
POST /chatbot/settings                    - Update settings
GET  /chatbot/conversations               - List conversations
GET  /chatbot/conversations/{id}          - View conversation
DELETE /chatbot/conversations/{id}        - Delete conversation
POST /chatbot/intents/{id}/toggle         - Toggle intent
POST /chatbot/test-message                - Send test message
POST /chatbot/test-whatsapp               - Test WhatsApp
```

### Webhook Routes

```
GET  /webhooks/whatsapp                   - WhatsApp verification
POST /webhooks/whatsapp                   - WhatsApp messages
GET  /webhooks/messenger                  - Messenger verification
POST /webhooks/messenger                  - Messenger messages
```

## Customization

### Adding New Intents

1. Add to `ChatbotIntent::getDefaults()` array
2. Create action handler in `app/Services/Chatbot/Actions/`
3. Extend `ChatbotActionHandler` base class
4. Implement `handle()` method
5. Run seeder to add to database

### Customizing Responses

Edit the `responses` array in `ChatbotIntent` records via:
- Admin panel
- Database directly
- Seeder updates

### Adding New Platforms

1. Create new platform adapter implementing `ChatbotPlatformInterface`
2. Add to `ChatbotService::getPlatformAdapter()`
3. Create webhook controller
4. Add webhook routes
5. Add platform configuration to `config/chatbot.php`

## Support

For issues or questions:
1. Check logs: `storage/logs/laravel.log`
2. Review admin panel at `/chatbot/settings`
3. Check platform credentials and webhook configuration
4. Verify OpenAI API key is set and working
5. Test with the built-in test message feature
