(function (blocks, element, blockEditor, components) {
    var el = element.createElement;
    var RichText = blockEditor.RichText;
    var useBlockProps = blockEditor.useBlockProps;

    blocks.registerBlockType('weoryx/services', {
        title: 'Services Section',
        icon: 'clipboard',
        category: 'weoryx',
        attributes: {
            title: { type: 'string', default: 'What We Offer' },
            services: {
                type: 'array',
                default: [
                    { title: 'Web Development', desc: 'Custom websites tailored to your needs.', icon: 'fa-code' },
                    { title: 'Digital Marketing', desc: 'Grow your brand with our expert marketing.', icon: 'fa-bullseye' }
                ]
            }
        },
        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;
            var blockProps = useBlockProps();

            function updateService(index, key, value) {
                var newServices = [...attributes.services];
                newServices[index] = { ...newServices[index], [key]: value };
                setAttributes({ services: newServices });
            }

            function addService() {
                setAttributes({ services: [...attributes.services, { title: '', desc: '', icon: 'fa-star' }] });
            }

            function removeService(index) {
                var newServices = attributes.services.filter((_, i) => i !== index);
                setAttributes({ services: newServices });
            }

            return el(
                'div',
                blockProps,
                el(RichText, {
                    tagName: 'h2',
                    value: attributes.title,
                    onChange: function (val) { setAttributes({ title: val }); },
                    placeholder: 'Section Title'
                }),
                el('div', { className: 'services-editor-grid' },
                    attributes.services.map(function (service, index) {
                        return el('div', { key: index, className: 'service-editor-item', style: { border: '1px solid #eee', padding: '10px', marginBottom: '10px' } },
                            el(RichText, {
                                tagName: 'h4',
                                value: service.title,
                                onChange: function (val) { updateService(index, 'title', val); },
                                placeholder: 'Service Title'
                            }),
                            el(RichText, {
                                tagName: 'p',
                                value: service.desc,
                                onChange: function (val) { updateService(index, 'desc', val); },
                                placeholder: 'Service Description'
                            }),
                            el('button', { onClick: function () { removeService(index); } }, 'Delete')
                        );
                    })
                ),
                el('button', { onClick: addService }, 'Add Service')
            );
        },
        save: function () {
            return null;
        }
    });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components);
