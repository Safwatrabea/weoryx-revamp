(function (blocks, element, blockEditor, components) {
    var el = element.createElement;
    var RichText = blockEditor.RichText;
    var MediaUpload = blockEditor.MediaUpload;
    var InspectorControls = blockEditor.InspectorControls;
    var PanelBody = components.PanelBody;
    var TextControl = components.TextControl;
    var Button = components.Button;
    var useBlockProps = blockEditor.useBlockProps;

    blocks.registerBlockType('weoryx/video', {
        title: 'Video Section',
        icon: 'video-alt',
        category: 'weoryx',
        attributes: {
            tag: { type: 'string', default: 'Video Marketing' },
            title: { type: 'string', default: 'Get Your Message Across Via Video' },
            subtitle: { type: 'string', default: 'Tell your story and engage your audience with compelling video content.' },
            videoUrl: { type: 'string', default: '#' },
            thumbnailUrl: { type: 'string' }
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
                        { title: 'Video Settings' },
                        el(TextControl, {
                            label: 'Video Link (YouTube/Vimeo/Internal)',
                            value: attributes.videoUrl,
                            onChange: function (val) { setAttributes({ videoUrl: val }); }
                        })
                    )
                ),
                el(
                    'div',
                    { className: 'video-editor-preview', style: { padding: '20px', border: '1px dashed #ccc' } },
                    el(RichText, {
                        tagName: 'span',
                        className: 'section-tag section-tag-center',
                        value: attributes.tag,
                        onChange: function (val) { setAttributes({ tag: val }); },
                        placeholder: 'Tag'
                    }),
                    el(RichText, {
                        tagName: 'h2',
                        className: 'section-title',
                        value: attributes.title,
                        onChange: function (val) { setAttributes({ title: val }); },
                        placeholder: 'Title'
                    }),
                    el(RichText, {
                        tagName: 'p',
                        className: 'section-subtitle',
                        value: attributes.subtitle,
                        onChange: function (val) { setAttributes({ subtitle: val }); },
                        placeholder: 'Subtitle'
                    }),
                    el(
                        'div',
                        { className: 'video-thumb-upload', style: { marginTop: '20px' } },
                        el(MediaUpload, {
                            onSelect: function (media) { setAttributes({ thumbnailUrl: media.url }); },
                            allowedTypes: ['image'],
                            render: function (obj) {
                                return el(Button, {
                                    className: attributes.thumbnailUrl ? 'image-button' : 'button button-large',
                                    onClick: obj.open
                                }, attributes.thumbnailUrl ? el('img', { src: attributes.thumbnailUrl, style: { maxWidth: '200px' } }) : 'Upload Thumbnail');
                            }
                        })
                    )
                )
            );
        },
        save: function () {
            return null;
        }
    });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components);
