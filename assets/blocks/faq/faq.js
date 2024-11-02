import {
    InnerBlocks,
    RichText,
    InspectorControls,
} from "@wordpress/block-editor";
import { PanelBody, PanelRow, ToggleControl } from "@wordpress/components";
import { registerBlockType } from "@wordpress/blocks";
import { useState } from "@wordpress/element";
import metadata from "./block.json";

registerBlockType(metadata, {
    edit: EditComponent,
    save: SaveComponent,
});

function EditComponent(props) {
    const [hasOpen, setOpen] = useState(false);

    function handleTextChange(x) {
        props.setAttributes({ text: x });
    }

    function handleOpenChange(x) {
        setOpen((state) => !state);
        props.setAttributes({ open: x });
    }

    return (
        <>
            <InspectorControls>
                <PanelBody title="Open settings" initialOpen={true}>
                    <PanelRow>
                        <ToggleControl
                            label="Open toggle"
                            help={
                                hasOpen
                                    ? "Will be open by default."
                                    : "Will be closed by default."
                            }
                            checked={hasOpen}
                            onChange={handleOpenChange}
                        />
                    </PanelRow>
                </PanelBody>
            </InspectorControls>
            <div className="faq">
                <RichText
                    tagName="button"
                    className="faqToggle"
                    value={props.attributes.text}
                    onChange={handleTextChange}
                />
                <div className="faqContent">
                    <InnerBlocks />
                </div>
            </div>
        </>
    );
}

// If you want your block to use a php callback return null.
function SaveComponent() {
    return <InnerBlocks.Content />;
}
