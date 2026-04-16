(function (blocks, element, blockEditor, components) {
    var el = element.createElement;
    var RichText = blockEditor.RichText;
    var InspectorControls = blockEditor.InspectorControls;
    var PanelBody = components.PanelBody;
    var RangeControl = components.RangeControl;
    var useBlockProps = blockEditor.useBlockProps;

    blocks.registerBlockType('weoryx/portfolio', {
        title: 'Portfolio Grid',
        icon: 'portfolio',
        category: 'weoryx',
        attributes: {
            title: { type: 'string', default: 'Recent Projects' },
            count: { type: 'number', default: 4 }
        },
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var blockProps = useBlockProps();

            return el(
                'div',
                blockProps,
                el(
                    InspectorControls,
                    {},
                    el(
                        PanelBody,
                        { title: 'Grid Settings' },
                        el(RangeControl, {
                            label: 'Number of Projects',
                            value: attributes.count,
                            onChange: function (val) { setAttributes({ count: val }); },
                            min: 1,
                            max: 12
                        })
                    )
                ),
                el(RichText, {
                    tagName: 'h2',
                    value: attributes.title,
                    onChange: function (val) { setAttributes({ title: val }); },
                    placeholder: 'Section Title'
                }),
                el('div', { className: 'portfolio-editor-placeholder', style: { padding: '20px', background: '#f9f9f9', border: '1px solid #ddd', textAlign: 'center' } },
                    'Projects will be displayed here based on the count: ' + attributes.count
                )
            );
        },
        save: function () {
            return null;
        }
    });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components);
