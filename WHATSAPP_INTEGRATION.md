# WhatsApp Gateway Integration for Order Notifications

## Overview
This implementation adds a configurable WhatsApp gateway to the Canting Food application. When a customer places an order through the frontend checkout, a WhatsApp message is automatically sent to the configured phone number with order details.

## Implementation Details

### 1. WhatsApp Service (`app/Services/WhatsAppService.php`)
- Handles communication with the WhatsApp API endpoint
- Uses configurable settings from database
- Formats order messages with proper WhatsApp formatting (bold, italic, etc.)
- Includes error handling and logging
- Supports custom message templates with placeholders

### 2. WhatsApp Gateway Service (`app/Services/WhatsAppGatewayService.php`)
- Manages WhatsApp gateway settings
- Provides settings CRUD operations
- Includes connection testing functionality

### 3. Order Service Integration (`app/Services/OrderService.php`)
- Modified `tableOrderStore` method to send WhatsApp notifications
- Added helper methods for formatting order data
- Integrated with existing notification system (email, SMS, push)

### 4. Admin Panel Integration
- **WhatsApp Gateway Settings Page**: `/admin/settings/whatsapp-gateway`
- **Controller**: `app/Http/Controllers/Admin/WhatsAppGatewayController.php`
- **Component**: `resources/js/components/admin/settings/WhatsAppGateway/WhatsAppGatewayComponent.vue`
- **Store Module**: `resources/js/store/modules/whatsappGateway.js`

### 5. Frontend Changes (`resources/js/components/table/checkout/CheckoutComponent.vue`)
- Removed Google Apps Script integration
- WhatsApp notifications now handled automatically by backend
- Cleaner, more maintainable code

## Configuration

### Admin Panel Settings
Access: **Admin Panel → Settings → WhatsApp Gateway**

#### Required Settings
1. **Enable WhatsApp Notifications**: Toggle to enable/disable
2. **API URL**: WhatsApp API endpoint (e.g., `https://dev-iptv-wa.appdewa.com/message/send-text`)
3. **Session**: WhatsApp session name (e.g., `mysession`)
4. **Phone Number**: Target phone number (format: `62812345678`)

#### Optional Settings
1. **Company Name**: Name to display in messages (default: "Canting Food")
2. **Message Template**: Custom message template with placeholders

### Message Template Placeholders
- `{company_name}` - Company name
- `{table_name}` - Table/room name
- `{items}` - Formatted list of ordered items
- `{subtotal}` - Order subtotal
- `{tax}` - Tax amount
- `{total}` - Total amount

### Default Message Template
```
*Hai {company_name}, ada pesanan baru nih!*
_Klik tautan berikut untuk mengkonfirmasi pesanan_ cantingfood.my.id

*Room/Table*
{table_name}

*Order Items*
{items}

*Subtotal*
{subtotal}
*Tax & Service*
{tax}
*Total*
{total}

_Thank's, happy working_
```

## API Integration
- **Endpoint**: Configurable via admin panel
- **Method**: POST
- **Content-Type**: application/json
- **Session**: Configurable via admin panel

### Request Format
```json
{
  "session": "mysession",
  "to": "62812345678",
  "text": "Order details message"
}
```

## Features

### 1. Configurable Settings
- All WhatsApp settings configurable through admin panel
- No hardcoded values
- Easy to change API endpoints, sessions, and phone numbers

### 2. Message Customization
- Custom message templates
- Placeholder system for dynamic content
- Professional formatting with WhatsApp markdown

### 3. Connection Testing
- Test connection button in admin panel
- Validates settings before sending messages
- Immediate feedback on configuration issues

### 4. Error Handling
- Graceful failure if WhatsApp is disabled
- Comprehensive logging
- No impact on order processing
- Validation of all settings

### 5. Security
- Settings validation through request classes
- API calls logged for monitoring
- No sensitive data exposed in logs

## Database Structure
Settings are stored in the `settings` table with group `whatsapp_gateway`:
- `whatsapp_enabled` (boolean)
- `whatsapp_api_url` (string)
- `whatsapp_session` (string)
- `whatsapp_phone` (string)
- `whatsapp_company_name` (string)
- `whatsapp_message_template` (text)

## Usage

### 1. Initial Setup
1. Run database seeder: `php artisan db:seed --class=WhatsAppGatewayTableSeeder`
2. Access admin panel: `/admin/settings/whatsapp-gateway`
3. Configure WhatsApp settings
4. Test connection
5. Enable notifications

### 2. Daily Operation
- Orders automatically trigger WhatsApp notifications
- No manual intervention required
- Monitor logs for any issues

### 3. Troubleshooting
- Use "Test Connection" button to verify settings
- Check application logs for error details
- Verify API endpoint and session are correct

## Testing
- Unit tests: `tests/Unit/WhatsAppServiceTest.php`
- Test script: `test_whatsapp_gateway.php`
- Admin panel test connection feature

## Benefits
- **Fully Configurable**: No hardcoded values
- **Professional Interface**: Clean admin panel
- **Robust Error Handling**: Comprehensive logging and validation
- **Scalable**: Easy to extend and modify
- **User-Friendly**: Intuitive admin interface
- **Secure**: Proper validation and logging
