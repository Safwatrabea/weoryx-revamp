(function (blocks, element, blockEditor) {
    var el = element.createElement;
    var useBlockProps = blockEditor.useBlockProps;

    blocks.registerBlockType('weoryx/contact', {
        title: 'Contact Grid Section',
        icon: 'email-alt',
        category: 'weoryx',
        edit: function () {
            var blockProps = useBlockProps({
                style: { padding: '40px', background: '#f5f5f5', textAlign: 'center' }
            });
            return el('div', blockProps, 'Contact Section Block');
        },
        save: function () { return null; }
    });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor);
