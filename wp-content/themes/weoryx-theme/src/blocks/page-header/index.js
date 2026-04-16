(function (blocks, element, blockEditor, components) {
    var el = element.createElement;
    var RichText = blockEditor.RichText;
    var InspectorControls = blockEditor.InspectorControls;
    var PanelBody = components.PanelBody;
    var ToggleControl = components.ToggleControl;
    var useBlockProps = blockEditor.useBlockProps;

    blocks.registerBlockType('weoryx/page-header', {
        title: 'Page Header (Inner Pages)',
        icon: 'welcome-widgets-menus',
        category: 'weoryx',
        attributes: {
            title: { type: 'string' },
            showBreadcrumbs: { type: 'boolean', default: true }
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
                        { title: 'Header Settings' },
                        el(ToggleControl, {
                            label: 'Show Breadcrumbs',
                            checked: attributes.showBreadcrumbs,
                            onChange: function (val) { setAttributes({ showBreadcrumbs: val }); }
                        })
                    )
                ),
                el(
                    'div',
                    { className: 'page-header-editor', style: { padding: '40px', background: '#1a5f7a', color: '#fff', textAlign: 'center' } },
                    el(RichText, {
                        tagName: 'h1',
                        className: 'page-title',
                        value: attributes.title || 'Page Title',
                        onChange: function (val) { setAttributes({ title: val }); },
                        placeholder: 'Enter Page Title'
                    }),
                    attributes.showBreadcrumbs && el('div', { className: 'page-breadcrumb' }, 'Home > Current Page')
                )
            );
        },
        save: function () { return null; }
    });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor, window.wp.components);
