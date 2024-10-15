# WooUser API Connector

**Connect WooCommerce users with an external API based on their preferences.**

## Introduction

**WooUser API Connector** is a WordPress plugin that integrates WooCommerce users with a custom external API. It allows users to configure their API preferences directly from their WooCommerce account and view real-time data fetched from the API. Built with maintainability and scalability in mind, the plugin follows best coding practices to ensure a reliable and secure experience.

## Features

- **External API Integration:** Connect WooCommerce accounts with a customized external API.
- **User Configuration:** Users can input and manage their API preferences from the **My Account > API Settings** section.
- **API Data Widget:** Display API data in any widget area, tailored to each user's preferences.
- **Data Caching:** Utilizes WordPress transients to cache API responses, enhancing performance.
- **Enhanced Security:** Implements input sanitization, nonce verification, and permission checks.
- **Modular Design:** Organized using object-oriented principles for easy maintenance and expansion.

## Installation

1. **Download the Plugin:**
   - Clone the repository:
     ```bash
     git clone https://github.com/jairoprez/woouser-api-connector.git
     ```
   - Or download the ZIP file from GitHub.

2. **Upload to WordPress:**
   - **Via Plugin Manager:**
     - Go to **Plugins > Add New** in your WordPress dashboard.
     - Click **Upload Plugin**, select the ZIP file, and click **Install Now**.
     - Activate the plugin after installation.
   - **Via FTP:**
     - Upload the `woouser-api-connector` folder to `/wp-content/plugins/` using an FTP client.
     - Activate the plugin from **Plugins > Installed Plugins** in your dashboard.

3. **Flush Rewrite Rules:**
   - Navigate to **Settings > Permalinks**.
   - Click **Save Changes** without making any modifications to update the rewrite rules.

## Usage

### API Settings

1. **Access API Settings:**
   - Log in to your WordPress site.
   - Go to **My Account > API Settings**.

2. **Configure Elements:**
   - Enter a comma-separated list of alphanumeric elements (e.g., `element1, element2, element3`).
   - Click **Save Settings** to store your preferences.

3. **View API Data:**
   - If elements are configured, the **Your API Data** section will display the corresponding API headers.

### API Data Widget

1. **Add the Widget:**
   - Go to **Appearance > Widgets**.
   - Locate the **API Data Widget** and drag it to your desired widget area.

2. **Configure the Widget:**
   - Set a title if desired.
   - Save the widget settings.

3. **Verify Display:**
   - Visit your site as a logged-in user to see the API data based on your preferences.
   - If no elements are set, the widget will remain empty.

## Frequently Asked Questions

### Do I need technical knowledge to use this plugin?

No, **WooUser API Connector** is designed to be user-friendly. Simply enter the desired elements, and the plugin handles the rest.

### What happens if the external API is down?

The plugin gracefully handles API errors. If the API doesn't respond or returns an error, an error message will be displayed instead of the data, ensuring your site remains unaffected.

### How can I change the API URL?

You can modify the API URL using the `wou_api_handler_api_url` filter in your theme's `functions.php` file or through a custom plugin.

**Example:**
```php
add_filter( 'wou_api_handler_api_url', 'custom_api_url' );

function custom_api_url( $url ) {
    return 'https://your-custom-api.com/endpoint';
}
```
