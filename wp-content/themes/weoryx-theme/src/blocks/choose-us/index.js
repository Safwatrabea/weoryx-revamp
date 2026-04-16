(function (blocks, element, blockEditor) {
    var el = element.createElement;
    var useBlockProps = blockEditor.useBlockProps;

    blocks.registerBlockType('weoryx/choose-us', {
        title: 'Why Choose Us',
        icon: 'thumbs-up',
        category: 'weoryx',
        edit: function () {
            var blockProps = useBlockProps({
                style: { padding: '40px', background: '#f5f5f5', textAlign: 'center' }
            });
            return el('div', blockProps, 'Why Choose Us Section');
        },
        save: function () { return null; }
    });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor);
