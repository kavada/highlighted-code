<?php

/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
use Magento\Framework\App\Action\Action;

// @codingStandardsIgnoreFile

?>
<?php
/**
 * Product list template
 *
 * @var $block \Magento\Catalog\Block\Product\ListProduct
 */
?>
<?php
$_productCollection = $block->getLoadedProductCollection();
$_helper = $this->helper('Magento\Catalog\Helper\Output');
?>
<?php if (!$_productCollection->count()): ?>
    <div class="message info empty"><div><?php /* @escapeNotVerified */ echo __('We can\'t find products matching the selection.') ?></div></div>
<?php else: ?>
    <?php // echo $block->getToolbarHtml() ?>
    <?php echo $block->getAdditionalHtml() ?>
    <?php
    if ($block->getMode() == 'grid') {
        $viewMode = 'grid';
        $image = 'category_page_grid';
        $showDescription = false;
        $templateType = \Magento\Catalog\Block\Product\ReviewRendererInterface::SHORT_VIEW;
    } else {
        $viewMode = 'list';
        $image = 'category_page_list';
        $showDescription = true;
        $templateType = \Magento\Catalog\Block\Product\ReviewRendererInterface::FULL_VIEW;
    }
    /**
     * Position for actions regarding image size changing in vde if needed
     */
    $pos = $block->getPositioned();
    ?>

    <style>
        .sidebar-main {
            display: none;
        }
    </style>
    
    <!-- Start ~ Sidebar -->
    <div class="list-page-sidebar grf-products-menu grf-25">
        <div class="grf-products-menu-container">
            <div class="grf-products-menu-filter-title grf-wrapper">filter</div>
            <div class="grf-products-menu-section grf-products-menu-device">
                <div class="grf-products-menu-title">Device
                    <div class="grf-products-menu-expand">+</div>
                </div>
                <div class="grf-products-menu-content"></div>
            </div>
            <div class="grf-products-menu-section grf-products-menu-color">
                <div class="grf-products-menu-title">Color
                    <div class="grf-products-menu-expand">+</div>
                </div>
                <div class="grf-products-menu-content">
                    <div class="grf-products-menu-colors grf-wrapper"></div>
                </div>
            </div>
            <div class="grf-products-menu-section grf-products-menu-price">
                <div class="grf-products-menu-title">Price
                    <div class="grf-products-menu-expand">+</div>
                </div>
                <div class="grf-products-menu-content"></div>
            </div>
            <div class="grf-products-menu-loader">
                <div class="grf-wrapper">
                    <div class="loader" id="loader-2"><span></span><span></span><span></span></div>
                </div>
            </div>
        </div>
    </div>
    <!-- End ~ Sidebar -->

    <!-- Start ~ Product Grid Overlay -->
    <div class="grf-products-overlay"></div>
    <!-- End ~ Product Grid Overlay -->

    <!-- Start ~ Toolbar -->
    <div class="list-page-toolbar grf-25">
        <div class="grf-products-menu-btn">
            <div class="grf-wrapper">
                <div class="grf-products-menu-line grf-25"></div>
                <div class="grf-products-menu-line grf-25"></div>
                <div class="grf-products-menu-line grf-25"></div>
            </div>
        </div>
        <div class="grf-products-list-toolbar grf-wrapper">
            <div class="grf-products-list-toolbar-group grf-wrapper">
                <div class="grf-products-list-toolbar-title">Product</div>
                <div class="grf-products-list-toolbar-list grf-wrapper">
                    <div class="grf-products-list-toolbar-item active" data-filter="featured">featured</div>
                    <div class="grf-products-list-toolbar-item" data-filter="newest">newest</div>
                    <div class="grf-products-list-toolbar-item" data-filter="oldest">oldest</div>
                </div>
            </div>
            <div class="grf-products-list-toolbar-group grf-wrapper">
                <div class="grf-products-list-toolbar-title">Price</div>
                <div class="grf-products-list-toolbar-list grf-wrapper">
                    <div class="grf-products-list-toolbar-item" data-filter="sale">on sale</div>
                    <div class="grf-products-list-toolbar-item" data-filter="highest">highest</div>
                    <div class="grf-products-list-toolbar-item" data-filter="lowest">lowest</div>
                </div>
            </div>
        </div>
        <!-- <div class="grf-list-page-btn"></div> -->
    </div>
    <!-- End ~ Toolbar -->

    <!-- Start ~ Product Grid -->
    <div class="products grf-products-wrapper list-page grf-products-list-wrapper grf-25 wrapper <?php /* @escapeNotVerified */ echo $viewMode; ?> products-<?php /* @escapeNotVerified */ echo $viewMode; ?>">
        <?php $iterator = 1; $grfItem = 1; ?>
        <div class="products list items product-items list-page-grid grf-wrapper" style="justify-content: flex-start;">
            <?php /** @var $_product \Magento\Catalog\Model\Product */ ?>
            <?php foreach ($_productCollection as $_product):

                // Date product was created
                $date1 = new DateTime($_product->getCreatedAt());
                // Todays datetime
                $date2 = new DateTime();
                // Calculates the difference of the two dates
                $interval = $date1->diff($date2);
                // Gets the time in days from when the product was created
                $days = $interval->days;
                // Gets the price of the product
                $price = $_product->getFinalPrice();
                // Gets the product device type id
                $device = $_product->getResource()->getAttribute('devices')->getFrontend()->getValue($_product);
                if(!$device) {
                    $device = 'N/A';
                }
                // Gets the product device type value
                $devices = $_product->getDevices();
                
                /* @escapeNotVerified */ echo($iterator++ == 1) ? '<div class="item grf-product grf-product-'.$grfItem.' grf-xs-100 grf-sm-50 grf-md-33 grf-lg-33 grf-wrapper product product-item" data-id="'.$grfItem.'" data-price="" data-created-day="'.$days.'" data-sale="" data-devices="'.$device.'" data-device="'.$device.'">' : '</div><div class="item grf-product grf-product-'.$grfItem.' grf-xs-100 grf-sm-50 grf-md-33 grf-lg-33 grf-wrapper product product-item" data-id="'.$grfItem.'" data-price="" data-created-day="'.$days.'" data-sale="" data-devices="'.$device.'" data-device="'.$device.'">' ?>

                <div class="product-item-info grf-product-item grf-wrapper" data-container="product-grid">
                    <?php
                    $productImage = $block->getImage($_product, $image);
                    if ($pos != null) {
                        $position = ' style="left:' . $productImage->getWidth() . 'px;'
                            . 'top:' . $productImage->getHeight() . 'px;"';
                    }
                    ?>

                    <div class="grf-product-item-container">

                        <a href="<?php /* @escapeNotVerified */ echo $_product->getProductUrl() ?>" class="product photo product-item-photo" tabindex="-1">
                            <?php echo $productImage->toHtml(); ?>
                        </a>
                        <div class="product details product-item-details grf-product-details-<?php echo $grfItem; ?>" data-id="<?php echo $grfItem; ?>">
                            <?php
                                $_productNameStripped = $block->stripTags($_product->getName(), null, true);
                            ?>
                            <?php echo $block->getReviewsSummaryHtml($_product, $templateType); ?>
                            <?php echo $block->getProductDetailsHtml($_product); ?>

                            <div class="product-item-inner">
                                <div class="grf-product-item-info">
                                    <div class="grf-product-item-name">
                                        <a class="product-item-link"
                                           href="<?php /* @escapeNotVerified */ echo $_product->getProductUrl() ?>">
                                           <div class="product name product-item-name">
                                            <?php
                                                $name = explode(' for', $_helper->productAttribute($_product, $_product->getName(), 'name'));
                                                echo $name[0];
                                            ?>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="grf-product-item-price"></div>
                                </div>
                                <div class="grf-product-item-swatches grf-wrapper"></div>
                                <div class="product actions product-item-actions"<?php echo strpos($pos, $viewMode . '-actions') ? $position : ''; ?>>
                                    <div class="actions-primary"<?php echo strpos($pos, $viewMode . '-primary') ? $position : ''; ?>>
                                        <?php if ($_product->isSaleable()): ?>
                                            <?php $postParams = $block->getAddToCartPostParams($_product); ?>

                                            
                                            <form data-role="tocart-form" action="<?php /* @escapeNotVerified */ echo $postParams['action']; ?>" method="post">
                                                <input type="hidden" name="product" value="<?php /* @escapeNotVerified */ echo $postParams['data']['product']; ?>">
                                                <input type="hidden" name="<?php /* @escapeNotVerified */ echo Action::PARAM_NAME_URL_ENCODED; ?>" value="<?php /* @escapeNotVerified */ echo $postParams['data'][Action::PARAM_NAME_URL_ENCODED]; ?>">
                                                <?php echo $block->getBlockHtml('formkey')?>
                                                <div class="grf-wrapper grf-product-item-add-container">
                                                    <div class="grf-product-item-price-container">
                                                        <?php echo $block->getProductPrice($_product) ?>
                                                    </div>
                                                    <div class="grf-product-item-add">
                                                        <!-- <button type="submit"
                                                                title="<?php # echo $block->escapeHtml(__('Add to Cart')); ?>"
                                                                class="action tocart primary">
                                                            <span><?php /* @escapeNotVerified */ # echo __('Add to Cart') ?></span>
                                                        </button> -->
                                                        <?php echo $block->getButton($_product)?>
                                                    </div>
                                                </div>
                                            </form>
                                        <?php else: ?>
                                            <div class="grf-wrapper grf-product-item-availability-container">
                                                <?php if ($_product->getIsSalable()): ?>
                                                    <div class="stock available"><span><?php /* @escapeNotVerified */ echo __('In stock') ?></span></div>
                                                <?php else: ?>
                                                    <div class="stock unavailable"><span><?php /* @escapeNotVerified */ echo __('Out of stock') ?></span></div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div style="display: none;" data-role="add-to-links" class="actions-secondary"<?php echo strpos($pos, $viewMode . '-secondary') ? $position : ''; ?>>
                                        <?php if ($addToBlock = $block->getChildBlock('addto')): ?>
                                            <?php echo $addToBlock->setProduct($_product)->getChildHtml(); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php if ($showDescription):?>
                                    <div class="product description product-item-description">
                                        <?php /* @escapeNotVerified */ echo $_helper->productAttribute($_product, $_product->getShortDescription(), 'short_description') ?>
                                        <a href="<?php /* @escapeNotVerified */ echo $_product->getProductUrl() ?>" title="<?php /* @escapeNotVerified */ echo $_productNameStripped ?>"
                                           class="action more"><?php /* @escapeNotVerified */ echo __('Learn More') ?></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo($iterator == count($_productCollection)+1) ? '</div>' : '' ?>
                <?php $grfItem++; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- End ~ Product Grid -->

    <?php echo $block->getToolbarHtml() ?>
    <?php if (!$block->isRedirectToCartEnabled()) : ?>
        <script type="text/x-magento-init">
        {
            "[data-role=tocart-form], .form.map.checkout": {
                "catalogAddToCart": {}
            }
        }
        </script>
    <?php endif; ?>
