# GA4 Reporting Integration Guide

This document provides instructions for configuring the server-side Google Analytics 4 (GA4) reporting infrastructure implemented in Phase 2.

## ⚙️ Configuration

To enable reporting in the admin dashboard, the following setup is required:

### 1. Environment Variables
Add these keys to your `.env` file:

- `GA4_PROPERTY_ID`: The numeric ID of your GA4 property.
- `GA4_SERVICE_ACCOUNT_JSON`: The absolute path to the service account JSON key file on the server.

### 2. Google Cloud Setup
1. **Create Service Account:** In the Google Cloud Console, create a service account.
2. **Grant Permissions:** Add the service account's email address as a user with "Viewer" permissions in the GA4 Admin console.
3. **Generate Key:** Create and download a JSON key for the service account.
4. **Secure Storage:** Upload the JSON file to a secure directory (e.g., `storage/app/google-analytics/key.json`). **Never commit this file to Git.**

### 3. Verification
Once configured, run the following Artisan command to verify connectivity:

```bash
php artisan analytics:test
```

A successful response will print `Connected successfully!`.
