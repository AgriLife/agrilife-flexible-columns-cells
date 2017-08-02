# AgriLife Plugin for Cells Column Type in Flexible Columns Page Template

[ ![Codeship Status for AgriLife/agrilife-extension-research](https://app.codeship.com/projects/1ade8650-2133-0135-19e8-660310782f44/status?branch=master)](https://app.codeship.com/projects/221267) on Master branch

[ ![Codeship Status for AgriLife/agrilife-extension-research](https://app.codeship.com/projects/1ade8650-2133-0135-19e8-660310782f44/status?branch=staging)](https://app.codeship.com/projects/221267) on Staging branch

Functionality for AgriLife Extension Research sites

## WordPress Requirements

1. [AgriFlex3 theme](https://github.com/agrilife/agriflex3)
2. [AgriLife Core plugin](https://github.com/agrilife/agrilife-core)
3. Advanced Custom Fields 5+ plugin (for Landing Page 1 Template)

## Installation

1. [Download the latest release](https://github.com/AgriLife/agrilife-flexible-columns-cells/releases/latest)
2. Upload the plugin to your site

## Features

* Cells type for the Columns row type of the Flexible Columns page template
* Repeater ACF field type for displaying content in rows and columns

## Development Installation

1. Copy this repo to the desired location.

## Development Notes

1. The ACF fields imported as JSON (flexiblecolumns-columnsaddon-details.json and publications-details.json) do not receive the "_name" property needed by the plugin while processing a field, which results in PHP Notice messages in debug mode. Add this property manually to new ACF fields in the JSON files.
