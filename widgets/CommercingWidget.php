<?php
/**
 * Commercing plugin for Craft CMS
 *
 * Commercing Widget
 *
 * --snip--
 * Dashboard widgets allow you to display information in the Admin CP Dashboard.  Adding new types of widgets to
 * the dashboard couldn’t be easier in Craft
 *
 * https://craftcms.com/docs/plugins/widgets
 * --snip--
 *
 * @author    Vadim Goncharov
 * @copyright Copyright (c) 2016 Vadim Goncharov
 * @link      http://owl-design.net
 * @package   Commercing
 * @since     0.0.1
 */

namespace Craft;

class CommercingWidget extends BaseWidget
{

    /**
     * Returns the name of the widget name.
     *
     * @return mixed
     */
    public function getName()
    {
        return Craft::t('Commercing');
    }

    /**
     * getBodyHtml() does just what it says: it returns your widget’s body HTML. We recommend that you store the
     * actual HTML in a template, and load it via craft()->templates->render().
     *
     * @return mixed
     */
    public function getBodyHtml()
    {
        // Widget settings
        $settings = $this->getSettings();
        
        $allOrders = craft()->elements->getCriteria('Commerce_Order', null);
        $orders = $allOrders->find();

        $totalPrice = 0;
        foreach ($orders as $key => $order) {
          $attributes = $order->getAttributes();
          // $date = new DateTime($attributes['dateOrdered']);
          // $date->getTimestamp();
          if ($attributes['datePaid']) {
            $totalPrice += $attributes['totalPrice'];
          }
        }


        // Orders
        $productListIds = [];
        $ordersProcessing = 0;
        $ordersShipped = 0;

        foreach ($orders as $key => $order) {

          // Processing Orders 
          if ($order->getAttributes()['orderStatusId'] == 1) {
            $ordersProcessing++;
          }

          // Shipped Orders 
          if ($order->getAttributes()['orderStatusId'] == 2) {
            $ordersShipped++;
          }

          // Get Products
          $lineItems = $order->getLineItems();
          foreach ($lineItems as $key => $product) {
            $productListIds[] = $product['snapshot']['productId'];
          }
        }

        // Get Most Sold Product
        $productList = array_count_values($productListIds);
        arsort($productList);
        $occurances = array_keys($productList);
        $mostOccuring = $occurances[0];

        $popularProduct = [];
        $popularProduct['product'] = craft()->commerce_products->getProductById($mostOccuring);
        $popularProduct['occurances'] = $productList[$mostOccuring];

        $variables['settings']          = $settings;
        $variables['orders']            = $orders;
        $variables['popularProduct']    = $popularProduct;
        $variables['totalPrice']        = $totalPrice;
        $variables['ordersProcessing']  = $ordersProcessing;
        $variables['ordersShipped']     = $ordersShipped;

        return craft()->templates->render('commercing/widgets/index', $variables);
    }

    /**
     * Returns how many columns the widget will span in the Admin CP
     *
     * @return int
     */
    public function getColspan()
    {
        return 2;
    }

    /**
     * Defines the attributes that model your Widget's available settings.
     *
     * @return array
     */
    protected function defineSettings()
    {
        return array(
            'someSetting' => array(AttributeType::String, 'label' => 'Some Setting', 'default' => ''),
        );
    }

    /**
     * Returns the HTML that displays your Widget's settings.
     *
     * @return mixed
     */
    public function getSettingsHtml()
    {
       return craft()->templates->render('commercing/widgets/settings', array(
           'settings' => $this->getSettings()
       ));
    }

    /**
     * If you need to do any processing on your settings’ post data before they’re saved to the database, you can
     * do it with the prepSettings() method:
     *
     * @param mixed $settings  The Widget's settings
     *
     * @return mixed
     */
    public function prepSettings($settings)
    {
        // Modify $settings here...

        return $settings;
    }
}