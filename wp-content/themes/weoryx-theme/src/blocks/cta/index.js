(function (blocks, element, blockEditor) {
    var el = element.createElement;
    var useBlockProps = blockEditor.useBlockProps;
    var RichText = blockEditor.RichText;
    var InspectorControls = blockEditor.InspectorControls;
    var PanelBody = blockEditor.components.PanelBody;
    var TextControl = blockEditor.components.TextControl;
    var TextareaControl = blockEditor.components.TextareaControl;

    blocks.registerBlockType('weoryx/cta', {
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var blockProps = useBlockProps();

            return el(
                element.Fragment,
                {},
                el(
                    InspectorControls,
                    {},
                    el(
                        PanelBody,
                        { title: 'Content Settings', initialOpen: true },
                        el(TextControl, {
                            label: 'Title',
                            value: attributes.title,
                            onChange: function (val) { setAttributes({ title: val }); }
                        }),
                        el(TextareaControl, {
                            label: 'Description',
                            value: attributes.description,
                            onChange: function (val) { setAttributes({ description: val }); }
                        }),
                        el(TextControl, {
                            label: 'Button Text',
                            value: attributes.buttonText,
                            onChange: function (val) { setAttributes({ buttonText: val }); }
                        }),
                        el(TextControl, {
                            label: 'Button URL',
                            value: attributes.buttonUrl,
                            onChange: function (val) { setAttributes({ buttonUrl: val }); }
                        })
                    )
                ),
                el(
                    'div',
                    blockProps,
                    el('h3', {}, 'CTA Block Preview'),
                    el('h2', {}, attributes.title),
                    el('p', {}, attributes.description),
                    el('button', { className: 'button' }, attributes.buttonText)
                )
            );
        },
        save: function () {
            return null; // Rendered via PHP
        }
    });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor);
