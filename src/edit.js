import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps, AlignmentToolbar, BlockControls, ColorPalette } from '@wordpress/block-editor';
import { SelectControl, RangeControl, PanelBody, PanelRow, ToggleControl } from '@wordpress/components';
import './editor.scss';
import ServerSideRender from '@wordpress/server-side-render';

export default function Edit(props) {
	const blockProps = useBlockProps();
	const { ...propsForServer } = props.attributes;

	return (
		[
			<InspectorControls>
				<PanelBody title={__('Product Settings', 'wclpg-block')}>

					<PanelRow>
						<RangeControl
							value={props.attributes.gridGap}
							style={{ width: "100%" }}
							label={__('Grid Gap', 'wclpg-block')}
							min={1}
							max={100}
							onChange={(value) => props.setAttributes({ gridGap: parseInt(value) })} />
					</PanelRow>

					<PanelRow>
						<ToggleControl
							label={__('Sale Tag', 'wclpg-block')}
							checked={props.attributes.displaySaleTag}
							onChange={(value) => props.setAttributes({ displaySaleTag: value })}
						/>
					</PanelRow>

					<PanelRow>
						<ToggleControl
							label={__('Product Title', 'wclpg-block')}
							checked={props.attributes.displayProductTitle}
							onChange={(value) => props.setAttributes({ displayProductTitle: value })}
						/>
					</PanelRow>

					{ /* Conditional InspectorControl */}
					{
						props.attributes.displayProductTitle == true && (
							<PanelRow>
								<div style={{ marginTop: '5px', marginBottom: '5px' }}>
									<p><strong>{__('Product Title Color: ', 'wclpg-block')}</strong></p>
									<ColorPalette
										colors={[
											{ name: 'red', color: '#f00' },
											{ name: 'green', color: '#0f0' },
											{ name: 'blue', color: '#00f' },
										]}
										value={props.attributes.productTitleColor}
										style={{ width: "100%" }}
										onChange={(value) => props.setAttributes({ productTitleColor: value })}
									/>
								</div>
							</PanelRow>
						)
					}

					<PanelRow>
						<ToggleControl
							label={__('Product Price', 'wclpg-block')}
							checked={props.attributes.displayProductPrice}
							onChange={(value) => props.setAttributes({ displayProductPrice: value })}
						/>
					</PanelRow>

					{ /* Conditional InspectorControl */}
					{
						props.attributes.displayProductPrice == true && (
							<PanelRow>
								<div style={{ marginTop: '5px', marginBottom: '5px' }}>
									<p><strong>{__('Product Price Color: ', 'wclpg-block')}</strong></p>
									<ColorPalette
										colors={[
											{ name: 'red', color: '#f00' },
											{ name: 'green', color: '#0f0' },
											{ name: 'blue', color: '#00f' },
										]}
										value={props.attributes.productPriceColor}
										style={{ width: "100%" }}
										onChange={(value) => props.setAttributes({ productPriceColor: value })}
									/>
								</div>
							</PanelRow>
						)
					}

					<PanelRow>
						<ToggleControl
							label={__('Product Button', 'wclpg-block')}
							checked={props.attributes.displayAddToCartBtn}
							onChange={(value) => props.setAttributes({ displayAddToCartBtn: value })}
						/>
					</PanelRow>

					{ /* Conditional InspectorControl */}
					{
						props.attributes.displayAddToCartBtn == true && (
							<PanelRow>
								<div style={{ marginTop: '5px', marginBottom: '5px' }}>
									<p><strong>{__('Product Button Text Color: ', 'wclpg-block')}</strong></p>
									<ColorPalette
										colors={[
											{ name: 'red', color: '#f00' },
											{ name: 'green', color: '#0f0' },
											{ name: 'blue', color: '#00f' },
										]}
										value={props.attributes.productBtnTextColor}
										style={{ width: "100%" }}
										onChange={(value) => props.setAttributes({ productBtnTextColor: value })}
									/>
								</div>
							</PanelRow>
						)
					}

					{ /* Conditional InspectorControl */}
					{
						props.attributes.displayAddToCartBtn == true && (
							<PanelRow>
								<div style={{ marginTop: '5px', marginBottom: '5px' }}>
									<p><strong>{__('Product Button Bg Color: ', 'wclpg-block')}</strong></p>
									<ColorPalette
										colors={[
											{ name: 'red', color: '#f00' },
											{ name: 'green', color: '#0f0' },
											{ name: 'blue', color: '#00f' },
										]}
										value={props.attributes.productBtnBgColor}
										style={{ width: "100%" }}
										onChange={(value) => props.setAttributes({ productBtnBgColor: value })}
									/>
								</div>
							</PanelRow>
						)
					}

				</PanelBody>
			</InspectorControls>,

			<div {...blockProps}>
				<ServerSideRender
					block="create-block/wc-latest-products-grid-block"
					attributes={propsForServer}
				/>
			</div>
		]
	);
}
