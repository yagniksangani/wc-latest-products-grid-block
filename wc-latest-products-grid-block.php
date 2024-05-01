<?php
/**
 * Plugin Name:       WooCommerce Latest Products Grid Block
 * Description:       Display the latest woocommerce products with this custom gutenberg block.
 * Requires at least: 6.1
 * Requires PHP:      7.4
 * Version:           1.0.0
 * Tested up to:      6.5.2
 * Author:            Yagnik Sangani
 * Author URI:        https://profiles.wordpress.org/yagniksangani/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wclpg-block
 * Domain Path:       /languages
 *
 * @package           WCLPG_BLOCK
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function create_block_wc_latest_products_grid_block_block_init() {
	register_block_type(
		__DIR__ . '/build',
		array(
			'render_callback' => 'wc_latest_products_list_render_callback',
			'attributes'      => array(
				'gridGap'             => array(
					'type'    => 'integer',
					'default' => 10,
				),
				'displaySaleTag'      => array(
					'type'    => 'boolean',
					'default' => 1,
				),
				'displayProductTitle' => array(
					'type'    => 'boolean',
					'default' => 1,
				),
				'displayProductPrice' => array(
					'type'    => 'boolean',
					'default' => 1,
				),
				'displayAddToCartBtn' => array(
					'type'    => 'boolean',
					'default' => 1,
				),
				'productTitleColor'   => array(
					'type'    => 'string',
					'default' => '#000',
				),
				'productPriceColor'   => array(
					'type'    => 'string',
					'default' => '#000',
				),
				'productBtnTextColor' => array(
					'type'    => 'string',
					'default' => '#000',
				),
				'productBtnBgColor'   => array(
					'type'    => 'string',
					'default' => '#ccc',
				),
			),
		)
	);
}
add_action( 'init', 'create_block_wc_latest_products_grid_block_block_init' );


/**
 * Child plugin activate.
 */
function wclpg_child_plugin_activate() {
	// Require parent plugin.
	if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) && current_user_can( 'activate_plugins' ) ) {

		deactivate_plugins( plugin_basename( __FILE__ ) );

		// Stop activation redirect and show error.
		wp_die( 'Sorry, but this plugin requires the "WooCommerce" Plugin to be installed and active. <br><a href="' . esc_url( admin_url( 'plugins.php' ) ) . '">&laquo; Return to Plugins</a>' );
	}
}
register_activation_hook( __FILE__, 'wclpg_child_plugin_activate' );


/**
 * Perform action once plugin loaded.
 */
function wclpg_load_plugin_textdomain() {
	// Require parent plugin.
	if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) && current_user_can( 'activate_plugins' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
	}
}
add_action( 'plugins_loaded', 'wclpg_load_plugin_textdomain' );


/**
 * Render Callback.
 *
 * @param  array $attributes attributes.
 *
 * @return string
 */
