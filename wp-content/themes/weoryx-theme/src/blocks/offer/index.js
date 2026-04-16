(function (blocks, element, blockEditor) {
    var el = element.createElement;
    var useBlockProps = blockEditor.useBlockProps;

    blocks.registerBlockType('weoryx/offer', {
        title: 'Special Offer',
        icon: 'star-filled',
        category: 'weoryx',
        edit: function () {
            var blockProps = useBlockProps({
                style: { padding: '40px', background: '#e85a3c', color: '#fff', textAlign: 'center' }
            });
            return el('div', blockProps, 'Special Offer Section');
        },
        save: function () { return null; }
    });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor);
