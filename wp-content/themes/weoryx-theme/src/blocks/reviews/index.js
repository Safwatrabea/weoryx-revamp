(function (blocks, element, blockEditor) {
    var el = element.createElement;
    var RichText = blockEditor.RichText;
    var useBlockProps = blockEditor.useBlockProps;

    blocks.registerBlockType('weoryx/testimonials-v2', {
        title: 'Testimonials Slider',
        icon: 'format-quote',
        category: 'weoryx',
        attributes: {
            title: { type: 'string', default: 'What Our Clients Say' }
        },
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var blockProps = useBlockProps();

            return el(
                'div',
                blockProps,
                el(RichText, {
                    tagName: 'h2',
                    value: attributes.title,
                    onChange: function (val) { setAttributes({ title: val }); },
                    placeholder: 'Section Title'
                }),
                el('div', { className: 'testimonials-editor-placeholder', style: { padding: '20px', background: '#f9f9f9', border: '1px solid #ddd', textAlign: 'center' } },
                    'Testimonials from the dashboard will be displayed here in a slider.'
                )
            );
        },
        save: function () {
            return null;
        }
    });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor);
