<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    design
 * @package     rwd_default
 * @copyright   Copyright (c) 2014 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
?>
<?php
/**
 * Product list template
 *
 * @see Mage_Catalog_Block_Product_List
 */
/* @var $this Mage_Catalog_Block_Product_List */
?>
<?php
    $_productCollection=$this->getLoadedProductCollection();
    $_helper = $this->helper('catalog/output');
?>
<div class="products-list-wrapper">
<script>
    jQuery(document).ready(function(){
        console.log('jquery is working');
        jQuery('.category-image').append('<div class="the-cat-img-block"></div>');
        var catTitle = jQuery('.category-title').detach();
        jQuery('.the-cat-img-block').append(catTitle);
    });
</script>
<div class="tvk-simple-ajax-data"></div>
<?php if(!$_productCollection->count()): ?>
<p class="note-msg"><?php echo $this->__('There are no products matching the selection.') ?></p>
<?php else: ?>
<div class="category-products">
    <?php echo $this->getToolbarHtml() ?>
    <?php // List mode ?>
    <?php if($this->getMode()!='grid'): ?>
    <?php $_iterator = 0; ?>
    <ol class="products-list" id="products-list">
    <?php foreach ($_productCollection as $_product): ?>

        <li class="item<?php if( ++$_iterator == sizeof($_productCollection) ): ?> last<?php endif; ?>">
            <?php // Product Image ?>
            <a href="<?php echo $_product->getProductUrl() ?>" title="<?php echo $this->stripTags($this->getImageLabel($_product, 'small_image'), null, true) ?>" class="product-image">

                <?php $_imgSize = 350; ?>
                <img id="product-collection-image-<?php echo $_product->getId(); ?>"
                     data-original="<?php echo $this->helper('catalog/image')->init($_product, 'small_image')->keepFrame(false)->resize($_imgSize); ?>"
                     alt="<?php echo $this->stripTags($this->getImageLabel($_product, 'small_image'), null, true) ?>" />
                
            </a>
            <?php // Product description ?>
            <div class="product-shop">
                <div class="f-fix">
                    <div class="product-primary">
                        <?php $_productNameStripped = $this->stripTags($_product->getName(), null, true); ?>
                        <h2 class="product-name"><a href="<?php echo $_product->getProductUrl() ?>" title="<?php echo $_productNameStripped; ?>"><?php echo $_helper->productAttribute($_product, $_product->getName() , 'name'); ?></a></h2>

                        <h2 class="product-device"><?php echo $_helper->productAttribute($_product, $_product->getDeviceType() , 'device_type'); ?></h2>
                            
                        <?php if($_product->getRatingSummary()): ?>
                        <?php echo $this->getReviewsSummaryHtml($_product) ?>
                        <?php endif; ?>

                        <?php
                            $_nameAfterChildren = $this->getChild('name.after')->getSortedChildren();
                            foreach($_nameAfterChildren as $_nameAfterChildName):
                                $_nameAfterChild = $this->getChild('name.after')->getChild($_nameAfterChildName);
                                $_nameAfterChild->setProduct($_product);
                        ?>
                            <?php echo $_nameAfterChild->toHtml(); ?>
                        <?php endforeach; ?>
                    </div>
                    <div class="product-secondary">
                        <?php echo $this->getPriceHtml($_product, true) ?>
                    </div>
                    <div class="product-secondary">
                        <?php if($_product->isSaleable() && !$_product->canConfigure()): ?>
                            <p class="action"><button type="button" title="<?php echo $this->__('Add to Cart') ?>" class="button btn-cart btn btn-default" onclick="setLocation('<?php echo $this->getAddToCartUrl($_product) ?>')"><span><span><?php echo $this->__('Add to Cart') ?></span></span></button></p>
                        <?php elseif($_product->isSaleable()): ?>
                            <p class="action"><a title="<?php echo $this->__('View Details') ?>" class="button" href="<?php echo $_product->getProductUrl() ?>"><?php echo $this->__('View Details') ?></a></p>
                        <?php else: ?>
                            <p class="action availability out-of-stock"><span><?php echo $this->__('Out of stock') ?></span></p>
                        <?php endif; ?>
                        <ul class="add-to-links">
                            <?php if ($this->helper('wishlist')->isAllow()) : ?>
                                <li><a href="<?php echo $this->helper('wishlist')->getAddUrl($_product) ?>" class="link-wishlist"><?php echo $this->__('Add to Wishlist') ?></a></li>
                            <?php endif; ?>
                            <?php if($_compareUrl=$this->getAddToCompareUrl($_product)): ?>
                                <li><span class="separator">|</span> <a href="<?php echo $_compareUrl ?>" class="link-compare"><?php echo $this->__('Add to Compare') ?></a></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <div class="desc std">
                        <?php echo $_helper->productAttribute($_product, $_product->getShortDescription(), 'short_description') ?>
                        <a href="<?php echo $_product->getProductUrl() ?>" title="<?php echo $_productNameStripped ?>" class="link-learn"><?php echo $this->__('Learn More') ?></a>
                    </div>
                </div>
            </div>
        </li>
    <?php endforeach; ?>
    </ol>
    <script type="text/javascript">decorateList('products-list', 'none-recursive')</script>

    <?php else: ?>

    <?php // Grid Mode ?>

    <?php $_collectionSize = $_productCollection->count() ?>
    <?php $_columnCount = $this->getColumnCount(); ?>
    <style>
        h2.product-name {
        }
    </style>
    <div class="tvk-product-flex-container">
        <?php $i = 0; foreach ($_productCollection as $_product): ?>
            <?php /*if ($i++%$_columnCount==0): ?>
            <?php endif*/ ?>
            <?php
                
                $special_price = $_product->getFinalPrice();
                $normal_price = $_product->getPrice();
                $mixed_price = $_product->getPriceScale();
                $product = Mage::getModel('catalog/product')->load($_product->getId());
                $configurable= Mage::getModel('catalog/product_type_configurable')->setProduct($product);
                $simpleCollection = $configurable->getUsedProductCollection()->addAttributeToSelect('*')->addFilterByRequiredOptions();
                $d = $i++;
            ?>
            <li class="mm-product-list-item mm-product-list-item-<?php echo ($d+1); ?> item tvk-product-flex <?php if(($i-1)%$_columnCount==0): ?> first<?php elseif($i%$_columnCount==0): ?> last<?php endif; ?>" data-id="<?php echo ($d+1); ?>" data-highlight="<?php echo $product->getCustomHighlight(); ?>">
                <?php
                    $created = array();
                    foreach($simpleCollection as $simpleProduct){
                        if ( in_array($simpleProduct->getCreatedAt(), $created) ) {
                            continue;
                        }
                        $created[] = $simpleProduct->getCreatedAt();
                    }
                    echo '<ul class="mm-product-list-custom-list mm-product-list-custom-list-'.($d+1).'" style="display: none;">';
                    foreach ($created as $crt) {
                        $date = explode(' ', $crt);
                        echo '<li class="mm-product-list-custom-item" data-fdate="'.$crt.'" data-id="'.($d+1).'" data-date="'.$date[0].'" data-price="'.$normal_price.'" data-sprice="'.$special_price.'"></li>';
                    }
                    echo '</ul>';
                ?>
                <a href="<?php echo $_product->getProductUrl() ?>" title="<?php echo $this->stripTags($this->getImageLabel($_product, 'small_image'), null, true) ?>" class="product-image">
                    <?php $_imgSize = '100%'; ?>
                    <?php $imgSrc = str_replace('/cache/1/small_image/900x/9df78eab33525d08d6e5fb8d27136e95/', '/', $this->helper('catalog/image')->init($_product, 'small_image')->resize(900)); ?>
                    
                    <img class="lazy" src="<?php echo $imgSrc ?>" data-src="<?php //echo $imgSrc; ?>" max-width="350" width="100%"  alt="<?php echo $this->stripTags($this->getImageLabel($_product, 'small_image'), null, true) ?>" />
                    <div class="mm-custom-product-cta-x"></div>
                    <div class="mm-custom-image-hover">
                        <?php
                            if(!$product->getHoverImage()){
                                
                            }
                            else {
                                echo '<img src="'.$product->getHoverImage().'" width="100%">';
                            }
                        ?>
                    </div>
                </a>
                <div class="product-info" data-link="<?php echo $_product->getProductUrl() ?>">
                    <?php echo $this->getPriceHtml($_product, true) ?>
                    <h2 class="product-name"><a style="color: #000; font-size: 14px;" href="<?php echo $_product->getProductUrl() ?>" title="<?php echo $this->stripTags($_product->getName(), null, true) ?>"><?php echo $_helper->productAttribute($_product, $_product->getName(), 'name') ?></a></h2>

                    <!-- DEVICE TYPE ADDITION -->
                    <?php if($_product->getDeviceType()) { ?>
                        <h2 style="font-weight: 300 !important; font-size: 12px !important; text-align: left !important;" class="product-name"><?php echo "for " . $_product->getDeviceType(); ?></h2>
                    <?php } ?>

                    <div class="mm-lp-info">
                        <p class="mm-custom-product-cta"></p>
                        <?php
                            if(!$_product->getPriceScale()) {
                                if($special_price < $normal_price) {
                                    echo '<p style="text-decoration: line-through;">$'.number_format($normal_price,2).'</p> <p style="color: red;"> $'.number_format($special_price,2).'</p>';
                                }
                                else {
                                    echo '<p class="tvk-simple-pricing">$ '.number_format($normal_price,2).'</p>';
                                }    
                            }
                            else {
                                echo '<p class="tvk-simple-pricing">'.$_product->getPriceScale().'</p>';
                            }
                            
                        ?>
                        <?php
                            $_nameAfterChildren = $this->getChild('name.after')->getSortedChildren();
                            foreach($_nameAfterChildren as $_nameAfterChildName):
                                $_nameAfterChild = $this->getChild('name.after')->getChild($_nameAfterChildName);
                                $_nameAfterChild->setProduct($_product);
                        ?>
                            <?php echo $_nameAfterChild->toHtml(); ?>
                        <?php endforeach; ?>
                        <?php if($_product->getRatingSummary()): ?>
                        <?php endif; ?>
                        <div class="mm-qa-btn" data-92="" data-133="" data-xid="<?php echo $_product->getId(); ?>" style="display: inline-block; float: right;">
                            <button>add to cart</button>
                        </div>
                        <div class="actions" style="display: none;">
                            <?php if($_product->isSaleable() && $_product->canConfigure()): ?>
                                <button type="button" title="<?php echo $this->__('Quick Add') ?>" class="button btn-cart btn btn-default" onclick="setLocation('<?php echo $this->getAddToCartUrl($_product) ?>')" style="background: transparent !important; border: none !important; color: #000 !important; border-radius: 0 !important; font-size: 11px !important; font-weight: 300 !important;">Add to Cart</button>
                            <?php elseif($_product->isSaleable()): ?>
                                <a title="<?php echo $this->__('Shop Now') ?>" class="button btn btn-primary"  href="<?php echo $_product->getProductUrl() ?>">Details</a>
                            <?php else: ?>
                                <p class="availability out-of-stock">Out of stock</p>
                            <?php endif; ?>
                            <ul style="display: none;" class="add-to-links">
                                <?php if ($this->helper('wishlist')->isAllow()) : ?>
                                    <li><a href="<?php echo $this->helper('wishlist')->getAddUrl($_product) ?>" class="link-wishlist"><?php echo $this->__('Add to Wishlist') ?></a></li>
                                <?php endif; ?>
                                <?php if($_compareUrl=$this->getAddToCompareUrl($_product)): ?>
                                    <li><span class="separator">|</span> <a href="<?php echo $_compareUrl ?>" class="link-compare"><?php echo $this->__('Add to Compare') ?></a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach ?>
    </div>
    <script type="text/javascript">
        jQuery('.col-left.sidebar.col-left-first').insertBefore('.category-products');
    </script>
    <script type="text/javascript">
        decorateGeneric($$('ul.products-grid'), ['odd','even','first','last'])
        jQuery(document).ready(function() {
            var cnnt, sData = [];
            cnnt = jQuery('.mm-cart-count').text();
            cnnt = parseInt(cnnt);
            wrap = '<ul id="cart-sidebar" class="mini-products-list"></ul>';
            if(cnnt === 0) {
                jQuery('.minicart-wrapper').html(wrap);
            }
            else {
                //
            }

            // this is used to find the exact product that lives in color & size
            Array.prototype.diff = function(arr2) {
                var ret = [];
                this.sort();
                arr2.sort();
                for(var i = 0; i < this.length; i += 1) {
                    if(arr2.indexOf(this[i]) > -1){
                        ret.push(this[i]);
                    }
                }
                return ret;
            };

            // When the product color is clicked
            jQuery('.amconf-image-container').on('click', function(e) {
                e.preventDefault();
                let item1, size, id92, config, btn, obj, obj92, obj133, opt92, opt133, opt92id, opt133id, item1Arry, item2Arry, simpleId;
                item1 = jQuery(this);
                id92 = item1.data('id');
                config = item1.data('size');
                btn = jQuery('.mm-pid-'+config+' .mm-qa-btn');
                obj = eval('mxConfig_'+config); // This references the product object
                obj92 = obj.attributes[92];
                obj133 = obj.attributes[133];
                opt92 = obj92.options;
                item1Arry = opt92[id92].products;
                opt92id = opt92[id92].id;
                parent = item1.parent().parent().parent();
                btn.removeClass('active');

                // Check to see if the product object has size
                if(obj133) {
                    opt133 = obj133.options;
                    size = parent.siblings('#data-id-2').children('.input-box').children('.amconf-images-container').children('.amconf-image-container');

                    // Loop through the product sizes and set an incremental id
                    // This is used to select the correct option within the size Object -> attributes[133]
                    size.each(function(index) {
                        let sizeItem, sizeArr;
                        sizeItem = jQuery(this);
                        sizeItem.attr('data-id',index);
                        index++;
                    });

                    // When the product size is clicked
                    size.on('click', function() {
                        let item2, id133, saleable;
                        item2 = jQuery(this);
                        id133 = item2.data('id');                    
                        item2Arry = opt133[id133].products;
                        opt133id = opt133[id133].id;
                        saleable = item2.children('.amconf-image');
                        btn.removeClass('active');
                        // simpleId = item1Arry.diff(item2Arry) this will get the exact simple id
                        if(!saleable.hasClass('amconf-image-outofstock')) {
                            btn.data('92',opt92id).data('133',opt133id).addClass('active');
                        }
                    });

                }
                else {
                    btn.data('92',opt92id).data('133','').addClass('active');
                }
            });

            // Adds the product to cart using AJAX
            jQuery('.mm-qa-btn').on('click', function(e) {
                e.preventDefault();
                let item, id, id92, id133, name, cnt, msg, msgCtr, msgCon;
                item = jQuery(this);
                id = item.data('xid');
                id92 = item.data('92');
                id133 = item.data('133');
                name = jQuery('.mm-pid-'+id+' .product-name').text();
                cnt = parseInt(jQuery('.mm-cart-count').text());
                msg = jQuery('.mm-cart-message');
                msgCtr = jQuery('.mm-cart-message-container');
                msgCon = jQuery('.mm-cart-message-content');
                jQuery.ajax
                ({
                    type: "POST",
                    url: "/ajax-cart",
                    data: { id: id, v92: id92, v133: id133 },
                    cache: false,
                    beforeSend: function() {
                        console.log('sent');
                        item.removeClass('active');
                        jQuery('.mm-cart-message-content').html('');
                        msgCon.html('<p><span>'+name+'</span> <br> successfully added to your cart.</p>');
                        jQuery('.mm-cart-count').html(cnt + 1);
                        msg.addClass('active');
                        setTimeout(function() {
                            msgCtr.addClass('active');
                            msgCon.addClass('active');
                        }, 50);
                    },
                    success: function(html) {
                        console.log('success');
                    },
                    complete: function() {
                        console.log('complete');
                        setTimeout(function() {
                            msgCon.removeClass('active');
                            msgCtr.removeClass('active');
                            setTimeout(function() {
                                msg.removeClass('active');
                            }, 250);
                        }, 500);
                    }
                });
            });

            var ids = [];
            jQuery('.product-info #data-id-1').each(function(index) {
                var item, id, a, b, c;
                item = jQuery(this);
                id = item.data('size');
                ids.push(id);
                a = item.parent();
                b = a.parent();
                c = b.parent();
                c.addClass('mm-pid-'+id);
            });
            
            for (var i = 0; i < ids.length; i++) {
                var id, xd;
                id = ids[i];
                xd = 0;
                jQuery('#amconf-images-92-'+id+' .amconf-image-container').each(function(index) {
                    var item;
                    item = jQuery(this);
                    item.attr('data-id',xd);
                    xd++;
                });
            }
            
            function buildCTA() {
                var today = new Date();
                var dd = today.getDate();
                var mm = today.getMonth()+1; //January is 0!
                var yyyy = today.getFullYear();
                if(dd<10) {
                    dd='0'+dd
                } 
                if(mm<10) {
                    mm='0'+mm
                } 
                today = yyyy+'-'+mm+'-'+dd;
                jQuery('.mm-product-list-item').each(function() {
                    
                    var item, id, prices = [], dates = [], count, td, od, ld, otTime, otDiff, ltTime, ltDiff;
                    
                    item = jQuery(this);
                    id = item.data('id');
                    
                    jQuery('.mm-product-list-custom-list-'+id+' li').each(function() {
                        var simp, price, sprice, date;
                        simp = jQuery(this);
                        price = simp.data('price');
                        sprice = simp.data('sprice');
                        date = simp.data('date');
                        if(price === sprice) {
                            //
                        }
                        else {
                            prices.push(sprice);
                        }
                        dates.push(date);
                    });
                    priceCount = prices.length;
                    count = dates.length;
                    // Dates
                    td = new Date(today); // Today
                    od = new Date(dates[0]); // First Date
                    ld = new Date(dates[(count-1)]); // Last Date
                    otTime = Math.abs(td.getTime() - od.getTime());
                    otDiff = Math.ceil(otTime / (1000 * 3600 * 24));
                    ltTime = Math.abs(td.getTime() - ld.getTime());
                    ltDiff = Math.ceil(ltTime / (1000 * 3600 * 24));
                    if(priceCount > 0) {
                        // jQuery('.mm-product-list-item-'+id+' .mm-custom-product-cta').html('On Sale');
                        jQuery('.mm-product-list-item-'+id+' .mm-custom-product-cta-x').addClass('active').html('<div class="on-sale-indicator"></div><div class="on-sale-text">On Sale</div>');
                    }
                    else {
                        if(item.data('highlight') == 1339) {
                            // jQuery('.mm-product-list-item-'+id+' .mm-custom-product-cta').html('New Arrival');
                            jQuery('.mm-product-list-item-'+id+' .mm-custom-product-cta-x').addClass('active').html('<div class="new-arrival-indicator"></div><div class="new-arrival-text">New Arrival</div>');
                        }
                        else {
                            if(count > 1 ){
                                if(ltDiff <= 30) {
                                    // jQuery('.mm-product-list-item-'+id+' .mm-custom-product-cta').html('New Colors');
                                    jQuery('.mm-product-list-item-'+id+' .mm-custom-product-cta-x').addClass('active').html('New Colors');
                                }
                                else {
                                    //
                                }
                            }
                            else {
                                //
                            }
                        }
                    }
                });
            }
            buildCTA();
        });

        jQuery(window).load(function() {

            jQuery('.amconf-block #data-id-1').each(function() {
                var item, pid, count, list, link;
                item = jQuery(this);
                pid = item.data('size');
                count = jQuery('#data-id-1 #amconf-images-92-'+pid+' .amconf-image-container').length;
                list = jQuery('#data-id-1 #amconf-images-92-'+pid+' .amconf-image-container');
                list.slice(5,count).addClass('amconf-hidden-items');
                link = jQuery('.mm-pid-'+pid).data('link');
                if(count >= 5) {
                    jQuery('#data-id-1 #amconf-images-92-'+pid).children('.amconf-image-container:eq(5)').after('<a href="'+link+'"><div class="amconf-more-images">+</div></a>');
                }
            });

        });

    </script>
    <?php endif; ?>

    <div class="container toolbar-bottom">
        <?php echo $this->getToolbarHtml() ?>
    </div>
</div>
<?php endif; ?>

<?php
    //set product collection on after blocks
    $_afterChildren = $this->getChild('after')->getSortedChildren();
    foreach($_afterChildren as $_afterChildName):
        $_afterChild = $this->getChild('after')->getChild($_afterChildName);
        $_afterChild->setProductCollection($_productCollection);
    ?>
    <?php echo $_afterChild->toHtml(); ?>
<?php endforeach; ?>

</div>