# [Google Customer Reviews for Magento 2](https://www.aitoc.com/magento-2-google-customer-reviews.html)

Smart solution for gathering customer reviews and analyzing them to improve efforts in the required fields.

## Extension features

- Collect valuable reviews about your business
- Integrate the Google Customer Reviews with your Magento 2 store painlessly
- Automatically add the Google Customer Reviews badge
- Apply the Survey Opt-in in the checkout page
- Take advantages of integration testing tools
- Analyze reviews to better understand the shopping experience you provide

## Attract New Customers and be a Trusted Store Owner

The power of **Google Customer Reviews** is that the module allows store owners to request product reviews from shoppers and display them on the site with the help of a special badge. The logic is simple: when you have many customer reviews, shoppers will most likely pay attention to the store. It will increase their confidence to make purchases on your Magento 2 store.

With the friendly functionality of the Google Customer Reviews extension, you can configure script on the store frontend by allowing all needed field configurations from the backend. This module also auto checks the order success page, injecting a piece of script code predestinate for the success page only. The extension is able to work in multiple countries of all continents.

Show off all the advantages of your e-commerce business and encourage potential users to purchase with the help of the powerful [Magento 2 Google Customer Reviews module](https://www.aitoc.com/magento-2-google-customer-reviews.html).

## How does it work?
 
Your buyers get an option to opt-in to receive an email requesting feedback from Google about the experience with your online store. 

In case customers opt-in, they can get such an email after the order has arrived. All ratings are will be displayed on the Google Customer Reviews badge and appear in your Merchant Center dashboard for seller ratings.

[![](https://fstorage.aitoc.com/documentation/google-customer-reviews-m2/google.png)](https://fstorage.aitoc.com/documentation/google-customer-reviews-m2/google.png)

## Four Most Essential Features in Google Customer Reviews:

- Opt-in
The opt-in appears after checkout to all customers. Users who opt-in receive an email from Google Customer Reviews that asks them to rate their experience.

- Badge
The Google Customer Reviews badge assists in identifying your site with the Google brand. 

- Survey
The Google Customer Reviews survey is optional. This is a short questionnaire, enabling customers to rate their purchase experiences with your site.

- Seller Ratings
This kind of ratings mean an aggregate score that appears on search ads and Google shopping.

## Useful Information
- [About Us](https://www.aitoc.com/about-us.html)
- [Privacy Policy](https://www.aitoc.com/privacy-policy.html)
- [Partnership Program](https://www.aitoc.com/partnership-program)
- [Affiliate Program](https://www.aitoc.com/affiliate-program)
- [Aitoc Customer Rewards](https://www.aitoc.com/reward-points)
- [Google Customer Reviews User Guide](https://www.aitoc.com/docs/guides/google-customer-reviews.html)
- [Get Support](https://www.aitoc.com/get-support.html)

## Installation Guide 

**INSTALL VIA COMPOSER**

Here you can find the guide [**'Extensions installation via composer'**](https://www.aitoc.com/docs/guides/composer.html#extensions-installation-via-composer).

As your next steps, run these CLI commands:

```
composer require aitoc/google-customer-reviews
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
```

**Copy/Paste Install**

To install the extension to your Magento 2 store, please follow these steps:
- Unzip and paste the extension file into your root Magento folder.
- Connect to your server by SSH.
- Go to your Magento root folder.
- To install the extension, perform this command:

```
php bin/magento setup:upgrade
```

- Reset JavaScript cache by removing all folders in pub/static:

```
_requirejs; adminhtml; frontend.
```

- To switch the extension on/off, perform these commands:

```
php bin/magento module:enable Aitoc_GoogleCustomerReview
php bin/magento module:disable Aitoc_GoogleCustomerReview
```

## Initial setup

- Create your Google Merchant Account. [Click here](https://support.google.com/merchants/answer/188924?hl=en "Click here") to see instructions.

**Enabling widget functionality**
Note that you need to enable widget functionality in your Google Merchant account.
Go to Program setup → Merchant Center programs:
[![](https://fstorage.aitoc.com/documentation/google-customer-reviews-m2/initial_set.png)](https://fstorage.aitoc.com/documentation/google-customer-reviews-m2/initial_set.png)
Find Customer Reviews and click ENABLE:
[![](https://fstorage.aitoc.com/documentation/google-customer-reviews-m2/initial_set2.png)](https://fstorage.aitoc.com/documentation/google-customer-reviews-m2/initial_set2.png)

- Go to SYSTEM > CONFIGURATION > AITOC EXTENSIONS > GOOGLE CUSTOMER REVIEWS

[![](https://fstorage.aitoc.com/documentation/google-customer-reviews-m2/config.png)](https://fstorage.aitoc.com/documentation/google-customer-reviews-m2/config.png)

**NOTE:**
General settings can be defined on website level only.

|  **Setting** | **Purpose**  |
| ------------ | ------------ |
|  Enable Google Customer Reviews |  You can enable or disable the extension for the whole website. |
| Merchant ID  |  Here you paste your Google Merchant ID [![](https://fstorage.aitoc.com/documentation/google-customer-reviews-m2/purpose.png)](https://fstorage.aitoc.com/documentation/google-customer-reviews-m2/purpose.png) |
| Default Estimated Delivery Time  | The number of days which shipping usually takes. The extension sends the review prompts when it considers the order delivered.  |
|  Custom Delivery Time Rules |  This allows you to tweak your delivery dates (it will affect the date when Google Customer Reviews sends an email asking for feedback). |

The Survey and Badge settings can be determined on store view level.
[![](https://fstorage.aitoc.com/documentation/google-customer-reviews-m2/config2.png)](https://fstorage.aitoc.com/documentation/google-customer-reviews-m2/config2.png)

| **Setting**  |  **Purpose** |
| ------------ | ------------ |
| Offer to all customer groups  |  If you pick "No", you'll be able to choose which customer groups receive the survey. |
| Survey Style  | Select the positioning of your survey.  |
| Survey Language  |  If you don't use system value, you'll be able to pick the language from a long dropdown menu. |
| Enable Badge  |  This setting enables the reviews badge on your website. |
|  Badge Position |  Badge positioning on your website. |
| Badge Language  |  Similar to Survey Language, Badge Language can be picked from a dropdown list if needed. |

**Warning:**
Don't forget to save your config for each store view.

**To see the any changes affecting your store, flush Magento cache.**

[![](https://fstorage.aitoc.com/documentation/google-customer-reviews-m2/women.png)](https://fstorage.aitoc.com/documentation/google-customer-reviews-m2/women.png)

Here is an example of Google Customers Review survey email:

[![](https://fstorage.aitoc.com/documentation/google-customer-reviews-m2/window.png)](https://fstorage.aitoc.com/documentation/google-customer-reviews-m2/window.png)

The design of this email is default and provided by Google, it cannot be customized.

##Reasons to choose Aitoc:

Aitoc proposes a full range of Magento development services, supporting e-commerce businesses with strong expertise and serious experience.

- **100+** Magento extensions built
- **800+** development projects completed
- **3000+** positive reviews on Magento Connect
- **20000+** happy clients in over 100 countries, and counting

More FREE Magento 2 Extensions by Aitoc on GitHub
---
- [Aitoc Core Extension](https://github.com/aitoc/magento-2-core)
- [SMTP Email Configuration](https://www.aitoc.com/magento-2-smtp.html)

Other Magento 2 Extensions by Aitoc
---
- [Orders Export and Import](https://www.aitoc.com/magento-2-orders-export-and-import.html)
- [Custom Product Designer](https://www.aitoc.com/magento-2-custom-product-designer.html)
- [Product Units and Quantities](https://www.aitoc.com/magento-2-units-and-quantities.html)
- [Dimensional Shipping](https://www.aitoc.com/magento-2-dimensional-shipping.html) 
- [Advanced Permissions](https://www.aitoc.com/magento-2-advanced-permissions.html)
- [Email Marketing Suite](https://www.aitoc.com/magento-2-email-marketing-suite.html) 
- [Pre-Orders](https://www.aitoc.com/magento-2-pre-orders.html) 
- [Google Page Speed Optimization](https://www.aitoc.com/magento-2-google-pagespeed-optimization-extension.html) 
- [Free Gift](https://www.aitoc.com/magento-2-free-gift.html)
- [Follow Up Emails](https://www.aitoc.com/magento-2-follow-up.html) 
- [Shipping Rules](https://www.aitoc.com/magento-2-shipping-rules.html) 
- [Shipping Restrictions](https://www.aitoc.com/magento-2-shipping-restrictions.html) 
- [Shipping Table Rates & Methods](https://www.aitoc.com/magento-2-shipping-table-rates.html) 

See more [**Magento 2 extensions**](https://www.aitoc.com/magento-2-extensions.html).

**THANK FOR CHOOSING** [![](https://fstorage.aitoc.com/documentation/smtp-m2/ext.png)](https://fstorage.aitoc.com/documentation/smtp-m2/ext.png)
