(function (blocks, element, blockEditor) {
    var el = element.createElement;
    var useBlockProps = blockEditor.useBlockProps;

    blocks.registerBlockType('weoryx/steps', {
        title: 'Step Process',
        icon: 'editor-ol',
        category: 'weoryx',
        edit: function () {
            var blockProps = useBlockProps({
                style: { padding: '40px', background: '#f9f9f9', textAlign: 'center' }
            });
            return el('div', blockProps, 'Step Process Section');
        },
        save: function () { return null; }
    });
})(window.wp.blocks, window.wp.element, window.wp.blockEditor);