<?php endif; ?>

<script type="text/javascript">

    require([
      'jquery',
      'jquery/ui',
      'moment'
    ], function($){

        function lazyImageLoader() {
            var image, count;

            image = 0; // starting image
            count = 6; // image count to add the lazyloader to on initial page load

            $('.grf-product-item-image').each(function() {
                
                var item, src;
                item = $(this);
                src = item.data('image');
                
                if(src.includes('placeholder')) {
                    item.parent().siblings('.product-item-details').children('.grf-swatch-container').children('.swatch-attribute').children('.swatch-attribute-options').children('.grf-swatch-option-container:first-child').click();
                }

                if(image < count) {
                    item.css({'background':'url('+src+')','background-size':'contain','background-position':'center center','background-repeat':'no-repeat'}).addClass('grf-lazy-load');
                }
                else {
                    item.addClass('grf-no-lazy-load');
                }

                image++;

            });

            setTimeout(function() {
                $('.grf-product-item-image.grf-no-lazy-load').each(function() {

                    var item, src;
                    item = $(this);
                    src = item.data('image');

                    if(src.includes('placeholder')) {
                        item.parent().siblings('.product-item-details').children('.grf-swatch-container').children('.swatch-attribute').children('.swatch-attribute-options').children('.grf-swatch-option-container:first-child').click();
                    }

                    item.css({'background':'url('+src+')','background-size':'contain','background-position':'center center','background-repeat':'no-repeat'}).removeClass('grf-no-lazy-load').addClass('grf-lazy-load');

                });
            }, 2500);

        }

        lazyImageLoader();
        
        $('.grf-products-list-toolbar-btn').on('click', function() {
            $('.grf-products-menu').addClass('active');
            $('.grf-products-menu-overlay').fadeIn();
        });

        $('.grf-products-menu-overlay').on('click', function() {
             $('.grf-products-menu').removeClass('active');
             $('.grf-products-menu-overlay').fadeOut();
        });

        var title = $('#page-title-heading span').text();
        $('.grf-category-cover-title').html(title);

        var device, devices = [];

        /* Get Unique Data */
        function unique(arr, comparator) {
          var uniqueArr = [];
          for (var i in arr) {
            var found = false;
            for (var j in uniqueArr) {
              if (comparator instanceof Function) {
                if (comparator.call(null, arr[i], uniqueArr[j])) {
                  found = true;
                  break;
                }
              } else {
                if (arr[i] == uniqueArr[j]) {
                  found = true;
                  break;
                }
              }
            }
            if (!found) {
              uniqueArr.push(arr[i]);
            }
          }
          return uniqueArr;
        };

        function dynamicSort(property) {
            var sortOrder = 1;
            if(property[0] === "-") {
                sortOrder = -1;
                property = property.substr(1);
            }
            return function (a,b) {
                var result = (a[property] < b[property]) ? -1 : (a[property] > b[property]) ? 1 : 0;
                return result * sortOrder;
            }
        }

        $('.grf-products-menu-btn').on('click', function() {
            var width, sidebar, toolbar, grid;
            
            width = $(window).width();
            sidebar = $('.list-page-sidebar');
            toolbar = $('.list-page-toolbar');
            grid = $('.list-page');

            sidebar.toggleClass('moved');
            toolbar.toggleClass('moved');
            grid.toggleClass('moved');

        });

        $('.grf-products-overlay').on('click', function() {
            $('.grf-products-menu, .grf-products-list').addClass('active');
            $('.grf-products-overlay').removeClass('active');
            $('.grf-products-menu-btn').addClass('open');
        });

        $('.grf-products-menu-title').on('click', function() {
            var item;
            item = $(this);
            item.siblings('.grf-products-menu-content').toggle();
        });

        setTimeout(function() {
            $('.grf-category-cover').addClass('active');
            $('.grf-products-menu-btn').click();
        }, 1500);

        // Update available prices in menu
        function updatePrices(arr) {
            var menuItem;

            menuItem = $('.grf-products-menu-price .grf-products-menu-item');
            menuItem.addClass('grf-no-match').removeClass('grf-has-match');

            menuItem.each(function() {
                var item, min, max;
                item = $(this);
                min = item.data('min');
                max = item.data('max');
                
                for (var i = 0; i < arr.length; i++) {
                    if(arr[i] > min && arr[i] < max) {
                        item.addClass('grf-has-match').removeClass('grf-no-match');
                    }
                }

            });

        }

        // filter
        function theFilter(name,operator) {
            var $grid = $('.list-page-grid'), $product = $grid.children('.grf-product');
            $product.sort(function(a,b){
                var an = parseInt(a.getAttribute(name)), bn = parseInt(b.getAttribute(name));
                switch(operator) {
                    case 'greater':
                        if(an > bn) { return 1; }
                        if(an < bn) { return -1; }
                        return 0;
                    break
                    case 'less':
                        if(an < bn) { return 1; }
                        if(an > bn) { return -1; }
                        return 0;
                    break;
                }
            });
            $product.detach().appendTo($grid);
        }

        $('.grf-products-list-toolbar-item').on('click', function() {
            var item, filter;
            item = $(this);
            if(item.hasClass('active')) {
                alert('you already selected this item');
            }
            else {
                $('.grf-products-list-toolbar-item').removeClass('active');
                item.addClass('active');
                filter = item.data('filter');
                switch(filter) {
                    case 'featured':
                        theFilter('data-id','greater');
                    break;
                    case 'newest':
                        theFilter('data-created-day','greater');
                    break;
                    case 'oldest':
                        theFilter('data-created-day','less');
                    break;
                    case 'sale':
                        theFilter('data-sale','less');
                    break;
                    case 'highest':
                        theFilter('data-price','less');
                    break;
                    case 'lowest':
                        theFilter('data-price','greater');
                    break;
                }
            }
            $('html, body').animate({
                scrollTop: $('.grf-products-list-wrapper').offset().top - 54
            }, 300);
        });

        $('.grf-product').each(function() {
            var item, sale, container, deviceChildren, devicesContainer, priceBox, priceB, priceData, finalPrice, oldPrice, specialPrice;
            item = $(this);
            // container of product
            container = item.children('.grf-product-item').children('.grf-product-item-container');
            // device container of product
            container.append('<div class="grf-product-item-devices grf-wrapper"></div>')
            devicesContainer = container.children('.grf-product-item-devices');
            // device value
            device = item.data('device');

            priceBox = item.find('.price-box');
            priceB = item.find('.price-box > span');

            priceB.each(function() {
                var priceBlock;
                priceBlock = $(this);
                if(priceBlock.hasClass('price-final_price')) {
                    finalPrice = priceBlock.find('.price').text();
                }
                else {
                    switch(priceBlock.attr('class')) {
                        case 'special-price':
                            specialPrice = priceBlock.find('.price').text();
                        break;
                        case 'old-price':
                            oldPrice = priceBlock.find('.price').text();
                        break;
                        case 'old-price sly-old-price no-display':
                            oldPrice = priceBlock.find('.price').text();
                        break;
                    }
                }
            });

            priceContainer = item.find('.grf-product-item-price');

            if(specialPrice == undefined && oldPrice == undefined && finalPrice == undefined) {
                // product is not available
            }
            else {
                if(specialPrice != undefined) {
                    priceData = '<div class="grf-product-item-price-onsale grf-wrapper"><div class="grf-product-item-price-base">'+oldPrice+'</div><div class="grf-product-item-price-special">'+specialPrice+'</div></div>';
                    item.attr('data-sale',1);
                    item.attr('data-price',specialPrice.replace('$',''));
                    sale = 1;
                }
                else {
                    priceData = '<div class="grf-product-item-price-regular grf-wrapper"><div class="grf-product-item-price-final">'+finalPrice+'</div></div>';
                    item.attr('data-sale',0);
                    item.attr('data-price',finalPrice.replace('$',''));
                    sale = 0;
                }
            }

            priceContainer.html(priceData);

            // let grfRating = $('.grf-product-item-price-container > div:first-child').detach();
            // $('.grf-product-item-info').append(grfRating);

            // if has device value
            if(device && device.length > 1) {
                // if product has more than one associated device
                if(device.includes(',')) {
                    deviceChildren = device.split(', ');
                    for (var i = 0; i < deviceChildren.length; i++) {
                        devices.push(deviceChildren[i]);
                        devicesContainer.append('<div class="grf-product-item-device">'+deviceChildren[i]+'</div>');
                    }
                }
                // if product has single device value
                else {
                    devices.push(device);
                    devicesContainer.append('<div class="grf-product-item-device">'+device+'</div>');
                }
            }
            // if product does not have device value
            else {
                devices.push('N/A');
                devicesContainer.append('<div class="grf-product-item-device">N/A</div>');
            }

            // add sale CTA to product
            if(sale === 1) {
                container.append('<div class="grf-product-item-cta">On Sale</div>');
            }

        });

        // Check to see if category has filter options
        var hasFilterOptions = $('.sidebar .filter .item').size();
        var catDevices = [];
        if(hasFilterOptions > 0) {
            $('.sidebar .filter .item a').each(function() {
                var item;
                item = $(this);
                catDevice = item.text();
                $('.grf-products-menu-device .grf-products-menu-content').append('<div class="grf-products-menu-item grf-wrapper" data-name="'+catDevice.replace(/\s+/g, '-').toLowerCase()+'">'+catDevice+'</div>');
            });
            console.log('has filter options');
        }
        else {
            console.log('category is product specific');
            devicex = unique(devices).sort();
            if(devicex.length > 1) {
                for (var i = 0; i < devicex.length; i++) {
                    $('.grf-products-menu-device .grf-products-menu-content').append('<div class="grf-products-menu-item grf-wrapper" data-name="'+devicex[i].replace(/\s+/g, '-').toLowerCase()+'">'+devicex[i]+'</div>');
                }
            }
            else {
                $('.grf-products-menu-device').hide();
            }
        }

        // Adds a clear device option to the menu -> this will cancel out the device filter when clicked (see clear device below)
        $('.grf-products-menu-device .grf-products-menu-content').prepend('<div class="grf-products-menu-clear">clear device</div>');

        // Device filter click event
        $('.grf-products-menu-device .grf-products-menu-item').on('click', function() {
            
            var item, name;
            item = $(this);
            name = item.data('name');

            if(item.hasClass('grf-no-match')) {
                alert('This choice does not have any options');
            }
            else {
                if(item.hasClass('active')) {
                    alert('you already selected this item');
                }
                else {
                    // Remove device availabilty from each product
                    $('.product-item').removeClass('grf-product-device-available').addClass('grf-product-device-unavailable');

                    $('.grf-products-menu-device .grf-products-menu-item').removeClass('active');
                    item.addClass('active');

                    // Loop through each product and get the value of each device
                    $('.product-item').each(function() {
                        var product, device;
                        product = $(this);
                        device = product.data('device');

                        if(device) {
                            device = device.replace(/\s+/g, '-').toLowerCase();
                            if(device.includes(name)) {
                                product.addClass('grf-product-device-available').removeClass('grf-product-device-unavailable');
                            }
                        }

                    });
                }
            }

            // Scroll to top of product list
            $('html, body').animate({
                scrollTop: $('.grf-products-list-wrapper').offset().top - 54
            }, 300);

        });

        // Clear device
        $('.grf-products-menu-device .grf-products-menu-clear').on('click', function() {
            $('.product-item').removeClass('grf-product-device-available grf-product-device-unavailable');
            $('.grf-products-menu-device .grf-products-menu-item').removeClass('active');

            $('html, body').animate({
                scrollTop: $('.grf-products-list-wrapper').offset().top - 54
            }, 300);
        });

        // Price Filter
        function priceFilter() {

            var prices = {

                1: {
                    title: '$0.00 - $25.00',
                    min: 0,
                    max: 25
                },
                2: {
                    title: '$25.00 - $50.00',
                    min: 25,
                    max: 50
                },
                3: {
                    title: '$50.00 - $100.00',
                    min: 50,
                    max: 100
                },
                4: {
                    title: '$100.00+',
                    min: 100,
                    max: 10000
                }

            };

            for (var i = 1; i <= Object.keys(prices).length; i++) {
                $('.grf-products-menu-price .grf-products-menu-content').append('<div class="grf-products-menu-item grf-wrapper" data-min="'+prices[i].min+'" data-max="'+prices[i].max+'">'+prices[i].title+'</div>');
            }

            // Adds a clear price option to the menu -> this will cancel out the price filter when clicked (see clear price below)
            $('.grf-products-menu-price .grf-products-menu-content').prepend('<div class="grf-products-menu-clear">clear price</div>');

            // Device filter click event
            $('.grf-products-menu-price .grf-products-menu-item').on('click', function() {
                
                var item, min, max;
                item = $(this);
                min = item.data('min');
                max = item.data('max');

                if(item.hasClass('active')) {
                    alert('you already selected this item');
                }
                else {
                    // Remove price availabilty from each product
                    $('.grf-product').removeClass('grf-product-price-available').addClass('grf-product-price-unavailable');

                    $('.grf-products-menu-price .grf-products-menu-item').removeClass('active');
                    item.addClass('active');

                    // Loop through each product and get the value of each price
                    $('.grf-product').each(function() {
                        var product, price;
                        product = $(this);
                        price = product.data('price');

                        if(price >= min && price < max) {
                            product.addClass('grf-product-price-available').removeClass('grf-product-price-unavailable');
                        }

                    });
                }

                // Scroll to top of product list
                $('html, body').animate({
                    scrollTop: $('.grf-products-list-wrapper').offset().top - 54
                }, 300);

            });

            // Clear price
            $('.grf-products-menu-price .grf-products-menu-clear').on('click', function() {
                $('.product-item').removeClass('grf-product-price-available grf-product-price-unavailable');
                $('.grf-products-menu-price .grf-products-menu-item').removeClass('active');

                $('html, body').animate({
                    scrollTop: $('.grf-products-list-wrapper').offset().top - 54
                }, 300);
            });

        }

        priceFilter();

        $('.grf-product-item-qv').on('click', function() {
            var item;
            item = $(this);
        });

        function grfColors() {

            var swatch;
            swatch = $('.grf-swatch-container').length;
            
            if(swatch > 1) {

                var color, colors = [];

                $('.grf-swatch-option-container').each(function() {
                    var item, label, value;
                    item = $(this);
                    label = item.attr('option-label');
                    value = item.attr('data-parent');
                    colors.push(value);
                });

                color = unique(colors);

                for (var i = 0; i < color.length; i++) {
                    swatch = '<div class="grf-swatch-hex-box" style="position: relative"><div class="grf-swatch-hex" style="background: '+color[i]+'"></div></div>';
                    $('.grf-products-menu-colors').append('<div class="grf-products-menu-item grf-wrapper" data-name="'+color[i]+'"><div class="grf-products-menu-item-swatch">'+swatch+'</div></div>');
                }

                $('.grf-products-menu-loader').fadeOut();

                // Adds a clear color option to the menu -> this will cancel out the color filter when clicked (see clear color below)
                $('.grf-products-menu-color .grf-products-menu-content').prepend('<div class="grf-products-menu-clear">clear color</div>');

                // // Color filter click event
                $('.grf-products-menu-color .grf-products-menu-item').on('click', function() {
                    
                    var width, item, name;
                    width = $(window).width();
                    item = $(this);
                    name = item.data('name');

                    if(item.hasClass('active')) {
                        alert('you already selected this item');
                    }
                    else {
                        $('.swatch-attribute-options').removeClass('active');
                        // Remove color availabilty from each product
                        $('.grf-product').removeClass('grf-product-color-available').addClass('grf-product-color-unavailable');
                        // Remove active state on color filter in sidebar
                        $('.grf-products-menu-color .grf-products-menu-item').removeClass('active');
                        item.addClass('active');
                        // Loop through each product and get the value of each color
                        $('.grf-product').each(function() {
                            var product, color;
                            product = $(this);
                            color = product.data('color');
                            color = color.split(',');

                            for (var i = 0; i < color.length; i++) {
                                if(color[i] === name) {
                                    product.addClass('grf-product-color-available').removeClass('grf-product-color-unavailable');
                                }
                            }

                        });

                        $('.grf-swatch-option-container').each(function() {
                            var swatch, parent, swatchParent;
                            swatch = $(this);
                            // label = swatch.attr('option-label').replace(/\s+/g, `-`).toLowerCase();
                            parent = swatch.parent();
                            swatchParent = swatch.data('parent');
                            // If product has the chosen color
                            if(parent.hasClass('active')) {

                            }
                            else {
                                if(swatchParent === name) {
                                    if(swatch.hasClass('selected')) {

                                    }
                                    else {
                                        swatch.click();
                                        parent.addClass('active');
                                    }
                                }
                            }
                        });

                        if(width < 1350) {
                            $('.grf-products-menu-btn').click();
                        }

                    }

                    // Scroll to top of product list
                    $('html, body').animate({
                        scrollTop: $('.grf-products-list-wrapper').offset().top - 54
                    }, 300);

                });

                // Clear color
                $('.grf-products-menu-color .grf-products-menu-clear').on('click', function() {
                    $('.product-item').removeClass('grf-product-color-available grf-product-color-unavailable');
                    $('.grf-products-menu-color .grf-products-menu-item').removeClass('active');

                    $('html, body').animate({
                        scrollTop: $('.grf-products-list-wrapper').offset().top - 54
                    }, 300);
                });

                clearInterval(handle);

            }
            else {
                $('.grf-products-menu-color').hide();
                $('.grf-products-menu-loader').fadeOut();
                clearInterval(handle);
            }

            $('.grf-product .product-item-details').each(function() {
                let item;
                item = $(this);
                if(item.hasClass('grf-has-swatch-options')) {

                }
                else {
                    item.prepend('<div class="grf-faux-swatch-container"></div>');
                }
            });

        }

        var handle = setInterval(grfColors, 1000);
        setTimeout(function() {
            clearInterval(handle);
        }, 10000);

        $(document).scroll(function() {

            var pos, cover, sidebar, toolbar;
            pos = $(this).scrollTop();
            cover = $('.grf-category-cover');
            sidebar = $('.list-page-sidebar');
            toolbar = $('.list-page-toolbar');

            if(pos > cover.height() + 35) {
                sidebar.addClass('active');
                toolbar.addClass('active');
            }
            else {
                sidebar.removeClass('active');
                toolbar.removeClass('active');
            }

            if( $('.grf-footer-menu').isOnScreen() ) {
                sidebar.hide();
                toolbar.hide();
            }
            else {
                sidebar.css({'display':'flex'});
                toolbar.css({'display':'flex'});
            }

        });

        function listSetup() {
            var width, sidebar, toolbar, grid;
            
            width = $(window).width();
            sidebar = $('.list-page-sidebar');
            toolbar = $('.list-page-toolbar');
            grid = $('.list-page');

            if(width < 1350) {
                sidebar.addClass('moved');
                toolbar.addClass('moved');
                grid.addClass('moved');
            }
            else {
                sidebar.removeClass('moved');
                toolbar.removeClass('moved');
                grid.removeClass('moved');
            }
        }

        listSetup();

        $(window).resize(function() {
            listSetup();
        });

    });

</script>