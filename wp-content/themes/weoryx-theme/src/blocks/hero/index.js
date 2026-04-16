(function (blocks, element, blockEditor, components) {
    var el = element.createElement;
    var RichText = blockEditor.RichText;
    var MediaUpload = blockEditor.MediaUpload;
    var InspectorControls = blockEditor.InspectorControls;
    var PanelBody = components.PanelBody;
    var TextControl = components.TextControl;
    var Button = components.Button;
    var useBlockProps = blockEditor.useBlockProps;

    blocks.registerBlockType('weoryx/hero', {
        title: 'Hero Section (Swiper)',
        icon: 'cover-image',
        category: 'weoryx',
        attributes: {
            slides: {
                type: 'array',
                default: [
                    {
                        title: 'DIGITAL MARKETING SOLUTION FOR YOUR BUSINESS',
                        tag: 'Digital Marketing Agency',
                        description: 'We help businesses transform their digital presence through strategic marketing, creative design, and cutting-edge technology solutions.',
                        imageUrl: '',
                        buttonText: 'Contact Us',
                        buttonUrl: '/contact'
                    }
                ]
            }
        },
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var blockProps = useBlockProps();

            function updateSlide(index, field, value) {
                var newSlides = attributes.slides.slice();
                newSlides[index] = Object.assign({}, newSlides[index], { [field]: value });
                setAttributes({ slides: newSlides });
            }

            function addSlide() {
                var newSlides = attributes.slides.slice();
                newSlides.push({
                    title: 'New Slide Title',
                    tag: 'Tag',
                    description: 'Slide Description',
                    imageUrl: '',
                    buttonText: 'Action',
                    buttonUrl: '#'
                });
                setAttributes({ slides: newSlides });
            }

            function removeSlide(index) {
                var newSlides = attributes.slides.filter(function (_, i) { return i !== index; });
                setAttributes({ slides: newSlides });
            }

            return el(
                'div',
                blockProps,
                el(
                    'div',
                    { className: 'hero-editor-wrapper', style: { border: '2px solid #1a5f7a', padding: '20px', borderRadius: '10px' } },
                    el('h3', {}, 'Hero Slides Management'),
                    attributes.slides.map(function (slide, index) {
                        return el(
                            'div',
                            { key: index, style: { marginBottom: '30px', padding: '20px', background: '#f9f9f9', border: '1px solid #ddd' } },
                            el('div', { style: { display: 'flex', justifyContent: 'space-between' } },
                                el('strong', {}, 'Slide #' + (index + 1)),
                                el(Button, { isDestructive: true, onClick: function () { removeSlide(index); } }, 'Remove Slide')
                            ),
                            el(TextControl, {
                                label: 'Tag',
                                value: slide.tag,
                                onChange: function (val) { updateSlide(index, 'tag', val); }
                            }),
                            el(TextControl, {
                                label: 'Title',
                                value: slide.title,
                                onChange: function (val) { updateSlide(index, 'title', val); }
                            }),
                            el(TextControl, {
                                label: 'Description',
                                value: slide.description,
                                onChange: function (val) { updateSlide(index, 'description', val); }
                            }),
                            el(MediaUpload, {
                                onSelect: function (media) { updateSlide(index, 'imageUrl', media.url); },
                                allowedTypes: ['image'],
                                render: function (obj) {
                                    return el(Button, {
                                        className: slide.imageUrl ? 'image-button' : 'button button-large',
                                        onClick: obj.open,
                                        style: { marginTop: '10px' }
                                    }, slide.imageUrl ? el('img', { src: slide.imageUrl, style: { maxWidth: '100px', display: 'block' } }) : 'Upload Slide Image');
                                }
                            }),
                            el('div', { style: { display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '10px', marginTop: '10px' } },
                                el(TextControl, {
                                    label: 'Button Text',
                                    value: slide.buttonText,
                                    onChange: function (val) { updateSlide(index, 'buttonText', val); }
                                }),
                                el(TextControl, {
                                    label: 'Button URL',
                                    value: slide.buttonUrl,
                                    onChange: function (val) { updateSlide(index, 'buttonUrl', val); }
                                })
                            )
                        );
                    }),
                    el(Button, { isPrimary: true, onClick: addSlide }, 'Add Slide')
                )
            );
        },
        save: function () {
            return null;
        }
    });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components);
