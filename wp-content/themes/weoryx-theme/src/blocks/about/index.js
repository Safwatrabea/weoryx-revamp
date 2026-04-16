(function (blocks, element, blockEditor, components) {
    var el = element.createElement;
    var RichText = blockEditor.RichText;
    var MediaPlaceholder = blockEditor.MediaPlaceholder;
    var useBlockProps = blockEditor.useBlockProps;

    blocks.registerBlockType('weoryx/about', {
        title: 'About Section',
        icon: 'info',
        category: 'weoryx',
        attributes: {
            title: { type: 'string', default: 'We Are Experts In Digital Solutions' },
            description: { type: 'string', default: 'With years of experience in the digital landscape, we help businesses achieve their goals through innovative strategies and creative execution.' },
            imageUrl: { type: 'string' }
        },
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var blockProps = useBlockProps();

            return el(
                'div',
                blockProps,
                el(
                    'div',
                    {
                        className: 'about-editor-container',
                        style: {
                            padding: '30px',
                            border: '1px dashed #ccc',
                            background: '#f9f9f9'
                        }
                    },
                    el(RichText, {
                        tagName: 'h2',
                        value: attributes.title,
                        onChange: function (val) { setAttributes({ title: val }); },
                        placeholder: 'Enter Title'
                    }),
                    el(RichText, {
                        tagName: 'p',
                        value: attributes.description,
                        onChange: function (val) { setAttributes({ description: val }); },
                        placeholder: 'Enter Description'
                    }),
                    !attributes.imageUrl ? el(MediaPlaceholder, {
                        onSelect: function (media) { setAttributes({ imageUrl: media.url }); },
                        allowedTypes: ['image'],
                        multiple: false,
                        labels: { title: 'About Image' }
                    }) : el('div', {},
                        el('img', { src: attributes.imageUrl, style: { maxWidth: '200px', display: 'block', marginBottom: '10px' } }),
                        el('button', {
                            onClick: function () { setAttributes({ imageUrl: '' }); }
                        }, 'Remove Image')
                    )
                )
            );
        },
        save: function () {
            return null;
        }
    });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components);
