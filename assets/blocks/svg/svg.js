import { Button, PanelBody, PanelRow } from "@wordpress/components";
import {
	InnerBlocks,
	InspectorControls,
	MediaUpload,
	MediaUploadCheck,
	useBlockProps,
} from "@wordpress/block-editor";
import { registerBlockType } from "@wordpress/blocks";

registerBlockType("utopian/svg", {
	edit: EditComponent,
	save: SaveComponent,
});

function EditComponent(props) {
	function onFileSelect(x) {
		props.setAttributes({ svgURL: x.url });
	}

	const blockProps = useBlockProps();

	return (
		<div {...blockProps}>
			<InspectorControls>
				<PanelBody title="Select an icon" initialOpen={true}>
					<PanelRow>
						<MediaUploadCheck>
							<MediaUpload
								onSelect={onFileSelect}
								value={props.attributes.svgURL}
								allowedTypes={"image/svg+xml"}
								render={({ open }) => {
									return (
										<>
											<Button onClick={open} variant="secondary">
												{props.attributes.svgURL != undefined && (
													<>
														<img
															style={{
																height: "80%",
																display: "block",
																width: "auto",
																marginRight: "10px",
															}}
															src={props.attributes.svgURL}
														/>
														Change Icon
													</>
												)}
												{props.attributes.svgURL === undefined && (
													<>Select Icon</>
												)}
											</Button>
										</>
									);
								}}
							/>
						</MediaUploadCheck>
					</PanelRow>
				</PanelBody>
			</InspectorControls>
			<img class="svg-block" src={props.attributes.svgURL} alt="svg" />
		</div>
	);
}

function SaveComponent() {
	return <InnerBlocks.Content />;
}
