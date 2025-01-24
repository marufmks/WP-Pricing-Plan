# WordPress Pricing Plan Plugin

A modern, user-friendly WordPress plugin for creating and managing beautiful pricing plans and comparison tables.

## Features

- 🎨 Modern, card-based interface
- 📱 Fully responsive design
- 🔄 Single-page application (SPA) architecture
- 💼 Multiple pricing plan layouts
- 📦 Package management system
- 🎯 Easy-to-use shortcodes
- ⚡ Fast and lightweight
- 🛠️ Developer-friendly codebase

## Installation

1. Download the plugin zip file
2. Go to WordPress admin panel > Plugins > Add New
3. Click "Upload Plugin" and choose the downloaded file
4. Click "Install Now" and then "Activate"

## Development

### Prerequisites

- Node.js (v14 or higher)
- Composer
- WordPress (v5.8 or higher)
- PHP (v7.4 or higher)

### Setup

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install

# Start development
npm start

# Build for production
npm run build
```

### File Structure

```
pricing-plan/
├── core/                  # PHP core files
│   ├── Admin/            # Admin-related functionality
│   ├── Api/              # REST API endpoints
│   ├── Base/             # Base plugin functionality
│   ├── Frontend/         # Frontend-related functionality
│   └── Models/           # Database models
├── src/                  # React application source
│   ├── components/       # React components
│   └── style.css        # Main stylesheet
├── build/               # Compiled assets
├── languages/           # Translation files
└── vendor/             # Composer dependencies
```

## Usage

1. After activation, find "Pricing Plan" in your WordPress admin menu
2. Create a new pricing plan
3. Add packages to your plan
4. Use the shortcode `[pricing_plan id="X"]` to display your plan
5. Customize appearance using the settings page

## Shortcodes

```
[pricing_plan id="1"]              # Display a specific pricing plan
[pricing_plan id="1" type="table"] # Display as comparison table
```

## REST API

The plugin provides a REST API for developers:

- `GET /wp-json/pricing-plan/v1/plans` - List all plans
- `POST /wp-json/pricing-plan/v1/plans` - Create a plan
- `GET /wp-json/pricing-plan/v1/plans/{id}` - Get a specific plan
- `PUT /wp-json/pricing-plan/v1/plans/{id}` - Update a plan
- `DELETE /wp-json/pricing-plan/v1/plans/{id}` - Delete a plan

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the GPL v2 or later - see the [LICENSE](LICENSE) file for details.

## Credits

Created by [Maruf Khan](https://github.com/marufmks) 