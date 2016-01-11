<?php
/**
 * Commercing plugin for Craft CMS
 *
 * Commercing Controller
 *
 * --snip--
 * Generally speaking, controllers are the middlemen between the front end of the CP/website and your plugin’s
 * services. They contain action methods which handle individual tasks.
 *
 * A common pattern used throughout Craft involves a controller action gathering post data, saving it on a model,
 * passing the model off to a service, and then responding to the request appropriately depending on the service
 * method’s response.
 *
 * Action methods begin with the prefix “action”, followed by a description of what the method does (for example,
 * actionSaveIngredient()).
 *
 * https://craftcms.com/docs/plugins/controllers
 * --snip--
 *
 * @author    Vadim Goncharov
 * @copyright Copyright (c) 2016 Vadim Goncharov
 * @link      http://owl-design.net
 * @package   Commercing
 * @since     0.0.1
 */

namespace Craft;

class CommercingController extends BaseController
{

  /**
   * @var    bool|array Allows anonymous access to this controller's actions.
   * @access protected
   */
  protected $allowAnonymous = array('actionIndex');
  
  /**
   * Handle a request going to our plugin's index action URL, e.g.: actions/commercing
   */
  public function actionIndex() {   

    $plugin     = craft()->plugins->getPlugin('commercing');
    $settings   = craft()->plugins->getPlugin('commercing')->getSettings();

    $commerce         = craft()->plugins->getPlugin('commerce');

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
    // echo "The most occuring value is $mostOccuring with $productList[$mostOccuring] occurences.";

    $popularProduct = [];
    $popularProduct['product'] = craft()->commerce_products->getProductById($mostOccuring);
    $popularProduct['occurances'] = $productList[$mostOccuring];
    // $order = craft()->commerce_orders->getOrderById($keys[0]);

    // craft()->elements->getCriteria('Commerce_Order', $criteria);
    // $order = craft()->commerce_orders->getOrderByNumber('39287d68ecec695c78803fefcca9b67f');

    $variables['title']       = 'Commercing';
    $variables['plugin']      = $plugin;
    $variables['settings']    = $settings;
    $variables['commerce']    = $commerce;

    $variables['orders']            = $orders;
    $variables['popularProduct']    = $popularProduct;
    $variables['totalPrice']        = $totalPrice;
    $variables['ordersProcessing']  = $ordersProcessing;
    $variables['ordersShipped']     = $ordersShipped;

    return $this->renderTemplate('commercing/index', $variables);

  }
}