function wc_latest_products_list_render_callback( $attributes ) {
	global  $woocommerce;
	$currency = get_woocommerce_currency_symbol();

	$args = array(
		'post_type'      => 'product',
		'posts_per_page' => '3',
		'post_status'    => array( 'publish' ),
		'orderby'        => 'date',
		'order'          => 'desc',
	);

	$latest_products = get_posts( $args );
	$count_products  = count( $latest_products );

	$style_grid_gap = '';
	if ( $attributes['gridGap'] ) {
		$style_grid_gap = 'style=margin-right:' . $attributes['gridGap'] . 'px;';
	}

	$producttitlecolor = '';
	if ( $attributes['productTitleColor'] ) {
		$producttitlecolor = 'style=color:' . $attributes['productTitleColor'] . ';';
	}

	$productpricecolor = '';
	if ( $attributes['productPriceColor'] ) {
		$productpricecolor = 'style=color:' . $attributes['productPriceColor'] . ';';
	}

	$productbtntextcolor = '';
	if ( $attributes['productBtnTextColor'] ) {
		$productbtntextcolor = 'color:' . $attributes['productBtnTextColor'] . ';';
	}

	$productbtnbgcolor = '';
	if ( $attributes['productBtnBgColor'] ) {
		$productbtnbgcolor = 'background-color:' . $attributes['productBtnBgColor'] . ';';
	}

	$style_product_btn = 'style=' . $productbtntextcolor . $productbtnbgcolor;

	ob_start();

	if ( $count_products ) {
		?>
		<ul class="wc_latest_products_grid_section products columns-3">
			<?php
			foreach ( $latest_products as $key => $value ) {
				$product_id = $value->ID;
				$product    = wc_get_product( $product_id );

				$product_name    = $product->get_name();
				$product_link    = get_permalink( $product_id );
				$price           = $product->get_price();
				$regular_price   = $product->get_regular_price();
				$sale_price      = $product->get_sale_price();
				$stock_status    = $product->get_stock_status();
				$product_image   = get_the_post_thumbnail_url( $product_id );
				$add_to_cart_url = $product->add_to_cart_url();

				if ( empty( $product_image ) ) {
					$product_image = wc_placeholder_img_src();
				}

				$li_class = '';
				if ( ( $key + 1 ) === $count_products ) {
					$li_class       = 'last';
					$style_grid_gap = '';
				} elseif ( 0 === $key ) {
					$li_class = 'first';
				}
				?>
				<li class="product type-product status-publish <?php echo esc_attr( $li_class ); ?>" <?php echo esc_attr( $style_grid_gap ); ?>>

					<a href="<?php echo esc_url( $product_link ); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
						<img width="324" height="324" src="<?php echo esc_url( $product_image ); ?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="" decoding="async" loading="lazy">
					</a>

					<?php if ( $attributes['displayProductTitle'] ) { ?>
						<h2 class="woocommerce-loop-product__title" <?php echo esc_attr( $producttitlecolor ); ?>><?php echo esc_html( $product_name ); ?></h2>
					<?php } ?>

					<?php if ( ! empty( $sale_price ) && $attributes['displaySaleTag'] ) { ?>
						<span class="onsale"><?php esc_html_e( 'Sale!', 'wclpg-block' ); ?></span>
					<?php } ?>

					<?php if ( $attributes['displayProductPrice'] ) { ?>
						<p <?php echo esc_attr( $productpricecolor ); ?>><?php echo $product->get_price_html(); // phpcs:ignore. ?></p>
					<?php } ?>

					<?php
					if ( $attributes['displayAddToCartBtn'] ) {
						if ( 'outofstock' !== $stock_status ) {
							if ( $product->is_type( 'simple' ) ) {
								?>
							<a <?php echo esc_attr( $style_product_btn ); ?> href="<?php echo esc_url( $add_to_cart_url ); ?>" data-quantity="1" class="button add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo esc_attr( $product_id ); ?>"><?php esc_html_e( 'Add to cart', 'wclpg-block' ); ?></a>
						<?php } else { ?>
							<a <?php echo esc_attr( $style_product_btn ); ?> target="_blank" href="<?php echo esc_url( $add_to_cart_url ); ?>" data-quantity="1" class="button add_to_cart_button" data-product_id="<?php echo esc_attr( $product_id ); ?>"><?php esc_html_e( 'Buy Now', 'wclpg-block' ); ?></a>
								<?php
						}
						} else {
							?>
						<a <?php echo esc_attr( $style_product_btn ); ?> href="<?php echo esc_url( $product_link ); ?>" class="button add_to_cart_button" data-product_id="<?php echo esc_attr( $product_id ); ?>"><?php esc_html_e( 'Out of stock', 'wclpg-block' ); ?></a>
							<?php
						}
					}
					?>
				</li>
				<?php
			}
			?>
		</ul>
		<?php
	} else {
		echo '<p>' . esc_html_e( 'No products found.', 'wclpg-block' ) . '</p>';
	}

	return ob_get_clean();
}
